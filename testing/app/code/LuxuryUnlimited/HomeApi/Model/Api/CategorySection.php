<?php
/**
 * LuxuryUnlimited_HomeApi
 *
 * @copyright   Copyright (c) 2023
 */
namespace LuxuryUnlimited\HomeApi\Model\Api;

use Psr\Log\LoggerInterface;

class CategorySection
{
    protected $logger;

    protected $categoryCollectionFactory;

    private $storeManager;

    protected $helper;

    public function __construct(
        LoggerInterface $logger,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \LuxuryUnlimited\HomeCategorySection\Helper\Data $helper,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory

    ){
        $this->logger = $logger;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->storeManager = $storeManager;
        $this->helper = $helper;
    }

    /**
     * Get Categories based on section
     * @param string $section
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCategorySection($section)
    {

        //section_two_display
        $response = ['success' => false];
        try {
            $sectionType = '';
            $limit = '';
            if($section == 1 ){
                if(!$this->helper->isEnabledSectionOne()){
                    $response = ['success' => false, 'message' => "first section is not enabled"];
                    return $response;
                }else {
                    $sectionType = 'section_one_display';
                    $limit = $this->helper->getCategorySectionOneCount();
                }
            }elseif($section == 2 ){
                if(!$this->helper->isEnabledSectionTwo()){
                    $response = ['success' => false, 'message' => "second section is not enabled"];
                    return $response;
                }else {
                    $sectionType = 'section_two_display';
                    $limit = $this->helper->getCategorySectionTwoCount();
                }
            }
            if($sectionType!='') {
                $storeId = $this->storeManager->getStore()->getId();
                $categories = $this->categoryCollectionFactory->create()
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter($sectionType, 1)
                    ->setStore($storeId);
                if(is_numeric($limit)) {
                    $categories->setPageSize($limit);
                }
                $responseData = [];
                foreach ($categories as $category) {
                    $responseData[] = $category->getData();
                }
                $response = ['success' => true, 'message' => $responseData];
            }else{
                $response = ['success' => false, 'message' => "invalid section type"];
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
            $this->logger->info($e->getMessage());
        }
        return $response;
    }
}