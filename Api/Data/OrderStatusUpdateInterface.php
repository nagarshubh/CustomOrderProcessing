<?php
namespace Vendor\CustomOrderProcessing\Api\Data;

/**
 * Interface for order status update request
 */
interface OrderStatusUpdateInterface
{
    public const INCREMENT_ID = 'increment_id';
    public const STATUS = 'status';

    /**
     * Get Order Increment ID
     *
     * @return string
     */
    public function getIncrementId();

    /**
     * Set Order Increment ID
     *
     * @param string $incrementId
     * @return $this
     */
    public function setIncrementId($incrementId);

    /**
     * Get Status
     *
     * @return string
     */
    public function getStatus();

    /**
     * Set Status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status);
}
