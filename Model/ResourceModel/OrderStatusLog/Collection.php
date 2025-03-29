<?php
namespace Vendor\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Vendor\CustomOrderProcessing\Model\OrderStatusLog;
use Vendor\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog as OrderStatusLogResource;

/**
 * OrderStatusLog Collection class
 */
class Collection extends AbstractCollection
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(OrderStatusLog::class, OrderStatusLogResource::class);
    }
}
