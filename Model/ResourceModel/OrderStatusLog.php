<?php
namespace Vendor\CustomOrderProcessing\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * OrderStatusLog ResourceModel class
 */
class OrderStatusLog extends AbstractDb
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('vendor_order_status_log', 'entity_id');
    }
}
