<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Smartwave\Porto\Controller\Category;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Controller\Category\View as CategoryView;
use Magento\Catalog\Model\Design;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Session;
use Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class controller View
 */
class View extends CategoryView
{
    private const PAGE_LAYOUT = 'porto_settings/category/page_layout';
    private const LAYOUT = 'porto_settings/general/layout';

    /**
     * @var Registry
     */
    protected Registry $coreRegistry;

    /**
     * @var Session
     */
    protected Session $catalogSession;

    /**
     * @var Design
     */
    protected Design $catalogDesign;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @var CategoryUrlPathGenerator
     */
    protected $categoryUrlPathGenerator;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var Resolver
     */
    protected Resolver $layerResolver;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $_scopeConfig;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Design $catalogDesign
     * @param Session $catalogSession
     * @param Registry $coreRegistry
     * @param StoreManagerInterface $storeManager
     * @param CategoryUrlPathGenerator $categoryUrlPathGenerator
     * @param PageFactory $resultPageFactory
     * @param ForwardFactory $resultForwardFactory
     * @param Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ScopeConfigInterface $scopeConfig
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        Design $catalogDesign,
        Session $catalogSession,
        Registry $coreRegistry,
        StoreManagerInterface $storeManager,
        CategoryUrlPathGenerator $categoryUrlPathGenerator,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct(
            $context,
            $catalogDesign,
            $catalogSession,
            $coreRegistry,
            $storeManager,
            $categoryUrlPathGenerator,
            $resultPageFactory,
            $resultForwardFactory,
            $layerResolver,
            $categoryRepository
        );
        $this->storeManager = $storeManager;
        $this->catalogDesign = $catalogDesign;
        $this->catalogSession = $catalogSession;
        $this->coreRegistry = $coreRegistry;
        $this->categoryUrlPathGenerator = $categoryUrlPathGenerator;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->layerResolver = $layerResolver;
        $this->categoryRepository = $categoryRepository;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Category view action
     *
     * @return ResultInterface
     * @throws NoSuchEntityException
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     */
    public function execute()
    {
        if ($this->_request->getParam(ActionInterface::PARAM_NAME_URL_ENCODED)) {
            return $this->resultRedirectFactory->create()->setUrl(
                $this->_redirect->getRedirectUrl()
            );
        }
        $category = $this->_initCategory();
        if ($category) {
            $this->layerResolver->create(Resolver::CATALOG_LAYER_CATEGORY);
            $settings = $this->catalogDesign->getDesignSettings($category);

            if ($settings->getData('custom_design')) {
                $this->catalogDesign->applyCustomDesign(
                    $settings->getData('custom_design')
                );
            }

            $this->catalogSession->setData(
                'last_viewed_category_id', $category->getId()
            );

            $page = $this->resultPageFactory->create();

            if ($settings->getData('page_layout')) {
                $page->getConfig()->setPageLayout($settings->getData('page_layout'));
            } else {
                $panelLayout = $this->scopeConfig->getValue(
                    self::PAGE_LAYOUT,
                    ScopeInterface::SCOPE_STORE,
                    $this->storeManager->getStore()->getId()
                );
                if ($panelLayout!='') {
                    $page->getConfig()->setPageLayout($panelLayout);
                }
            }

            $hasChildren = $category->hasChildren();
            if ($category->getIsAnchor()) {
                $type = $hasChildren ? 'layered' : 'layered_without_children';
            } else {
                $type = $hasChildren ? 'default' : 'default_without_children';
            }

            if (!$hasChildren) {
                $parentType = strtok($type, '_');
                $page->addPageLayoutHandles(['type' => $parentType]);
            }
            $page->addPageLayoutHandles(
                ['type' => $type, 'id' => $category->getId()]
            );

            $layoutUpdates = $settings->getData('layout_updates');
            if ($layoutUpdates && is_array($layoutUpdates)) {
                foreach ($layoutUpdates as $layoutUpdate) {
                    $page->addUpdate($layoutUpdate);
                    // phpcs:disable
                    $page->addPageLayoutHandles(['layout_update' => md5($layoutUpdate)]);
                    // phpcs:enable
                }
            }
            $full_width = $this->scopeConfig->getValue(
                self::LAYOUT,
                ScopeInterface::SCOPE_STORE,
                $this->storeManager->getStore()->getId()
            );
            $additional_class = '';
            if (isset($full_width) && $full_width == 'full_width') {
                $additional_class = 'layout-fullwidth';
            }
            $page->getConfig()->addBodyClass('page-products')
                ->addBodyClass(
                    'categorypath-' .
                    $this->categoryUrlPathGenerator->getUrlPath($category)
                )
                ->addBodyClass('category-' . $category->getUrlKey())
                ->addBodyClass($additional_class);

            return $page;
        } elseif (!$this->getResponse()->isRedirect()) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
        return $this->resultForwardFactory->create()->forward('noroute');
    }
}
