<?php
namespace Vendor\CustomOrderProcessing\Model\Data;

use Vendor\CustomOrderProcessing\Api\Data\OrderStatusUpdateInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class OrderStatusUpdate extends AbstractSimpleObject implements OrderStatusUpdateInterface
{
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
    public function getStatus()
    {
        return $this->_get(self::STATUS);
    }

    /**
     * @inheritdoc
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
}
