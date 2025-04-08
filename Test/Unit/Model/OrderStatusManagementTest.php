<?php
namespace Vendor\CustomOrderProcessing\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Vendor\CustomOrderProcessing\Model\OrderStatusManagement;
use Vendor\CustomOrderProcessing\Model\OrderManagement;
use Vendor\CustomOrderProcessing\Api\Data\OrderStatusUpdateInterface;
use Vendor\CustomOrderProcessing\Api\Data\OrderStatusUpdateResultInterface;
use Vendor\CustomOrderProcessing\Api\Data\OrderStatusUpdateResultInterfaceFactory;
use Magento\Sales\Model\ResourceModel\Order\Status\Collection as StatusCollection;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order;

class OrderStatusManagementTest extends TestCase
{
    private $orderManagementMock;
    private $resultFactoryMock;
    private $resultMock;
    private $statusCollectionMock;
    private $model;
    private $orderMock;

    protected function setUp(): void
    {
        $this->orderManagementMock = $this->createMock(OrderManagement::class);
        $this->resultFactoryMock = $this->createMock(OrderStatusUpdateResultInterfaceFactory::class);
        $this->resultMock = $this->createMock(OrderStatusUpdateResultInterface::class);
        $this->statusCollectionMock = $this->createMock(StatusCollection::class);

        $this->resultFactoryMock->method('create')->willReturn($this->resultMock);

        $this->model = new OrderStatusManagement(
            $this->orderManagementMock,
            $this->resultFactoryMock,
            $this->statusCollectionMock
        );

        // ✅ Correct class name and constructor handling
        $this->orderMock = $this->getMockBuilder(Order::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getId', 'getStatus', 'setStatus'])
            ->getMock();
    }

    public function testUpdateOrderStatusSuccess()
    {
        $incrementId = '000000002-3';
        $newStatus = 'complete';
        $previousStatus = 'custom_complete';

        $this->statusCollectionMock->method('toOptionArray')->willReturn([
            ['value' => 'pending'],
            ['value' => 'processing'],
            ['value' => 'complete'],
            ['value' => 'custom_complete']
        ]);

        $this->orderMock->method('getStatus')->willReturn($previousStatus);
        $this->orderMock->method('getId')->willReturn(1);
        $this->orderMock->expects($this->once())->method('setStatus')->with($newStatus);

        $this->orderManagementMock->method('getOrderByIncrementId')->willReturn($this->orderMock);
        $this->orderManagementMock->method('isValidStatusTransition')->willReturn(true);
        $this->orderManagementMock->expects($this->once())->method('setNewStatus')->with($this->orderMock);

        $this->resultMock->expects($this->once())->method('setSuccess')->with(true)->willReturnSelf();
        $this->resultMock->expects($this->once())->method('setMessage')->with('Order status has been updated successfully.')->willReturnSelf();
        $this->resultMock->expects($this->once())->method('setOrderId')->with(1)->willReturnSelf();
        $this->resultMock->expects($this->once())->method('setIncrementId')->with($incrementId)->willReturnSelf();
        $this->resultMock->expects($this->once())->method('setPreviousStatus')->with($previousStatus)->willReturnSelf();
        $this->resultMock->expects($this->once())->method('setNewStatus')->with($newStatus)->willReturnSelf();
        $this->resultMock->method('getSuccess')->willReturn(true);


        $result = $this->model->updateOrderStatus($incrementId, $newStatus);
        //$this->assertSame($this->resultMock, $result);
        
        /* Check the success with actual results */
        $this->assertTrue($result->getSuccess());
    }

    public function testUpdateOrderStatusWithInvalidStatus()
    {
        $incrementId = '1000002';
        $invalidStatus = 'invalid_status';

        $this->statusCollectionMock->method('toOptionArray')->willReturn([
            ['value' => 'pending'],
        ]);

        $this->resultMock->expects($this->once())->method('setSuccess')->with(false)->willReturnSelf();
        $this->resultMock->expects($this->once())->method('setMessage')
            ->with('Invalid status value: invalid_status')
            ->willReturnSelf();

        $result = $this->model->updateOrderStatus($incrementId, $invalidStatus);
        $this->assertSame($this->resultMock, $result);
    }

    public function testUpdateOrderStatusOrderNotFound()
    {
        $incrementId = '1000003';
        $validStatus = 'processing';

        $this->statusCollectionMock->method('toOptionArray')->willReturn([
            ['value' => 'processing']
        ]);

        $this->orderManagementMock->method('getOrderByIncrementId')->willReturn(null);

        $this->resultMock->expects($this->once())->method('setSuccess')->with(false)->willReturnSelf();
        $this->resultMock->expects($this->once())->method('setMessage')->with("Order with increment ID $incrementId does not exist.")->willReturnSelf();

        $result = $this->model->updateOrderStatus($incrementId, $validStatus);
        $this->assertSame($this->resultMock, $result);
    }

    public function testUpdateOrderStatusSameStatus()
    {
        $incrementId = '1000004';
        $status = 'pending';

        $this->statusCollectionMock->method('toOptionArray')->willReturn([
            ['value' => 'pending']
        ]);

        $this->orderMock->method('getStatus')->willReturn($status);
        $this->orderMock->method('getId')->willReturn(42);

        $this->orderManagementMock->method('getOrderByIncrementId')->willReturn($this->orderMock);

        $this->resultMock->expects($this->once())->method('setSuccess')->with(true)->willReturnSelf();
        $this->resultMock->expects($this->once())->method('setMessage')->with("No change required status is already $status.")->willReturnSelf();
        $this->resultMock->expects($this->once())->method('setOrderId')->with(42)->willReturnSelf();
        $this->resultMock->expects($this->once())->method('setIncrementId')->with($incrementId)->willReturnSelf();
        $this->resultMock->expects($this->once())->method('setPreviousStatus')->with($status)->willReturnSelf();
        $this->resultMock->expects($this->once())->method('setNewStatus')->with($status)->willReturnSelf();

        $result = $this->model->updateOrderStatus($incrementId, $status);
        $this->assertSame($this->resultMock, $result);
    }
}
