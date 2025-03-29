<?php
namespace Vendor\CustomOrderProcessing\Api\Data;

/**
 * Interface for order status update response
 */
interface OrderStatusUpdateResultInterface
{
    /**
     * Constants for keys of data array
     */
    public const SUCCESS = 'success';
    public const MESSAGE = 'message';
    public const ORDER_ID = 'order_id';
    public const INCREMENT_ID = 'increment_id';
    public const PREVIOUS_STATUS = 'previous_status';
    public const NEW_STATUS = 'new_status';

    /**
     * Get Success Flag
     *
     * @return bool
     */
    public function getSuccess();

    /**
     * Set Success Flag
     *
     * @param bool $success
     * @return $this
     */
    public function setSuccess($success);

    /**
     * Get Message
     *
     * @return string
     */
    public function getMessage();

    /**
     * Set Message
     *
     * @param string $message
     * @return $this
     */
    public function setMessage($message);

    /**
     * Get Order ID
     *
     * @return int
     */
    public function getOrderId();

    /**
     * Set Order ID
     *
     * @param int $orderId
     * @return $this
     */
    public function setOrderId($orderId);

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
     * Get Previous Status
     *
     * @return string
     */
    public function getPreviousStatus();

    /**
     * Set Previous Status
     *
     * @param string $status
     * @return $this
     */
    public function setPreviousStatus($status);

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
}
