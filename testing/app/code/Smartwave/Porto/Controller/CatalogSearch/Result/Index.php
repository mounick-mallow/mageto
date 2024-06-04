<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Smartwave\Porto\Controller\CatalogSearch\Result;

use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Session;
use Magento\CatalogSearch\Controller\Result\Index as ResultIndex;
use Magento\CatalogSearch\Helper\Data;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\Page;
use Magento\Search\Model\Query;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Search\Model\QueryFactory;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class controller Index
 */
class Index extends ResultIndex
{
    /**
     * @var Session
     */
    protected Session $catalogSession;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @var QueryFactory
     */
    private QueryFactory $queryFactory;

    /**
     * @var Resolver
     */
    private Resolver $layerResolver;

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @var Data
     */
    private Data $helperData;

    /**
     * Construct
     *
     * @param Context $context
     * @param Session $catalogSession
     * @param StoreManagerInterface $storeManager
     * @param QueryFactory $queryFactory
     * @param Resolver $layerResolver
     * @param ScopeConfigInterface $scopeConfig
     * @param Data $helperData
     */
    public function __construct(
        Context $context,
        Session $catalogSession,
        StoreManagerInterface $storeManager,
        QueryFactory $queryFactory,
        Resolver $layerResolver,
        ScopeConfigInterface $scopeConfig,
        Data $helperData
    ) {
        $this->catalogSession = $catalogSession;
        $this->storeManager = $storeManager;
        $this->queryFactory = $queryFactory;
        $this->layerResolver = $layerResolver;
        parent::__construct(
            $context,
            $catalogSession,
            $storeManager,
            $queryFactory,
            $layerResolver
        );
        $this->resultFactory = $context->getResultFactory();
        $this->scopeConfig = $scopeConfig;
        $this->helperData = $helperData;
    }

    /**
     * Display search result
     *
     * @return ResultInterface|Page|(Page&ResultInterface)|void
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $this->layerResolver->create(Resolver::CATALOG_LAYER_SEARCH);
        /* @var $query Query */
        $query = $this->queryFactory->get();

        $query->setStoreId($this->storeManager->getStore()->getId());

        if ($query->getQueryText() != '') {
            if ($this->helperData->isMinQueryLength()) {
                $query->setId(0)->setIsActive(1)->setIsProcessed(1);
            } else {
                $query->saveIncrementalPopularity();

                if ($query->getRedirect()) {
                    $this->getResponse()->setRedirect($query->getRedirect());
                    return;
                }
            }
            $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            $this->helperData->checkNotes();

            $full_width = $this->scopeConfig->getValue(
                'porto_settings/general/layout',
                ScopeInterface::SCOPE_STORE,
                $this->storeManager->getStore()->getId()
            );
            $additional_class = '';
            if (isset($full_width) && $full_width == 'full_width') {
                $additional_class = 'layout-fullwidth';
            }
            $resultPage->getConfig()->addBodyClass($additional_class);
            $panelLayout = $this->scopeConfig->getValue(
                'porto_settings/category/page_layout',
                ScopeInterface::SCOPE_STORE,
                $this->storeManager->getStore()->getId()
            );
            if ($panelLayout!='') {
                $resultPage->getConfig()->setPageLayout($panelLayout);
                return $resultPage;
            } else {
                $this->_view->loadLayout();
                $this->_view->renderLayout();
            }
        } else {
            $this->getResponse()->setRedirect($this->_redirect->getRedirectUrl());
        }
    }
}
