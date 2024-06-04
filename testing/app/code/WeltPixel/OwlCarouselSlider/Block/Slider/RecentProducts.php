<?php
namespace WeltPixel\OwlCarouselSlider\Block\Slider;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Model\Product;
use Magento\Widget\Block\BlockInterface;

class RecentProducts extends AbstractProduct implements BlockInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \WeltPixel\OwlCarouselSlider\Helper\Custom
     */
    protected $_helperCustom;

    /**
     * @var \WeltPixel\OwlCarouselSlider\Helper\Products
     */
    protected $_helperProducts;

    /**
     * @var mixed
     */
    protected $_sliderConfiguration;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var \Magento\Reports\Block\Product\Widget\Viewed\Proxy
     */
    protected $_viewProductsBlock;

    /**
     * @var \Magento\Framework\Encryption\UrlCoder
     */
    protected $_urlCoder;

    public const COLLECTION_TYPE = 'recently_viewed';

    /**
     * RecentProducts constructor.
     *
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \WeltPixel\OwlCarouselSlider\Helper\Products $helperProducts
     * @param \WeltPixel\OwlCarouselSlider\Helper\Custom $helperCustom
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productsCollectionFactory
     * @param \Magento\Reports\Block\Product\Widget\Viewed\Proxy $viewedProductsBlock
     * @param \Magento\Framework\Encryption\UrlCoder $urlCoder
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \WeltPixel\OwlCarouselSlider\Helper\Products $helperProducts,
        \WeltPixel\OwlCarouselSlider\Helper\Custom $helperCustom,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productsCollectionFactory,
        \Magento\Reports\Block\Product\Widget\Viewed\Proxy $viewedProductsBlock, // @codingStandardsIgnoreLine
        \Magento\Framework\Encryption\UrlCoder $urlCoder,
        array $data = []
    ) {
        $this->_coreRegistry              = $context->getRegistry();
        $this->_helperProducts            = $helperProducts;
        $this->_helperCustom              = $helperCustom;
        $this->_productCollectionFactory  = $productsCollectionFactory;
        $this->_viewProductsBlock         = $viewedProductsBlock;
        $this->_urlCoder                  = $urlCoder;
        $this->setTemplate('recent/products.phtml');
        parent::__construct($context, $data);
    }

    /**
     * Retrieve the product collection based on product type.
     *
     * @return array|\Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollection()
    {
        $productCollection =  $this->_getRecentlyViewedCollection($this->_productCollectionFactory->create());
        return $productCollection;
    }

    /**
     * Retrieve the Slider settings.
     *
     * @return array
     */
    public function getSliderConfiguration()
    {
        if ($this->_sliderConfiguration === null) {
            $this->_sliderConfiguration = $this->_helperProducts->getSliderConfigOptions(self::COLLECTION_TYPE);
        }

        return $this->_sliderConfiguration;
    }

    /**
     * Retrieve the Slider Breakpoint settings.
     *
     * @return array
     */
    public function getBreakpointConfiguration()
    {
        return $this->_helperCustom->getBreakpointConfiguration();
    }

    /**
     * Get recently viewed slider products.
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $_collection
     * @return array|\Magento\Reports\Model\ResourceModel\Product\Index\Collection\AbstractCollection
     * @throws \Exception
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _getRecentlyViewedCollection($_collection)
    {
        $limit  = $this->_getProductLimit(self::COLLECTION_TYPE);
        $random = $this->_getRandomSort(self::COLLECTION_TYPE);

        if ($limit == 0) {
            return [];
        }

        $_itemsCollection = $this->_viewProductsBlock->getItemsCollection();

        if ($random) {
            $allIds = $_itemsCollection->getAllIds();
            $candidateIds = $_itemsCollection->getAllIds();
            $randomIds = [];
            $maxKey = count($candidateIds) - 1;
            while (count($randomIds) <= count($allIds) - 1) {
                $randomKey = random_int(0, $maxKey);
                $randomIds[$randomKey] = $candidateIds[$randomKey];
            }
            $_itemsCollection->addIdFilter($randomIds);
        }

        if ($limit > 0) {
            $_itemsCollection->setPageSize($limit);
        }

        return $_itemsCollection;
    }

    /**
     * Retrieve the products limit based on type.
     *
     * @param string $type
     * @return int
     */
    protected function _getProductLimit($type)
    {
        return $this->_helperProducts->getProductLimit($type);
    }

    /**
     * Retrieve the products random sort flag based on type.
     *
     * @param string $type
     * @return mixed
     */
    protected function _getRandomSort($type)
    {
        return $this->_helperProducts->getRandomSort($type);
    }

    /**
     * Retrieve the current store id.
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * IsHoverImageEnabled
     *
     * @return mixed
     */
    public function isHoverImageEnabled()
    {
        return $this->_helperCustom->isHoverImageEnabled();
    }

    /**
     * GetCustomAddToCartUrl
     *
     * @param Product $product
     * @param array $additional
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getCustomAddToCartUrl($product, $additional = [])
    {
        $referer = $this->_request->getServer('HTTP_REFERER');
        $uenc = $this->_urlCoder->encode($referer);
        $productId = $product->getEntityId();
        $params = [
            'uenc' => $uenc,
            'product' => $productId
        ];
        return $this->getUrl('checkout/cart/add', $params);
    }
}
