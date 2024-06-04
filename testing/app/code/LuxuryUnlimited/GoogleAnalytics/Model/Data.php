<?php

namespace LuxuryUnlimited\GoogleAnalytics\Model;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Checkout\Model\Cart;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\QuoteFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data
{
    /**
     * @var CategoryRepositoryInterface
     */
    protected CategoryRepositoryInterface $categoryRepository;

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @var Cart
     */
    protected Cart $cart;

    /**
     * @var OrderRepositoryInterface
     */
    protected OrderRepositoryInterface $orderRepository;

    /**
     * @var QuoteFactory
     */
    protected $quoteFactory;

    /**
     * Constructor
     *
     * @param CategoryRepositoryInterface $categoryRepository
     * @param StoreManagerInterface $storeManager
     * @param ProductRepository $productRepository
     * @param Cart $cart
     * @param QuoteFactory $quoteFactory
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        StoreManagerInterface $storeManager,
        ProductRepository $productRepository,
        Cart $cart,
        QuoteFactory $quoteFactory,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
        $this->cart = $cart;
        $this->quoteFactory = $quoteFactory;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Get GA product data
     *
     * @param array $originalData
     * @param string $eventName
     * @return array
     * @throws NoSuchEntityException
     */
    public function getGoogleAnalyticsProductData($originalData, string $eventName)
    {
        try {
            $product = $this->productRepository->getById($originalData['product_id']);
        } catch (\Exception $e) {
            return [];
        }
        $store = $this->storeManager->getStore();
        $data['event'] = $eventName;
        $data['ecommerce'] = [];
        $data['ecommerce']['items'] = [];

        $productData = [];
        $productData['item_id'] = $product->getSku();
        $productData['item_name'] = html_entity_decode($product->getName()); // @codingStandardsIgnoreLine
        $productData['affiliation'] = '';
        $productData['currency'] = $store->getDefaultCurrencyCode();
        $productData['item_brand'] = 'Google';
        $categoryIds = $product->getCategoryIds();
        $productData += $this->getCategoriesData($categoryIds);
        //$productData['location_id'] = 'ChIJIQBpAG2ahYAR_6128GcTUEo';
        $productData['price'] = number_format(
            $product->getPriceInfo()->getPrice('final_price')->getValue(),
            2,
            '.',
            ''
        );
        $productData['item_list_id'] = 'category_page';
        $productData['item_list_name'] = 'Category Page';
        $productData['quantity'] = isset($originalData['qty']) && $originalData['qty'] ? $originalData['qty'] : 1;
        $data['ecommerce']['items'][] = $productData;

        return $data;
    }

    /**
     * Get GA cart quote items data
     *
     * @param string $event
     * @return array
     */
    public function getGoogleAnalyticsCartData(string $event)
    {
        $data['event'] = $event;
        $data['ecommerce'] = [];
        $data['ecommerce']['items'] = [];
        $items = $this->cart->getQuote()->getAllVisibleItems();
        $data['ecommerce']['items'] += $this->getItemsData($items);

        return $data;
    }

    /**
     * Get GA checkout quote items data
     *
     * @param int $quoteId
     * @return array
     */
    public function getGoogleAnalyticsQuoteData($quoteId)
    {
        $data['event'] = 'add_payment_info';
        $data['ecommerce'] = [];
        $data['ecommerce']['items'] = [];
        try {
            $quote = $this->quoteFactory->create()->loadByIdWithoutStore($quoteId);
            $items = $quote->getAllVisibleItems();
        } catch (NoSuchEntityException $e) {
            return [];
        }
        $data['ecommerce']['items'] += $this->getItemsData($items);

        return $data;
    }

    /**
     * Get GA order data
     *
     * @param int $orderId
     * @return array
     */
    public function getGoogleAnalyticsOrderData($orderId)
    {
        $order = $this->orderRepository->get($orderId);
        $data['event'] = 'purchase';
        $data['value'] = $order->getGrandTotal();
        $data['tax'] = $order->getTaxAmount();
        $data['shipping'] = $order->getShippingAmount();
        $data['ecommerce'] = [];
        $data['ecommerce']['items'] = [];
        $items = $order->getAllVisibleItems();
        $data['ecommerce']['items'] += $this->getItemsData($items);

        return $data;
    }

    /**
     * Get cart items data
     *
     * @param array $items
     * @return array
     */
    private function getItemsData($items)
    {
        $itemLabels = [];
        foreach ($items as $item) {
            unset($productData);
            $productData['item_id'] = $item->getSku();
            $productData['item_name'] = html_entity_decode($item->getName()); // @codingStandardsIgnoreLine
            $productData['item_brand'] = 'Google';
            try {
                $product = $this->productRepository->getById($item->getProductId());
                $categoryIds = $product->getCategoryIds();
                $productData += $this->getCategoriesData($categoryIds);
                $productData += $this->getSelectedOptionsData($item);
            } catch (\Exception $e) {
                return [];
            }
            $productData['qty'] = $item->getQty() ?: $item->getQtyOrdered() ?: 1;
            $itemLabels[] = $productData;
        }

        return $itemLabels;
    }

    /**
     * Get selected options data by cart item
     *
     * @param object $item
     * @return array
     */
    private function getSelectedOptionsData($item)
    {
        $optionsData = [];
        $numCat = 1;
        $options = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
        if (isset($options['attributes_info']) && $options['attributes_info']) {
            foreach ($options['attributes_info'] as $option) {
                try {
                    $variant = $option['label'] . ' - ' . $option['value'];
                    $numCat == 1 ? $optionsData['item_variant'] = $variant :
                        $optionsData['item_variant' . $numCat] = $variant;
                    $numCat++;
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        return $optionsData;
    }

    /**
     * Get categories data by product
     *
     * @param array $categoryIds
     * @return array
     * @throws NoSuchEntityException
     */
    private function getCategoriesData($categoryIds)
    {
        $store = $this->storeManager->getStore();
        $categoriesName = [];
        $numCat = 1;
        if ($categoryIds) {
            foreach ($categoryIds as $categoryId) {
                try {
                    $category = $this->categoryRepository->get($categoryId, $store->getId());
                    $categoryName = $category->getName();
                    $numCat == 1 ? $categoriesName['item_category'] = $categoryName :
                        $categoriesName['item_category' . $numCat] = $categoryName;
                    $numCat++;
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        return $categoriesName;
    }
}
