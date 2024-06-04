<?php
/**
 * LuxuryUnlimited_HomeApi
 *
 * @copyright   Copyright (c) 2023
 */
namespace LuxuryUnlimited\HomeApi\Model\Api;

use LuxuryUnlimited\BrandList\Helper\Data;
use Mage360\Brands\Model\ResourceModel\Brands\CollectionFactory as BrandsCollectionFactory;
use Magento\Framework\Exception\LocalizedException as LocalizedExceptionAlias;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class BrandSection
{
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var BrandsCollectionFactory
     */
    public BrandsCollectionFactory $brandsCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var Data
     */
    protected Data $helper;

    /**
     * @param LoggerInterface $logger
     * @param StoreManagerInterface $storeManager
     * @param Data $helper
     * @param BrandsCollectionFactory $brandsCollectionFactory
     */
    public function __construct(
        LoggerInterface $logger,
        StoreManagerInterface $storeManager,
        Data $helper,
        BrandsCollectionFactory $brandsCollectionFactory
    ) {
        $this->logger = $logger;
        $this->brandsCollectionFactory = $brandsCollectionFactory;
        $this->storeManager = $storeManager;
        $this->helper = $helper;
    }

    /**
     * Get Brands based on section
     *
     * @param string $section
     * @return mixed
     * @throws LocalizedExceptionAlias
     */
    public function getBrandSection($section)
    {
        $response = ['success' => false];
        try {
            $sectionType = false;
            $limit = Data::DEFAULT_BRAND_COUNT;
            if ($section == 1) {
                if (!$this->helper->isEnabledSectionOne()) {
                    return ['success' => false, 'message' => "first section is not enabled"];
                } else {
                    $sectionType = 'section_one_display';
                    $limit = $this->helper->getSectionOneBrandCount();
                }
            } elseif ($section == 2) {
                if (!$this->helper->isEnabledSectionTwo()) {
                    return ['success' => false, 'message' => "second section is not enabled"];
                } else {
                    $sectionType = 'section_two_display';
                    $limit = $this->helper->getSectionTwoBrandCount();
                }
            }
            if ($sectionType) {
                $brands = $this->brandsCollectionFactory->create()
                    ->addFieldToFilter($sectionType, $section)
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('is_active', 1);
                if (is_numeric($limit)) {
                    $brands->setPageSize($limit);
                }
                $responseData = [];
                foreach ($brands as $category) {
                    $responseData[] = $category->getData();
                }
                $response = ['success' => true, 'message' => $responseData];
            } else {
                $response = ['success' => false, 'message' => "invalid section type"];
            }
        } catch (LocalizedExceptionAlias $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
            $this->logger->info($e->getMessage());
        }
        return $response;
    }
}
