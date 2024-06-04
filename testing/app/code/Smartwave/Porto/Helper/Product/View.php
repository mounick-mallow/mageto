<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Smartwave\Porto\Helper\Product;

use Magento\Catalog\Helper\Product;
use Magento\Catalog\Helper\Product\View as ProductView;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Design;
use Magento\Catalog\Model\Product as ProductModel;
use Magento\Catalog\Model\Session;
use Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page as ResultPage;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Catalog category helper View
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class View extends ProductView
{
    /**
     * @var array
     */
    protected $messageGroups;

    /**
     * @var Registry
     */
    protected $_coreRegistry = null;

    /**
     * Catalog product
     *
     * @var Product
     */
    protected $_catalogProduct = null;

    /**
     * Catalog design
     *
     * @var Design
     */
    protected $_catalogDesign;

    /**
     * Catalog session
     *
     * @var Session
     */
    protected $_catalogSession;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var CategoryUrlPathGenerator
     */
    protected $categoryUrlPathGenerator;

    /**
     * @var ScopeConfigInterface
     */
	protected ScopeConfigInterface $_scopeConfig;

    /**
     * @var StoreManagerInterface
     */
	protected StoreManagerInterface $_storeManager;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Session $catalogSession
     * @param Design $catalogDesign
     * @param Product $catalogProduct
     * @param Registry $coreRegistry
     * @param ManagerInterface $messageManager
     * @param CategoryUrlPathGenerator $categoryUrlPathGenerator
     * @param StoreManagerInterface $storeManager
     * @param array $messageGroups
     */
    public function __construct(
        Context $context,
        Session $catalogSession,
        Design $catalogDesign,
        Product $catalogProduct,
        Registry $coreRegistry,
        ManagerInterface $messageManager,
        CategoryUrlPathGenerator $categoryUrlPathGenerator,
		StoreManagerInterface $storeManager,
        array $messageGroups = []
    ) {
        $this->_catalogSession = $catalogSession;
        $this->_catalogDesign = $catalogDesign;
        $this->_catalogProduct = $catalogProduct;
        $this->_coreRegistry = $coreRegistry;
        $this->messageGroups = $messageGroups;
        $this->messageManager = $messageManager;
        $this->categoryUrlPathGenerator = $categoryUrlPathGenerator;
		$this->_scopeConfig = $context->getScopeConfig();
		$this->_storeManager = $storeManager;
        parent::__construct(
            $context,
            $catalogSession,
            $catalogDesign,
            $catalogProduct,
            $coreRegistry,
            $messageManager,
            $categoryUrlPathGenerator
        );
    }

    /**
     * Init layout for viewing product page
     *
     * @param ResultPage $resultPage
     * @param $product
     * @param $params
     * @return $this|View
     * @throws NoSuchEntityException
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function initProductLayout(
        ResultPage $resultPage,
        $product,
        $params = null
    ): View|static {
        $settings = $this->_catalogDesign->getDesignSettings($product);
        $pageConfig = $resultPage->getConfig();

        if ($settings->getData('custom_design')) {
            $this->_catalogDesign->applyCustomDesign($settings->getData('custom_design'));
        }

        // Apply custom page layout
		if ($settings->getData('page_layout')) {
			$pageConfig->setPageLayout($settings->getData('page_layout'));
		}else{
			$panelLayout = $this->_scopeConfig->getValue(
                'porto_settings/product/page_layout',
                ScopeInterface::SCOPE_STORE,
                $this->_storeManager->getStore()->getId()
            );
			if($panelLayout!=''){
				$pageConfig->setPageLayout($panelLayout);
			}
		}

        $urlSafeSku = rawurlencode($product->getSku());

        if ($params && $params->getBeforeHandles()) {
            foreach ($params->getBeforeHandles() as $handle) {
                $resultPage->addPageLayoutHandles(
                    ['id' => $product->getId(), 'sku' => $urlSafeSku, 'type' => $product->getTypeId()],
                    $handle
                );
            }
        }

        $resultPage->addPageLayoutHandles(
            ['id' => $product->getId(), 'sku' => $urlSafeSku, 'type' => $product->getTypeId()]
        );

        if ($params && $params->getAfterHandles()) {
            foreach ($params->getAfterHandles() as $handle) {
                $resultPage->addPageLayoutHandles(
                    ['id' => $product->getId(), 'sku' => $urlSafeSku, 'type' => $product->getTypeId()],
                    $handle
                );
            }
        }

        $update = $resultPage->getLayout()->getUpdate();
        $layoutUpdates = $settings->getLayoutUpdates();
        if ($layoutUpdates) {
            if (is_array($layoutUpdates)) {
                foreach ($layoutUpdates as $layoutUpdate) {
                    $update->addUpdate($layoutUpdate);
                }
            }
        }

        $currentCategory = $this->_coreRegistry->registry('current_category');
        $controllerClass = $this->_request->getFullActionName();
        if ($controllerClass != 'catalog-product-view') {
            $pageConfig->addBodyClass('catalog-product-view');
        }

        $full_width = $this->_scopeConfig->getValue('porto_settings/general/layout', ScopeInterface::SCOPE_STORE, $this->_storeManager->getStore()->getId());
        $product_page_type = $product->getData('product_page_type');
        if(!$product_page_type)
            $product_page_type = $this->_scopeConfig->getValue('porto_settings/product/product_page_type', ScopeInterface::SCOPE_STORE, $this->_storeManager->getStore()->getId());
        $additional_class = '';
        if(isset($full_width) && $full_width == 'full_width')
            $additional_class = 'layout-fullwidth';
        if(isset($product_page_type) && $product_page_type)
            $pageConfig->addBodyClass('product-type-'.$product_page_type);
        $pageConfig->addBodyClass('product-' . $product->getUrlKey())
            ->addBodyClass($additional_class);
        if ($currentCategory instanceof Category) {
            $pageConfig->addBodyClass('categorypath-' . $this->categoryUrlPathGenerator->getUrlPath($currentCategory))
                ->addBodyClass('category-' . $currentCategory->getUrlKey());
        }

        return $this;
    }
}
