<?php
namespace Vendor\CustomOrderProcessing\Model\Data;

use Vendor\CustomOrderProcessing\Api\Data\OrderStatusUpdateLogInterface;
use Vendor\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog as OrderStatusLogResource;
use Magento\Framework\Model\AbstractModel;

class OrderStatusUpdateLog extends AbstractModel implements OrderStatusUpdateLogInterface
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(OrderStatusLogResource::class);
    }

    /**
     * @inheritdoc
     */
    public function getEntityId()
    {
        return $this->_get(self::ENTITY_ID);
    }

    /**
     * @inheritdoc
     */
    public function setEntityId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * @inheritdoc
     */
    public function getOrderIncrementId()
    {
        return $this->_get(self::ORDER_INCREMENT_ID);
    }

    /**
     * @inheritdoc
     */
    public function setOrderIncrementId($incrementId)
    {
        return $this->setData(self::ORDER_INCREMENT_ID, $incrementId);
    }

    /**
     * @inheritdoc
     */
    public function getOldStatus()
    {
        return $this->_get(self::OLD_STATUS);
    }

    /**
     * @inheritdoc
     */
    public function setOldStatus($status)
    {
        return $this->setData(self::OLD_STATUS, $status);
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

    /**
     * @inheritdoc
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * @inheritdoc
     */
    public function setCreatedAt($date)
    {
        return $this->setData(self::CREATED_AT, $date);
    }
}
