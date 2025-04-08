<?php
namespace Vendor\CustomOrderProcessing\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Sales\Model\Order;
use Vendor\CustomOrderProcessing\Logger\Logger as LoggerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Vendor\CustomOrderProcessing\Api\Data\OrderStatusUpdateLogInterface;
use Vendor\CustomOrderProcessing\Api\OrderStatusLogManagementInterface;
use Magento\Framework\MessageQueue\PublisherInterface;

class OrderStatusChangeObserver implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var OrderStatusUpdateLogInterface
     */
    protected $orderStatusUpdateLog;
    /**
     * @var OrderStatusLogManagementInterface
     */
    protected $orderStatusLogMngmnt;
    /**
     * @var PublisherInterface
     */
    protected $publisher;

    /**
     * @param LoggerInterface $logger
     * @param StoreManagerInterface $storeManager
     * @param OrderStatusUpdateLogInterface $orderStatusUpdateLog
     * @param OrderStatusLogManagementInterface $orderStatusLogMngmnt
     * @param PublisherInterface $publisher
     */
    public function __construct(
        LoggerInterface $logger,
        StoreManagerInterface $storeManager,
        OrderStatusUpdateLogInterface $orderStatusUpdateLog,
        OrderStatusLogManagementInterface $orderStatusLogMngmnt,
        PublisherInterface $publisher
    ) {
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->orderStatusUpdateLog = $orderStatusUpdateLog;
        $this->orderStatusLogMngmnt = $orderStatusLogMngmnt;
        $this->publisher = $publisher;
    }

    /**
     * Execute function
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $oldStatus = $order->getOrigData('status');
        $newStatus = $order->getStatus();

        if ($oldStatus !== $newStatus) {
            $this->logOrderStatusChange($order->getIncrementId(), $oldStatus, $newStatus);

            $shipments = $order->getShipmentsCollection();
            
            foreach ($shipments as $shipment) {
                if (!$shipment->getData('custom_email_sent')) {
                    try {
                        $this->sendShipmentNotificationEmail($order);
                        $shipment->setData('custom_email_sent', 1);
                        $shipment->getResource()->saveAttribute($shipment, 'custom_email_sent');
                    } catch (\Exception $e) {
                        $this->logger->error("Error sending custom shipment email: " . $e->getMessage());
                    }
                }
            }
        }
    }

    /**
     * LogOrderStatusChange function
     *
     * @param string $orderId
     * @param string $oldStatus
     * @param string $newStatus
     * @return void
     */
    private function logOrderStatusChange($orderId, $oldStatus, $newStatus)
    {
        try {
            $this->logger->info(__("Changing order status of order #$orderId from $oldStatus to $newStatus"));
            $orderStatusLog = $this->orderStatusUpdateLog;
            $orderStatusLog->setOrderIncrementId($orderId)
                ->setOldStatus($oldStatus)
                ->setNewStatus($newStatus)
                ->setCreatedAt(date('Y-m-d H:i:s'));
            $this->orderStatusLogMngmnt->save($orderStatusLog);
        } catch (\Exception $e) {
            $this->logger->error(__('Error logging order status change: %1', $e->getMessage()));
        }
    }

    /**
     * SendShipmentNotificationEmail function
     *
     * @param Order $order
     * @return void
     */
    private function sendShipmentNotificationEmail(Order $order)
    {
        try {
            $this->publisher->publish('vendor_customorderprocessing.order.status.email', $order->getEntityId());
            $this->logger->info("Order status change for order {$order->getIncrementId()} added to email queue");
        } catch (\Exception $e) {
            $this->logger->error("Failed to queue order status email: " . $e->getMessage(), [
                'order_id' => $order->getIncrementId(),
                'exception' => $e
            ]);
        }
    }
}
