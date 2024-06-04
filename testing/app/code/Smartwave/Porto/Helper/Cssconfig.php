<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Smartwave\Porto\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Smartwave\Porto\Api\ViewModel\CssConfigInterface;

/**
 * Class helper Cssconfig
 */
class Cssconfig extends AbstractHelper
{
    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * Construct
     *
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Get Base Media Url
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getBaseMediaUrl(): string
    {
        return $this->storeManager->getStore()->getBaseUrl(
            UrlInterface::URL_TYPE_MEDIA
        );
    }

    /**
     * Get Css Config Dir
     *
     * @return string
     */
    public function getCssConfigDir(): string
    {
        return CssConfigInterface::GENERATED_CSS_DIR;
    }

    /**
     * Get settings File
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getSettingsFile(): string
    {
        return $this->getBaseMediaUrl()
            . CssConfigInterface::GENERATED_CSS_FOLDER
            . 'settings_'
            . $this->storeManager->getStore()->getCode() . '.css';
    }

    /**
     * Get Design File
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getDesignFile(): string
    {
        return $this->getBaseMediaUrl()
            . CssConfigInterface::GENERATED_CSS_FOLDER
            . 'design_'
            . $this->storeManager->getStore()->getCode() . '.css';
    }

    /**
     * Get Porto Web Dir
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getPortoWebDir(): string
    {
        return $this->getBaseMediaUrl().'porto/web/';
    }
}
