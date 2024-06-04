<?php

namespace LuxuryUnlimited\SmileElasticSuite\Controller\Brand;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;

/**
 * Magetop GiftCard ClearDiscount Controller.
 * @SuppressWarnings(PHPMD.AllPurposeAction)
 */
class BrandName extends Action
{
    /**
     * @var ProductFactory
     */
    protected $productFactory;
    
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    protected $resultJsonFactory;

    /**
     * *
     *
     * @param Context $context
     * @param CollectionFactory $productFactory
     * @param RequestInterface $request
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        CollectionFactory $productFactory,
        RequestInterface $request,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->productFactory = $productFactory;
        $this->request = $request;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    /**
     * *
     *
     * @return \Magento\Framework\App\RequestInterface
     */
    public function execute()
    {
        $id = $this->request->getParam('productId');
        $product = $this->productFactory->create()
        ->addFieldToFilter('entity_id', $id)
        ->addAttributeToSelect('*')
        ->getFirstItem();
        $brand = $product->getResource()->getAttribute('brands')
        ->getFrontend()->getValue($product);
        // echo $brand;
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData(['brand'=>$brand]);
    }
}
