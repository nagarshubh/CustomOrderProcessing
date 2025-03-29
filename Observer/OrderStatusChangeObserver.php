<?php
namespace Vendor\CustomOrderProcessing\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;
use Vendor\CustomOrderProcessing\Model\OrderStatusLogFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Vendor\CustomOrderProcessing\Api\Data\OrderStatusUpdateLogInterface;
use Vendor\CustomOrderProcessing\Api\OrderStatusLogManagementInterface;

class OrderStatusChangeObserver implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var StateInterface
     */
    protected $inlineTranslation;
    /**
     * @var OrderStatusUpdateLogInterface
     */
    protected $orderStatusUpdateLog;
    /**
     * @var OrderStatusLogManagementInterface
     */
    protected $orderStatusLogMngmnt;

    /**
     * @param LoggerInterface $logger
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $inlineTranslation
     * @param OrderStatusUpdateLogInterface $orderStatusUpdateLog
     * @param OrderStatusLogManagementInterface $orderStatusLogMngmnt
     */
    public function __construct(
        LoggerInterface $logger,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        StateInterface $inlineTranslation,
        OrderStatusUpdateLogInterface $orderStatusUpdateLog,
        OrderStatusLogManagementInterface $orderStatusLogMngmnt
    ) {
        $this->logger = $logger;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->orderStatusUpdateLog = $orderStatusUpdateLog;
        $this->orderStatusLogMngmnt = $orderStatusLogMngmnt;
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
            $this->inlineTranslation->suspend();
            $store = $order->getStoreId();
            
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('custom_shipment_notification_email_template')
                ->setTemplateOptions([
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $store
                ])
                ->setTemplateVars(['order' => $order])
                ->setFrom([
                    'email' => 'vendor.support@example.com',
                    'name' => 'Vendor Support'
                ])
                ->addTo($order->getCustomerEmail())
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->error(__('Error sending shipment notification email: %1', $e->getMessage()));
        }
    }
}
