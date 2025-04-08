<?php

namespace Vendor\CustomOrderProcessing\Model\Queue;

use Magento\Sales\Api\OrderRepositoryInterface;
use Vendor\CustomOrderProcessing\Logger\Logger as LoggerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;

class EmailSender
{
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * Constructor
     *
     * @param OrderRepositoryInterface $orderRepository
     * @param LoggerInterface $logger
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        LoggerInterface $logger,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation
    ) {
        $this->orderRepository = $orderRepository;
        $this->logger = $logger;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
    }

    /**
     * Process Queue
     *
     * @param string $orderId
     * @return void
     */
    public function process(string $orderId)
    {
        try {
            $order = $this->orderRepository->get($orderId);
            $this->logger->info('Sending email for Order: ' . $order->getIncrementId());
            $this->inlineTranslation->suspend();
            $store = $order->getStoreId();
            
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('custom_shipment_notification_email_template')
                ->setTemplateOptions([
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $store
                ])
                ->setTemplateVars(['order' => $order])
                ->setFrom([
                    'email' => 'vendor.support@example.com',
                    'name' => 'Vendor Support'
                ])
                ->addTo($order->getCustomerEmail())
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();

        } catch (\Exception $e) {
            $this->logger->error('Email Queue Error: ' . $e->getMessage());
        }
    }
}
