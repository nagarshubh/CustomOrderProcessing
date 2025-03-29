<?php
namespace Vendor\CustomOrderProcessing\Model\Data;

use Vendor\CustomOrderProcessing\Api\Data\OrderStatusUpdateResultInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class OrderStatusUpdateResult extends AbstractSimpleObject implements OrderStatusUpdateResultInterface
{
    /**
     * @inheritdoc
     */
    public function getSuccess()
    {
        return $this->_get(self::SUCCESS);
    }

    /**
     * @inheritdoc
     */
    public function setSuccess($success)
    {
        return $this->setData(self::SUCCESS, $success);
    }

    /**
     * @inheritdoc
     */
    public function getMessage()
    {
        return $this->_get(self::MESSAGE);
    }

    /**
     * @inheritdoc
     */
    public function setMessage($message)
    {
        return $this->setData(self::MESSAGE, $message);
    }

    /**
     * @inheritdoc
     */
    public function getOrderId()
    {
        return $this->_get(self::ORDER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setOrderId($orderId)
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * @inheritdoc
     */
    public function getIncrementId()
    {
        return $this->_get(self::INCREMENT_ID);
    }

    /**
     * @inheritdoc
     */
    public function setIncrementId($incrementId)
    {
        return $this->setData(self::INCREMENT_ID, $incrementId);
    }

    /**
     * @inheritdoc
     */
    public function getPreviousStatus()
    {
        return $this->_get(self::PREVIOUS_STATUS);
    }

    /**
     * @inheritdoc
     */
    public function setPreviousStatus($status)
    {
        return $this->setData(self::PREVIOUS_STATUS, $status);
    }

    /**
     * @inheritdoc
     */
    public function getNewStatus()
    {
        return $this->_get(self::NEW_STATUS);
    }

    /**
     * @inheritdoc
     */
    public function setNewStatus($status)
    {
        return $this->setData(self::NEW_STATUS, $status);
    }
}
