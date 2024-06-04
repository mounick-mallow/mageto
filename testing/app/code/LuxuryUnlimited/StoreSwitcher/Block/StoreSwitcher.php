<?php

namespace LuxuryUnlimited\StoreSwitcher\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use LuxuryUnlimited\StoreSwitcher\Model\WebsitesStoresRepository;

class StoreSwitcher extends \Magento\Framework\View\Element\Template
{
    public const COOKIE_NAME = 'base_url';

    /**
     * @var StoreManagerInterface $storeManager
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     */
    protected $_cookieManager;

    /**
     * @var WebsitesStoresRepository $websitesStoresRepository
     */
    protected $websitesStoresRepository;

    /**
     * *
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     * @param WebsitesStoresRepository $websitesStoresRepository
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        WebsitesStoresRepository $websitesStoresRepository
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->_cookieManager = $cookieManager;
        $this->websitesStoresRepository= $websitesStoresRepository;
    }

    /**
     * Get all websites
     *
     * @return array
     */
    public function getAllWebsites()
    {
        return $this->websitesStoresRepository->getList();
    }

    /**
     * Get base url from cookies
     *
     * @return string
     */
    public function getBaseUrlFromCookies()
    {
        return $this->_cookieManager->getCookie(self::COOKIE_NAME);
    }

    /**
     * Get current base url
     *
     * @return string
     */
    public function getCurrentUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    /**
     * Get all stores of current website
     *
     * @return array
     */
    public function getCurrentStores()
    {
        return $this->_storeManager->getWebsite($this->_storeManager->getStore()->getWebsiteId())->getStores();
        ;
    }

    /**
     * Get current website id
     *
     * @return string
     */
    public function getCurrentWebsite()
    {
        return $this->_storeManager->getStore()->getWebsite()->getCode();
    }

    /**
     * Get current store id
     *
     * @return string
     */
    public function getCurrentStore()
    {
        return $this->_storeManager->getStore()->getStoreId();
        ;
    }

    /**
     * Get store currency
     * @return mixed
     */
    public function getCurrentStoreCurrency()
    {
        return $this->_storeManager->getStore()->getDefaultCurrencyCode();
        ;
    }

    /**
     * DEVTASK-21844 : generate json for the country and code
     * used to render options using javascript avoid excessive DOM child max elements
     * 
     * @return string[]
     */
    public function generateJsonOptionCountry()
    {
        $countries = [];
        $currentWebsite = $this->getCurrentWebsite();
        $websites = $this->getAllWebsites();
        foreach ($websites as $website) {
            $selected = $website->getCode() == $currentWebsite ? 1 : 0;
            $countries[] = [
                'value' => $website->getCode(),
                'label' => $website->getName(),
                'selected' => $selected
            ];
        }

        return $countries;
    }
}
