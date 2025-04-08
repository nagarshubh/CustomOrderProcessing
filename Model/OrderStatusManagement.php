<?php
namespace Vendor\CustomOrderProcessing\Model;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Vendor\CustomOrderProcessing\Model\OrderManagement;
use Vendor\CustomOrderProcessing\Api\OrderStatusManagementInterface;
use Vendor\CustomOrderProcessing\Api\Data\OrderStatusUpdateInterface;
use Vendor\CustomOrderProcessing\Api\Data\OrderStatusUpdateResultInterfaceFactory;
use Magento\Sales\Model\ResourceModel\Order\Status\Collection as StatusCollection;

class OrderStatusManagement implements OrderStatusManagementInterface
{
    /**
     * @var OrderManagement
     */
    protected $orderManagement;

    /**
     * @var OrderStatusUpdateResultInterfaceFactory
     */
    protected $resultFactory;

    /**
     * @var StatusCollection
     */
    protected $statusCollection;

    /**
     * @param OrderManagement $orderManagement
     * @param OrderStatusUpdateResultInterfaceFactory $resultFactory
     */
    public function __construct(
        OrderManagement $orderManagement,
        OrderStatusUpdateResultInterfaceFactory $resultFactory,
        StatusCollection $statusCollection
    ) {
        $this->orderManagement = $orderManagement;
        $this->resultFactory = $resultFactory;
        $this->statusCollection = $statusCollection;
    }

    /**
     * @inheritdoc
     */
    public function updateOrderStatus(string $increment_id, string $status)
    {
        $result = $this->resultFactory->create();
        
        try {

            if (empty($increment_id) || empty($status)) {
                throw new InputException(__('Increment ID and status are required.'));
            }

            $statusCollection = $this->statusCollection->toOptionArray();
            $allStatuses = array_column($statusCollection, 'value');
            
            if (!in_array($status, $allStatuses)) {
                throw new InputException(__("Invalid status value: {$status}"));
            }

            $order = $this->orderManagement->getOrderByIncrementId($increment_id);

            if (!$order) {
                throw new NoSuchEntityException(__('Order with increment ID %1 does not exist.', $increment_id));
            }

            $previousStatus = $order->getStatus();
            
            if ($previousStatus == $status) {
                $result->setSuccess(true)
                ->setMessage(__('No change required status is already %1.', $status))
                ->setOrderId($order->getId())
                ->setIncrementId($increment_id)
                ->setPreviousStatus($previousStatus)
                ->setNewStatus($status);
            } else {
                if (!$this->orderManagement->isValidStatusTransition($order, $status)) {
                    throw new StateException(
                        __('Status transition from %1 to %2 is not allowed.', $previousStatus, $status)
                    );
                }

                $order->setStatus($status);
                $this->orderManagement->setNewStatus($order);
                $result->setSuccess(true)
                    ->setMessage(__('Order status has been updated successfully.'))
                    ->setOrderId($order->getId())
                    ->setIncrementId($increment_id)
                    ->setPreviousStatus($previousStatus)
                    ->setNewStatus($status);
            }
        } catch (NoSuchEntityException $e) {
            $result->setSuccess(false)
                   ->setMessage($e->getMessage());
        } catch (StateException $e) {
            $result->setSuccess(false)
                   ->setMessage($e->getMessage());
        } catch (InputException $e) {
            $result->setSuccess(false)
                   ->setMessage($e->getMessage());
        } catch (LocalizedException $e) {
            $result->setSuccess(false)
                   ->setMessage($e->getMessage());
        } catch (\Exception $e) {
            $result->setSuccess(false)
                   ->setMessage(__('An error occurred while updating the order status.'));
        }
        
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function updateOrderStatusByRequestInterface(OrderStatusUpdateInterface $statusUpdate)
    {
        $result = $this->resultFactory->create();
        
        try {
            $incrementId = $statusUpdate->getIncrementId();
            $status = $statusUpdate->getStatus();
            
            if (empty($incrementId) || empty($status)) {
                throw new InputException(__('Increment ID and status are required.'));
            }

            $statusCollection = $this->statusCollection->toOptionArray();
            $allStatuses = array_column($statusCollection, 'value');
            
            if (!in_array($status, $allStatuses)) {
                throw new InputException(__("Invalid status value: {$status}"));
            }

            $order = $this->orderManagement->getOrderByIncrementId($incrementId);

            if (!$order) {
                throw new NoSuchEntityException(__('Order with increment ID %1 does not exist.', $incrementId));
            }

            $previousStatus = $order->getStatus();
            
            if ($previousStatus == $status) {
                $result->setSuccess(true)
                ->setMessage(__('No change required status is already %1.', $status))
                ->setOrderId($order->getId())
                ->setIncrementId($incrementId)
                ->setPreviousStatus($previousStatus)
                ->setNewStatus($status);
            } else {
                if (!$this->orderManagement->isValidStatusTransition($order, $status)) {
                    throw new StateException(
                        __('Status transition from %1 to %2 is not allowed.', $previousStatus, $status)
                    );
                }

                $order->setStatus($status);
                $this->orderManagement->setNewStatus($order);
                $result->setSuccess(true)
                    ->setMessage(__('Order status has been updated successfully.'))
                    ->setOrderId($order->getId())
                    ->setIncrementId($incrementId)
                    ->setPreviousStatus($previousStatus)
                    ->setNewStatus($status);
            }
                   
        } catch (NoSuchEntityException $e) {
            $result->setSuccess(false)
                   ->setMessage($e->getMessage());
        } catch (StateException $e) {
            $result->setSuccess(false)
                   ->setMessage($e->getMessage());
        } catch (InputException $e) {
            $result->setSuccess(false)
                   ->setMessage($e->getMessage());
        } catch (LocalizedException $e) {
            $result->setSuccess(false)
                   ->setMessage($e->getMessage());
        } catch (\Exception $e) {
            $result->setSuccess(false)
                   ->setMessage(__('An error occurred while updating the order status.'));
        }
        
        return $result;
    }
}
