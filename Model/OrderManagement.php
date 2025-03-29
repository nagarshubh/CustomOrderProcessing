<?php
namespace Vendor\CustomOrderProcessing\Model;

use Magento\Sales\Api\OrderRepositoryInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory as StatusCollectionFactory;
use Magento\Framework\Exception\LocalizedException;

class OrderManagement
{
    /**
     * @var OrderRepositoryInterfaceFactory
     */
    protected OrderRepositoryInterfaceFactory $orderRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    /**
     * @var StatusCollectionFactory
     */
    protected StatusCollectionFactory $statusCollectionFactory;

    /**
     * Constructor
     *
     * @param OrderRepositoryInterfaceFactory $orderRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param StatusCollectionFactory $statusCollectionFactory
     */
    public function __construct(
        OrderRepositoryInterfaceFactory $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        StatusCollectionFactory $statusCollectionFactory
    ) {
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->statusCollectionFactory = $statusCollectionFactory;
    }
    
    /**
     * GetOrderByIncrementId function to load order by increment id
     *
     * @param string $incrementId
     * @return mixed
     */
    public function getOrderByIncrementId($incrementId)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('increment_id', $incrementId, 'eq')
            ->create();

        $orderList = $this->orderRepository->create()->getList($searchCriteria)->getItems();

        return count($orderList) ? reset($orderList) : false;
    }

    /**
     * IsValidStatusTransition function
     *
     * @param object $order
     * @param string $status
     * @return boolean
     */
    public function isValidStatusTransition($order, $status)
    {
        $allowedStatuses = $this->statusCollectionFactory->create()
            ->addStateFilter($order->getState())
            ->getColumnValues('status');
        $isAllowed = false;
        if (in_array($status, $allowedStatuses)) {
            $isAllowed = true;
        }
        return $isAllowed;
    }

    /**
     * SetNewStatus function
     *
     * @param object $order
     * @return void
     */
    public function setNewStatus($order)
    {
        try {
            $this->orderRepository->create()->save($order);
        } catch (LocalizedException $e) {
            throw new LocalizedException(
                __('Some error in saving the order, please check exeption logs for more details.')
            );
        }
    }
}
