<?php

namespace Magetop\GiftCard\Block;

use Magento\Store\Model\Store;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * GiftCatrd block.
 *
 * @author      Magetop Software
 */
class ProductRefund extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Session
     */
    protected $_customerSession;

    /**
     * Magento\Framework\Registry.
     *
     * @var [type]
     */
    protected $_registry;

    /**
     * Customer session
     *
     * @var \Magento\Framework\App\Http\Context
     */
    protected $_httpContext;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private \Magento\Framework\ObjectManagerInterface $_objectManager;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    private \Magento\Catalog\Model\ProductFactory $_product;

    /**
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry                      $_registry
     * @param \Magento\Framework\ObjectManagerInterface        $objectManager
     * @param \Magento\Framework\App\Http\Context              $httpContext
     * @param \Magento\Catalog\Model\ProductFactory            $product
     * @param DateTime                                         $date
     * @param Store                                            $store
     * @param array                                            $data
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $_registry,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Catalog\Model\ProductFactory $product,
        DateTime $date,
        Store $store,
        array $data = []
    ) {
        $this->_registry = $_registry;
        $this->_objectManager = $objectManager;
        $this->_session = $context->getStoreManager();
        $this->_product = $product;
        parent::__construct($context, $data);
        $this->_httpContext = $httpContext;
    }

    /**
     * Get Product
     *
     * @return Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        $id = $this->getRequest()->getParam('id');
        return $this->_product->create()->load($id);
    }
}
