<?php

namespace LuxuryUnlimited\BrandList\Helper;

use Mage360\Brands\Model\ResourceModel\Brands\CollectionFactory as BrandsCollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    public const SECTION_ONE_ENABLED = 'home_page/brandlist_one/enable';
    public const SECTION_TWO_ENABLED = 'home_page/brandlist_two/enable';
    public const SECTION_ONE_BRAND_COUNT = 'home_page/brandlist_one/count';
    public const SECTION_TWO_BRAND_COUNT = 'home_page/brandlist_two/count';
    public const DEFAULT_BRAND_COUNT = '5';
    public const BRAND_IMAGE_PATH = 'mage360_brands/brands/image';

    /**
     * @var ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * @var brandsCollectionFactory
     */
    public $brandsCollectionFactory;

    /**
     * @var File
     */
    protected $fileDriver;

    /**
     * @var DirectoryList
     */
    private $dirList;

    /**
     * @var string
     */
    protected $brandListFolder = '';

    /**
     * *
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param BrandsCollectionFactory $brandsCollectionFactory
     * @param File $fileDriver
     * @param DirectoryList $dirList
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        BrandsCollectionFactory $brandsCollectionFactory,
        File $fileDriver,
        DirectoryList $dirList
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->brandsCollectionFactory  = $brandsCollectionFactory;
        $this->fileDriver = $fileDriver;
        $this->dirList = $dirList;
    }

    /**
     * *
     *
     * @return string
     */
    public function isEnabledSectionOne()
    {
        return $this->getConfigValue(self::SECTION_ONE_ENABLED);
    }

    /**
     * *
     *
     * @return string
     */
    public function isEnabledSectionTwo()
    {
        return $this->getConfigValue(self::SECTION_TWO_ENABLED);
    }

    /**
     * *
     *
     * @return string
     */
    public function getSectionOneBrandCount()
    {
        $productCount =  $this->getConfigValue(self::SECTION_ONE_BRAND_COUNT);
        return ($productCount) ? $productCount : self::DEFAULT_BRAND_COUNT;
    }

    /**
     * *
     *
     * @return string
     */
    public function getSectionTwoBrandCount()
    {
        $productCount =  $this->getConfigValue(self::SECTION_TWO_BRAND_COUNT);
        return ($productCount) ? $productCount : self::DEFAULT_BRAND_COUNT;
    }

    /**
     * *
     *
     * @param string $path
     *
     * @return string
     */
    public function getConfigValue($path)
    {
        $storeId = $this->getCurrentStoreId();
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * *
     *
     * @return string
     */
    public function getCurrentStoreId()
    {
        return $this->storeManager->getStore()->getStoreId();
    }

    /**
     * *
     *
     * @return string
     */
    public function getCurrentStoreCode()
    {
        return $this->storeManager->getStore()->getCode();
    }

    /**
     * *
     *
     * @return string
     */
    public function getMediaUrl()
    {
        return $this->storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * *
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }

    /**
     * *
     *
     * @return
     */
    public function getBrandList()
    {
        return $this->brandsCollectionFactory->create()
            ->addFieldToFilter('section_one_display', 1)
            ->setPageSize($this->getSectionOneBrandCount());
    }

    /**
     * *
     *
     * @return
     */
    public function getBrandListTwo()
    {
        return $this->brandsCollectionFactory->create()
            ->addFieldToFilter('section_two_display', 1)
            ->setPageSize($this->getSectionTwoBrandCount());
    }

    /**
     * *
     *
     * @return string
     */
    public function getBrandMediaUrl()
    {
        return $this->getMediaUrl() . self::BRAND_IMAGE_PATH;
    }

    /**
     * Check Image Src
     *
     * @param string $path
     * @return bool
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function checkImageExist($path)
    {
        return $this->fileDriver->isExists($this->brandImageFolder() . $path);
    }

    /**
     * Brand Image Folder
     *
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function brandImageFolder()
    {
        if (!$this->brandListFolder) {
            $mediaFolder = $this->dirList->getPath(DirectoryList::MEDIA);
            $this->brandListFolder = $mediaFolder . DIRECTORY_SEPARATOR . self::BRAND_IMAGE_PATH;
        }
        return $this->brandListFolder;
    }
}
