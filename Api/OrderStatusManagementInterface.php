<?php
namespace Vendor\CustomOrderProcessing\Api;

use Vendor\CustomOrderProcessing\Api\Data\OrderStatusUpdateInterface;
use Vendor\CustomOrderProcessing\Api\Data\OrderStatusUpdateResultInterface;

interface OrderStatusManagementInterface
{
    /**
     * Update order status
     *
     * @param string $increment_id
     * @param string $status
     * @return \Vendor\CustomOrderProcessing\Api\Data\OrderStatusUpdateResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function updateOrderStatus(string $increment_id, string $status);

    /**
     * Update order status by request interface
     *
     * @param \Vendor\CustomOrderProcessing\Api\Data\OrderStatusUpdateInterface $statusUpdate
     * @return \Vendor\CustomOrderProcessing\Api\Data\OrderStatusUpdateResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function updateOrderStatusByRequestInterface(OrderStatusUpdateInterface $statusUpdate);
}
