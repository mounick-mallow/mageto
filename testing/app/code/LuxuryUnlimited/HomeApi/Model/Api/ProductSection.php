<?php
/**
 * LuxuryUnlimited_HomeApi
 *
 * @copyright   Copyright (c) 2023
 */
namespace LuxuryUnlimited\HomeApi\Model\Api;

use Psr\Log\LoggerInterface;

class ProductSection
{
    protected $logger;

    protected $productCollectionFactory;

    private $storeManager;

    protected $helper;

    protected $productStatus;

    protected $mostHelper;

    protected $salesHelper;


    public function __construct(
        LoggerInterface $logger,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \LuxuryUnlimited\HomeProductSection\Helper\Data $helper,
        \LuxuryUnlimited\MostPopular\Helper\Data $mostHelper,
        \LuxuryUnlimited\SaleProducts\Helper\Data $salesHelper,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus
    ){
        $this->logger = $logger;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->storeManager = $storeManager;
        $this->productStatus = $productStatus;
        $this->mostHelper = $mostHelper;
        $this->salesHelper = $salesHelper;
        $this->helper = $helper;
    }

    /**
     * Get Product Section
     * @param string $section
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductSection($section)
    {

        $response = ['success' => false,"message"=>"store"];
        try {
            $sectionEnabled = false;
            $categoryId = '';
            $limit = 4;
            if($section == 1 ){
                if(!$this->helper->isEnabledSectionOne()){
                    $response = ['success' => false, 'message' => "first section is not enabled"];
                    return $response;
                }else {
                    $limit = $this->helper->getSectionOneProductCount();
                    $categoryId = $this->helper->getSectionOneCategory();
                    $sectionEnabled = true;
                }
            }elseif($section == 2 ){
                if(!$this->helper->isEnabledSectionTwo()){
                    $response = ['success' => false, 'message' => "second section is not enabled"];
                    return $response;
                }else {
                    $limit = $this->helper->getSectionTwoProductCount();
                    $categoryId = $this->helper->getSectionTwoCategory();
                    $sectionEnabled = true;
                }
            }elseif($section == 3 ){
                if(!$this->helper->isEnabledSectionThree()){
                    $response = ['success' => false, 'message' => "third section is not enabled"];
                    return $response;
                }else {
                    $limit = $this->helper->getSectionThreeProductCount();
                    $categoryId = $this->helper->getSectionThreeCategory();
                    $sectionEnabled = true;
                }
            }
            if( $sectionEnabled && $categoryId!='') {
                $category =  $this->helper->getCategoryById($categoryId);
                if(!is_numeric($limit)){
                    $limit = 4;
                }
                $productCollection = $this->helper->getProductCollectionByCategories($category, $limit);
                $responseData = [];
                foreach ($productCollection as $product) {
                    $responseData[] = $product->getData();
                }
                $response = ['success' => true, 'message' => $responseData];
            }else{
                $response = ['success' => false, 'message' => "invalid section type or category not selected in config"];
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
            $this->logger->info($e->getMessage());
        }
        //$returnArray = json_encode($response);
        return $response;
    }

    /**
     * Get Promo Products
     * @param string $promoType
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getPromoProducts($promoType)
    {

        $response = ['success' => false,"message"=>"store"];
        try {
            $sectionEnabled = false;
            $categoryId = '';
            $productCollection =[];
            if($promoType == "most" ){
                if(!$this->mostHelper->isEnabledMostPopular()){
                    $response = ['success' => false, 'message' => "Most popular is not enabled"];
                    return $response;
                }else {
                    $limit = $this->mostHelper->getMostPopularProductCount();
                    $categoryId = $this->mostHelper->getMostPopularCategory();
                    $category = $this->mostHelper->getCategoryById($categoryId);
                    if(!is_numeric($limit)){
                        $limit = 4;
                    }
                    $productCollection = $this->mostHelper->getProductCollectionByCategories($category, $limit);
                    $sectionEnabled = true;
                }
            }elseif($promoType == "sales" ){
                if(!$this->salesHelper->isEnabledSaleProducts()){
                    $response = ['success' => false, 'message' => "Sales Products is not enabled"];
                    return $response;
                }else {
                    $limit = $this->salesHelper->getSaleProductsProductCount();
                    $categoryId = $this->salesHelper->getSaleProductsCategory();
                    $category = $this->salesHelper->getCategoryById($categoryId);
                    if(!is_numeric($limit)){
                        $limit = 4;
                    }
                    $productCollection = $this->salesHelper->getProductCollectionByCategories($category, $limit);
                    $sectionEnabled = true;
                }
            }

	    if( $sectionEnabled && $categoryId!='') {
		$responseData =[];    
                foreach ($productCollection as $product) {
                    $responseData[] = $product->getData();
                }
                $response = ['success' => true, 'message' => $responseData];
            }else{
                $response = ['success' => false, 'message' => "invalid promo type or category not selected in config"];
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
            $this->logger->info($e->getMessage());
        }
        //$returnArray = json_encode($response);
        return $response;
    }


}
