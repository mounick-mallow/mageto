<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Smartwave\Porto\Model\Import;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Xml\Parser;
use Magento\Cms\Model\ResourceModel\Block\CollectionFactory as BlockCollectionFactory;
use Magento\Cms\Model\BlockFactory as BlockFactory;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory as PageCollectionFactory;
use Magento\Cms\Model\PageFactory as PageFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Cms get import
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Cms
{
    public const IMPORT_PATH = BP .// @phpstan-ignore-line
        '/app/code/Smartwave/Porto/etc/import/';
    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @var Parser
     */
    protected Parser $parser;

    /**
     * @var BlockCollectionFactory
     */
    protected BlockCollectionFactory $blockCollectionFactory;

    /**
     * @var BlockRepositoryInterface
     */
    protected BlockRepositoryInterface $blockRepository;

    /**
     * @var BlockFactory
     */
    protected BlockFactory $blockFactory;

    /**
     * @var PageCollectionFactory
     */
    protected PageCollectionFactory $pageCollectionFactory;

    /**
     * @var PageRepositoryInterface
     */
    protected PageRepositoryInterface $pageRepository;

    /**
     * @var PageFactory
     */
    protected PageFactory $pageFactory;

    /**
     * @var File
     */
    private File $driver;

    /**
     * Construct
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param BlockCollectionFactory $blockCollectionFactory
     * @param BlockRepositoryInterface $blockRepository
     * @param BlockFactory $blockFactory
     * @param PageCollectionFactory $pageCollectionFactory
     * @param File $driver
     * @param PageRepositoryInterface $pageRepository
     * @param PageFactory $pageFactory
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        BlockCollectionFactory $blockCollectionFactory,
        BlockRepositoryInterface $blockRepository,
        BlockFactory $blockFactory,
        PageCollectionFactory $pageCollectionFactory,
        File $driver,
        PageRepositoryInterface $pageRepository,
        PageFactory $pageFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->blockCollectionFactory = $blockCollectionFactory;
        $this->blockFactory = $blockFactory;
        $this->blockRepository = $blockRepository;
        $this->pageCollectionFactory = $pageCollectionFactory;
        $this->pageFactory = $pageFactory;
        $this->pageRepository = $pageRepository;
        $this->parser = new Parser();
        $this->driver = $driver;
    }

    /**
     * Import Cms
     *
     * @param mixed $type
     * @param mixed $demoVersion
     * @return DataObject
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * //phpcs:disable
     */
    public function importCms(mixed $type, mixed $demoVersion)
    {
        $gatewayResponse = new DataObject([
            'is_valid' => false,
            'import_path' => '',
            'request_success' => false,
            'request_message' => __('Error during Import CMS Sample Datas.'),
        ]);

        try {
            $xmlPath = self::IMPORT_PATH . $type . '.xml';
            $demoCMSxmlPath = self::IMPORT_PATH . 'demo_cms.xml';
            
            $overwrite = false;
            
            if ($this->scopeConfig->getValue("porto_settings/install/overwrite_".$type)) {
                $overwrite = true;
            }
            
            if (!$this->driver->isReadable($xmlPath) || !$this->driver->isReadable($demoCMSxmlPath)) {
                throw new LocalizedException(
                    __("Can't get the data file for import cms blocks/pages: ".$xmlPath)
                );
            }
            $data = $this->parser->load($xmlPath)->xmlToArray();
            $cmsData = $this->parser->load($demoCMSxmlPath)->xmlToArray();
            
            $arr = [];
            if ($demoVersion != "0") {
                foreach ($cmsData['root']['demos'][$demoVersion][$type]['item'] as $item) {
                    if (!is_array($item)) {
                        $arr[] = $item;
                    } else {
                        foreach ($item as $__item) {
                            $arr[] = $__item;
                        }
                    }
                }
            }

            $conflictingOldItems = [];
            
            $i = 0;
            foreach ($data['root'][$type]['cms_item'] as $_item) {
                $exist = false;
                if ($demoVersion == "0" || in_array($_item['identifier'], $arr)) {
                    if ($type == "blocks") {
                        $cmsCollection = $this->blockCollectionFactory->create()->addFieldToFilter(
                            'identifier',
                            $_item['identifier']
                        );
                        if (count($cmsCollection) > 0) {
                            $exist = true;
                        }
                        
                    } else {
                        $cmsCollection = $this->pageCollectionFactory->create()->addFieldToFilter(
                            'identifier',
                            $_item['identifier']
                        );
                        if (count($cmsCollection) > 0) {
                            $exist = true;
                        }
                        
                    }
                    if ($overwrite) {
                        if ($exist) {
                            $conflictingOldItems[] = $_item['identifier'];
                            if ($type == "blocks") {
                                $this->blockRepository->deleteById($_item['identifier']);
                            } else {
                                $this->pageRepository->deleteById($_item['identifier']);
                            }
                        }
                    } else {
                        if ($exist) {
                            $conflictingOldItems[] = $_item['identifier'];
                            continue;
                        }
                    }
                    $_item['stores'] = [0];
                    if ($type == "blocks") {
                        $this->blockFactory->create()->setData($_item)->save();
                    } else {
                        $this->pageFactory->create()->setData($_item)->save();
                    }
                    $i++;
                }
            }

            if ($i) {
                $message = $i." item(s) was(were) imported.";
            } else {
                $message = "No items were imported.";
            }
            
            $gatewayResponse->setData('is_valid', true);
            $gatewayResponse->setData('request_success', true);
            
            if ($gatewayResponse->getData('is_valid')) {
                if ($overwrite) {
                    if ($conflictingOldItems) {
                        $message .= "Items (" . count($conflictingOldItems)
                            . ") with the following identifiers were overwritten:<br/>"
                            . implode(', ', $conflictingOldItems);
                    }
                } else {
                    if ($conflictingOldItems) {
                        $message .= "<br/>Unable to import items (" . count($conflictingOldItems)
                            . ") with the following identifiers (they already exist in the database):<br/>"
                            . implode(', ', $conflictingOldItems);
                    }
                }
            }
            $gatewayResponse->setData('request_message', __($message));
        } catch (\Exception $exception) {
            $gatewayResponse->setData('is_valid', false);
            $gatewayResponse->setData('request_message', $exception->getMessage());
        }

        return $gatewayResponse;
    }
}
