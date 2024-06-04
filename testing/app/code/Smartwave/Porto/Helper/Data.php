<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Smartwave\Porto\Helper;

use Exception;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\State;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Url\DecoderInterface;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Review\Model\Review;
use Magento\Review\Model\ReviewFactory;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use Smartwave\Porto\Model\Config\Provider;

/**
 * Class Helper Data
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Data extends AbstractHelper
{
    /**
     * @var FilterProvider
     */
    protected FilterProvider $filterProvider;

    /**
     * @var string
     */
    private string $_checkedPurchaseCode;

    /**
     * @var ReviewFactory
     */
    private ReviewFactory $reviewFactory;

    /**
     * @var ProductFactory
     */
    private ProductFactory $productFactory;

    /**
     * @var Provider
     */
    private Provider $configProvider;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var StockRegistryInterface
     */
    private StockRegistryInterface $stockRegistry;

    /**
     * @var State
     */
    protected State $state;

    /**
     * @var ManagerInterface
     */
    private ManagerInterface $messageManager;

    /**
     * @var ConfigInterface
     */
    private ConfigInterface $configFactory;

    /**
     * @var Curl
     */
    private Curl $curl;

    /**
     * Construct
     *
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param FilterProvider $filterProvider
     * @param ProductFactory $productFactory
     * @param ReviewFactory $reviewFactory
     * @param Provider $configProvider
     * @param ConfigInterface $configFactory
     * @param ManagerInterface $messageManager
     * @param StockRegistryInterface $stockRegistry
     * @param EncoderInterface $urlEncoder
     * @param Curl $curl
     * @param DecoderInterface $urlDecoder
     * @param State $state
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        FilterProvider $filterProvider,
        ProductFactory $productFactory,
        ReviewFactory $reviewFactory,
        Provider $configProvider,
        ConfigInterface $configFactory,
        ManagerInterface $messageManager,
        StockRegistryInterface $stockRegistry,
        EncoderInterface $urlEncoder,
        Curl $curl,
        DecoderInterface $urlDecoder,
        State $state
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->filterProvider = $filterProvider;
        $this->stockRegistry = $stockRegistry;
        $this->reviewFactory = $reviewFactory;
        $this->productFactory = $productFactory;
        $this->configProvider = $configProvider;
        $this->state = $state;
        $this->urlDecoder = $urlDecoder;
        $this->urlEncoder = $urlEncoder;
        $this->messageManager = $messageManager;
        $this->configFactory = $configFactory;
        $this->curl = $curl;
    }

    /**
     * CheckPurchase Code
     *
     * @param bool $save
     * @return string
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function checkPurchaseCode(bool $save = false): string
    {
        if ($this->isLocalhost()) {
            return "localhost";
        }
        if (!$this->_checkedPurchaseCode) {
            $code = $this->scopeConfig->getValue(
                'porto_license/general/purchase_code'
            );
            $codeConfirm = $this->scopeConfig->getValue(
                'porto_license/general/purchase_code_confirm'
            );

            if ($save) {
                $siteUrl = $this->scopeConfig->getValue('web/unsecure/base_url');
                $domain = trim(preg_replace('/^.*?\\/\\/(.*)?\\//', '$1', $siteUrl));
                if (str_contains($domain, "/")) {
                    $domain = substr($domain, 0, strpos($domain, "/"));
                }
                if (!$code || $this->urlEncoder->encode($code) != $codeConfirm) {
                    $this->curlPurchaseCode(
                        $this->urlDecoder->decode($codeConfirm),
                        "",
                        "remove"
                    );
                }
                if ($code) {
                    $result = $this->curlPurchaseCode($code, $domain, "add");
                    if (!$result || $result['result'] == 0) {
                        $this->_checkedPurchaseCode = "";
                        $codeConfirm = "";
                        $this->messageManager->getMessages(true);
                        $this->messageManager->addWarningMessage(
                            __('Purchase code is not valid!')
                        );
                    } elseif ($result['result'] == 1) {
                        $codeConfirm = $this->urlEncoder->encode($code);
                        $this->_checkedPurchaseCode = "verified";
                    } else {
                        $this->_checkedPurchaseCode = "";
                        $codeConfirm = "";
                        $this->messageManager->getMessages(true);
                        $this->messageManager->addWarningMessage(
                            __($result['message'])
                        );
                    }
                } else {
                    $codeConfirm = "";
                    $this->_checkedPurchaseCode = "";
                }
                $this->configFactory->saveConfig(
                    'porto_license/general/purchase_code_confirm',
                    $codeConfirm,
                    "default",
                    0
                );
            } else {
                if ($code && $codeConfirm &&
                    $this->urlEncoder->encode($code) == $codeConfirm) {
                    $this->_checkedPurchaseCode = "verified";
                }
            }
        }

        return $this->_checkedPurchaseCode;
    }

    /**
     * Curl Purchase code
     *
     * @param mixed $code
     * @param string $domain
     * @param string $act
     * @return mixed
     */
    public function curlPurchaseCode(mixed $code, string $domain, string $act)
    {
        $url =  'http://www.portotheme.com/envato/verify_purchase_new.php';
        $params = [
            'item'      => '9725864',
            'version'   => 'm2',
            'code'      => $code,
            'domain'    => $domain,
            'act'       => $act
        ];
        $this->curl->setOption(CURLOPT_RETURNTRANSFER, 1);
        $this->curl->setOption(CURLOPT_USERAGENT, 'PORTO-PURCHASE-VERIFY');

        $this->curl->post($url, $params);

        $body = $this->curl->getBody();

        return !empty($body) ? json_decode($body) : '';
    }

    /**
     * Is Local Host
     *
     * @return bool
     */
    public function isLocalhost(): bool
    {
        $whitelist = [
            '127.0.0.1',
            '::1'
        ];

        return in_array($_SERVER['REMOTE_ADDR'], $whitelist); // phpcs:ignore
    }

    /**
     * Get Base Url
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getBaseUrl(): string
    {
        return $this->storeManager->getStore()->getBaseUrl(
            UrlInterface::URL_TYPE_MEDIA
        );
    }

    /**
     * Get Base Link Url
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getBaseLinkUrl(): string
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }

    /**
     * Get Config
     *
     * @param string $configPath
     * @return mixed
     */
    public function getConfig(string $configPath): mixed
    {
        return $this->configProvider->getConfig($configPath);
    }

    /**
     * Get Model
     *
     * @return Review
     */
    public function getModel(): Review
    {
        return $this->reviewFactory->create();
    }

    /**
     * Filter Content
     *
     * @param mixed $content
     * @return string
     * @throws Exception
     */
    public function filterContent(mixed $content): string
    {
        return $this->filterProvider->getPageFilter()->filter($content);
    }

    /**
     * Get Category Product Ids
     *
     * @param mixed $currentCategory
     * @return mixed
     */
    public function getCategoryProductIds(mixed $currentCategory): mixed
    {
        $categoryProducts = $currentCategory->getProductCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToSort('position', 'asc');
        return $categoryProducts->getAllIds();
    }

    /**
     * Get Prev Product
     *
     * @param mixed $product
     * @return false|Product
     */
    public function getPrevProduct(mixed $product): bool|Product
    {
        $currentCategory = $this->getCurrentCategory($product);
        if (!$currentCategory) {
            foreach ($product->getCategoryCollection() as $parentCat) {
                $currentCategory = $parentCat;
            }
        }
        if (!$currentCategory) {
            return false;
        }
        $catProdIds = $this->getCategoryProductIds($currentCategory);
        $pos = array_search($product->getId(), $catProdIds);
        if (isset($catProdIds[$pos - 1])) {
            return $this->productFactory->create()->load($catProdIds[$pos - 1]);
        }
        return false;
    }

    /**
     * Get Next Product
     *
     * @param mixed $product
     * @return false|Product
     */
    public function getNextProduct(mixed $product): bool|Product
    {
        $currentCategory = $this->getCurrentCategory($product);
        if (!$currentCategory) {
            foreach ($product->getCategoryCollection() as $parentCat) {
                $currentCategory = $parentCat;
            }
        }
        if (!$currentCategory) {
            return false;
        }
        $catProdIds = $this->getCategoryProductIds($currentCategory);
        $pos = array_search($product->getId(), $catProdIds);
        if (isset($catProdIds[$pos + 1])) {
            return $this->productFactory->create()->load($catProdIds[$pos + 1]);
        }
        return false;
    }

    /**
     * Get Masonry Item Class
     *
     * @param array $arr
     * @return string
     */
    public function getMasonryItemClass(array $arr): string
    {
        $itemClass = "";
        foreach ($arr as $key => $value) {
            $itemClass .= ' ' . $key . '-' . $value;
        }

        return $itemClass;
    }

    /**
     * Get Stock Status
     *
     * @param mixed $product
     * @return bool|int
     */
    public function getStockStatus(mixed $product): bool|int
    {
        return $this->stockRegistry->getStockItem($product->getId())->getIsInStock();
    }

    /**
     * Get Current Category
     *
     * @param mixed $product
     * @return mixed
     */
    protected function getCurrentCategory($product): mixed
    {
        return $product->getCategory();
    }
}
