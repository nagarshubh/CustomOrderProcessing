<?php
namespace Vendor\CustomOrderProcessing\Api\Data;

/**
 * Interface for order status update Log
 */
interface OrderStatusUpdateLogInterface
{
    public const ENTITY_ID = 'entity_id';
    public const ORDER_INCREMENT_ID = 'order_increment_id';
    public const OLD_STATUS = 'old_status';
    public const NEW_STATUS = 'new_status';
    public const CREATED_AT = 'created_at';

    /**
     * Get ID
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setEntityId($id);

    /**
     * Get Order Increment ID
     *
     * @return string
     */
    public function getOrderIncrementId();

    /**
     * Set Order Increment ID
     *
     * @param string $incrementId
     * @return $this
     */
    public function setOrderIncrementId($incrementId);

    /**
     * Get Old Status
     *
     * @return string
     */
    public function getOldStatus();

    /**
     * Set Old Status
     *
     * @param string $status
     * @return $this
     */
    public function setOldStatus($status);

    /**
     * Get New Status
     *
     * @return string
     */
    public function getNewStatus();

    /**
     * Set New Status
     *
     * @param string $status
     * @return $this
     */
    public function setNewStatus($status);

    /**
     * Get  Created At
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set Created At
     *
     * @param string $date
     * @return $this
     */
    public function setCreatedAt($date);
}
