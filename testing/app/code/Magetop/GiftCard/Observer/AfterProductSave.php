<?php

namespace Magetop\GiftCard\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Api\Data\ProductCustomOptionInterfaceFactory;

class AfterProductSave implements ObserverInterface
{
    /** @var \Magento\Framework\Logger\Monolog */
    protected $_logger;

    /** @var Magento\Framework\App\RequestInterface */
    protected $_request;

    /**
     * @var ProductCustomOptionInterfaceFactory
     */
    private ProductCustomOptionInterfaceFactory $productCustomOptionInterfaceFactory;

    /**
     * @param \Psr\Log\LoggerInterface $loggerInterface
     * @param RequestInterface $requestInterface
     * @param ProductCustomOptionInterfaceFactory $productCustomOptionInterfaceFactory
     */
    public function __construct(
        \Psr\Log\LoggerInterface $loggerInterface,
        RequestInterface $requestInterface,
        ProductCustomOptionInterfaceFactory $productCustomOptionInterfaceFactory
    ) {
        $this->_logger = $loggerInterface;
        $this->_request = $requestInterface;
        $this->productCustomOptionInterfaceFactory = $productCustomOptionInterfaceFactory;
    }

    /**
     * This is the method that fires when the event runs.
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();
        if ($product->getTypeId() == 'giftcard') {
            if (!$product->getHasOptions() && (int)$product->getHasOptions()==0) {
                $emailCustomOption = $this->productCustomOptionInterfaceFactory->create();
                $messageCustomOption = $this->productCustomOptionInterfaceFactory->create();
                $emailCustomOption->setTitle('Email To')
                             ->setType('field')
                             ->setIsRequire(true)
                             ->setSortOrder(0)
                             ->setPrice(0)
                             ->setPriceType('percent')
                             ->setIsDelete(0)
                             ->setProductSku($product->getSku());
                $customOptions[] = $emailCustomOption;
                $messageCustomOption->setTitle('Message')
                             ->setType('area')
                             ->setIsRequire(true)
                             ->setSortOrder(0)
                             ->setPrice(0)
                             ->setPriceType('percent')
                             ->setMaxCharacters(160)
                             ->setIsDelete(0)
                             ->setProductSku($product->getSku());
                $customOptions[] = $messageCustomOption;
                $product->setOptions($customOptions)->save();
            }
        }
    }
}
