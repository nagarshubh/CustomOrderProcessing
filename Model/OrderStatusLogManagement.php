<?php
namespace Vendor\CustomOrderProcessing\Model;

use Vendor\CustomOrderProcessing\Api\Data\OrderStatusUpdateLogInterface;
use Vendor\CustomOrderProcessing\Api\OrderStatusLogManagementInterface;
use Vendor\CustomOrderProcessing\Model\ResourceModel\OrderStatusLogFactory;
use Magento\Framework\Exception\LocalizedException;
use Vendor\CustomOrderProcessing\Model\Data\OrderStatusUpdateLog;

class OrderStatusLogManagement implements OrderStatusLogManagementInterface
{
    /**
     * @var OrderStatusLogFactory
     */
    public $orderStatusLogResource;

    /**
     * @param OrderStatusLogFactory $orderStatusLogResource
     */
    public function __construct(
        OrderStatusLogFactory $orderStatusLogResource
    ) {
        $this->orderStatusLogResource = $orderStatusLogResource;
    }
    
    /**
     * Save function
     *
     * @param OrderStatusUpdateLog $orderStatusLog
     * @return void
     */
    public function save(OrderStatusUpdateLog $orderStatusLog)
    {
        try {
            $newOrderStatusLog = $this->orderStatusLogResource->create()->save($orderStatusLog);
            return $newOrderStatusLog;
        } catch (\Exception $e) {
            throw new LocalizedException(__('Unable to save Order Status Log.'));
        }
    }
}
