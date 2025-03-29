<?php
namespace Vendor\CustomOrderProcessing\Api;

use Vendor\CustomOrderProcessing\Api\Data\OrderStatusUpdateLogInterface;
use Vendor\CustomOrderProcessing\Model\Data\OrderStatusUpdateLog;

interface OrderStatusLogManagementInterface
{
    /**
     * Update order status
     *
     * @param OrderStatusUpdateLog $orderStatusLog
     * @return OrderStatusUpdateLog
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function save(OrderStatusUpdateLog $orderStatusLog);
}
