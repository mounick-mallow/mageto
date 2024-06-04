<?php

namespace Smartwave\Porto\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template as ViewTemplate;
use Magento\Store\Model\ScopeInterface;
use Smartwave\Porto\Model\Config\Backend\Image\Logo;
use Smartwave\Porto\Model\Config\Provider;

/**
 * Class block Template
 */
class Template extends ViewTemplate
{
    /**
     * @var Registry
     */
    public Registry $_coreRegistry;

    /**
     * @var Provider
     */
    private Provider $configProvider;

    /**
     * Construct
     *
     * @param Provider $configProvider
     * @param Context $context
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        Provider $configProvider,
        Context $context,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
        $this->configProvider = $configProvider;
    }

    /**
     * Get Config
     *
     * @param string $configPath
     * @param mixed|null $storeCode
     * @return mixed
     */
    public function getConfig(string $configPath, mixed $storeCode = null): mixed
    {
        return $this->configProvider->getConfig($configPath, $storeCode);
    }

    /**
     * Get Footer Logo Src
     *
     * @return string
     */
    public function getFooterLogoSrc(): string
    {
        $folderName = Logo::UPLOAD_DIR;
        $storeLogoPath = $this->_scopeConfig->getValue(
            'porto_settings/footer/footer_logo_src',
            ScopeInterface::SCOPE_STORE
        );
        $path = $folderName . '/' . $storeLogoPath;

        return $this->_urlBuilder->getBaseUrl(
            ['_type' => UrlInterface::URL_TYPE_MEDIA]
        ) . $path;
    }

    /**
     * Is Home Page
     *
     * @return bool
     */
    public function isHomePage(): bool
    {
        $currentUrl = $this->getUrl('', ['_current' => true]);
        $urlRewrite = $this->getUrl('*/*/*', [
            '_current' => true, '_use_rewrite' => true
        ]);

        return $currentUrl == $urlRewrite;
    }
}
