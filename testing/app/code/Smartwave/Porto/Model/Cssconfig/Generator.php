<?php

namespace Smartwave\Porto\Model\Cssconfig;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem\Driver\File as Driver;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\LayoutInterface;
use Magento\Store\Model\StoreManagerInterface;
use Smartwave\Porto\Api\ViewModel\CssConfigInterface;
use Smartwave\Porto\Block\Template;

/**
 * Class Generator to generate Css
 */
class Generator
{
    /**
     * @var ManagerInterface
     */
    protected ManagerInterface $messageManager;

    /**
     * @var Registry
     */
    protected Registry $coreRegistry;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @var LayoutInterface
     */
    protected LayoutInterface $layoutManager;

    /**
     * @var File
     */
    private File $file;

    /**
     * @var Driver
     */
    private Driver $driver;

    /**
     * Construct
     *
     * @param Registry $coreRegistry
     * @param StoreManagerInterface $storeManager
     * @param LayoutInterface $layoutManager
     * @param ManagerInterface $messageManager
     * @param Driver $driver
     * @param File $file
     */
    public function __construct(
        Registry $coreRegistry,
        StoreManagerInterface $storeManager,
        LayoutInterface $layoutManager,
        ManagerInterface $messageManager,
        Driver $driver,
        File $file,
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->storeManager = $storeManager;
        $this->layoutManager = $layoutManager;
        $this->messageManager = $messageManager;
        $this->driver = $driver;
        $this->file = $file;
    }

    /**
     * Generate Css
     *
     * @param mixed $type
     * @param int|string $websiteId
     * @param int|string $storeId
     * @return void
     * @throws NoSuchEntityException
     * @throws LocalizedException
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function generateCss(
        mixed $type,
        int|string $websiteId,
        int|string $storeId
    ) {
        if (!$websiteId && !$storeId) {
            $websites = $this->storeManager->getWebsites();
            foreach ($websites as $id => $value) {
                $this->generateWebsiteCss($type, $id);
            }
        } else {
            if ($storeId) {
                $this->generateStoreCss($type, $storeId);
            } else {
                $this->generateWebsiteCss($type, $websiteId);
            }
        }
    }

    /**
     * Generate Website Css
     *
     * @param mixed $type
     * @param int|string $websiteId
     * @return void
     * @throws LocalizedException
     */
    protected function generateWebsiteCss(mixed $type, int|string $websiteId)
    {
        $website = $this->storeManager->getWebsite($websiteId);
        foreach ($website->getStoreIds() as $storeId) {
            $this->generateStoreCss($type, $storeId);
        }
    }

    /**
     * Generate Store Css
     *
     * @param mixed $type
     * @param int|string $storeId
     * @return void
     * @throws NoSuchEntityException
     */
    protected function generateStoreCss(mixed $type, int|string $storeId)
    {
        $store = $this->storeManager->getStore($storeId);
        if (!$store->isActive()) {
            return;
        }
        $storeCode = $store->getCode();
        $str1 = '_'.$storeCode;
        $str2 = $type.$str1.'.css';
        $str3 = CssConfigInterface::GENERATED_CSS_DIR . $str2;
        $str4 = 'porto/css/'.$type.'.phtml';
        $this->coreRegistry->register('cssgen_store', $storeCode);

        try {
            $block = $this->layoutManager->createBlock(
                Template::class
            )->setData('area', 'frontend')->setTemplate($str4)->toHtml();
            if (!$this->file->fileExists(CssConfigInterface::GENERATED_CSS_DIR)) {
                $this->driver->createDirectory(
                    CssConfigInterface::GENERATED_CSS_DIR
                );
            }

            $file = $this->driver->fileOpen($str3, "w+");
            $this->driver->fileLock($file);
            $this->driver->fileWrite($file, $block);
            $this->driver->fileLock($file, LOCK_UN);
            $this->driver->fileClose($file);
            if (empty($block)) {
                throw new \Exception(
                    __("Template file is empty or doesn't exist: ".$str4)
                );
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __(
                    'Failed generating CSS file: '
                    . $str2.' in '. CssConfigInterface::GENERATED_CSS_DIR
                )
                . <<<HTML
                    <br/>Message:
                   HTML . $e->getMessage()
            );
        }
        $this->coreRegistry->unregister('cssgen_store');
    }
}
