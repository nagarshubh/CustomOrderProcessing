<?php
namespace Vendor\CustomOrderProcessing\Model;

use Magento\Framework\Model\AbstractModel;
use Vendor\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog as OrderStatusLogResource;

/**
 * OrderStatusLog Model class
 */
class OrderStatusLog extends AbstractModel
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
}
