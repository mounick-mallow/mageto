<?php

namespace LuxuryUnlimited\FrontendConfigs\ViewModel;

use Exception;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Helper\Image;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\ShipmentRepositoryInterface;
use Dynamic\Orderreturn\Helper\Data as OrderReturnHelper;
use Dynamic\Customization\Helper\Data as CustomizationHelper;
use Dynamic\OrderTracking\Helper\Data as OrderTrackingHelper;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;

class OrderHistory implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_productFactory;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $_imageHelper;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Sales\Api\ShipmentRepositoryInterface
     */
    private $shipmentRepository;

    /**
     * @var \Dynamic\Orderreturn\Helper\Data
     */
    protected $orderReturnHelper;

    /**
     * @var \Dynamic\Customization\Helper\Data
     */
    protected $customizationHelper;

    /**
     * @var \Dynamic\OrderTracking\Helper\Data
     */
    protected $orderTrackingHelper;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $pricingHelper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param ProductFactory $productFactory
     * @param Image $imageHelper
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ShipmentRepositoryInterface $shipmentRepository
     * @param OrderReturnHelper $orderReturnHelper
     * @param CustomizationHelper $customizationHelper
     * @param OrderTrackingHelper $orderTrackingHelper
     * @param PricingHelper $pricingHelper
     * @param LoggerInterface $logger
     */
    public function __construct(
        ProductFactory $productFactory,
        Image $imageHelper,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ShipmentRepositoryInterface $shipmentRepository,
        OrderReturnHelper $orderReturnHelper,
        CustomizationHelper $customizationHelper,
        OrderTrackingHelper $orderTrackingHelper,
        PricingHelper $pricingHelper,
        LoggerInterface $logger
    ) {
        $this->_productFactory = $productFactory;
        $this->_imageHelper = $imageHelper;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->shipmentRepository = $shipmentRepository;
        $this->orderReturnHelper = $orderReturnHelper;
        $this->customizationHelper = $customizationHelper;
        $this->orderTrackingHelper = $orderTrackingHelper;
        $this->pricingHelper = $pricingHelper;
        $this->logger = $logger;
    }

    /**
     * Get product image
     *
     * @param string $id
     *
     * @return string
     */
    public function getProductImage($id)
    {
        $product = $this->_productFactory->create()->load($id);
        return $this->_imageHelper
        ->init($product, 'product_swatch_image_medium')
        ->setImageFile($product->getFile())->getUrl();
    }

    /**
     * Get selected option
     *
     * @param object $item
     *
     * @return array
     */
    public function getSelectedOptions($item)
    {
        $result = [];
        $options = $item->getProductOptions();
        if ($options) {
            if (isset($options['options'])) {
                $result = array_merge($result, $options['options']);
            }
            if (isset($options['additional_options'])) {
                $result = array_merge($result, $options['additional_options']);
            }
            if (isset($options['attributes_info'])) {
                $result = array_merge($result, $options['attributes_info']);
            }
        }
        return $result;
    }

    /**
     * Get shipment data
     *
     * @param int $orderId
     * @return string|null
     */
    public function getShipmentDataByOrderId(int $orderId)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('order_id', $orderId)->create();
        try {
            $shipments = $this->shipmentRepository->getList($searchCriteria);
            $shipmentRecords = $shipments->getItems();
        } catch (Exception $exception) {
            $this->logger->critical($exception->getMessage());
            $shipmentRecords = null;
        }
        $shipmentDate = null;
        if (!empty($shipmentRecords)) {
            foreach ($shipmentRecords as $shipment) {
                $shipmentDate = $shipment->getCreatedAt();
            }
            $shipmentDate = date("d M", strtotime($shipmentDate));
        }
        return $shipmentDate;
    }

    /**
     * Get reason of order cancel
     *
     * @return array
     */
    public function getReasonData(): array
    {
        $configurationReason = $this->orderReturnHelper->getConfigValue(
            'ordercancel_reason/ordercancel_configuration/reason'
        );

        return !empty($configurationReason) ?
            json_decode($configurationReason, true) : [];
    }

    /**
     * Get reason of order return
     *
     * @return array
     */
    public function getReturnReasonData(): array
    {
        $reason = $this->orderReturnHelper->getConfigValue(
            'orderreturn_reason/orderreturn_configuration/reason'
        );

        return !empty($reason) ? json_decode($reason, true) : [];
    }

    /**
     * Get customer session manager
     *
     * @return array
     */
    public function getCustomerSessionManager()
    {
        return $this->customizationHelper->getCustomerSessionManager();
    }

    /**
     * Get store code
     *
     * @return string
     */
    public function getStoreCode()
    {
        return $this->customizationHelper->getStoreManager()->getStore()->getCode();
    }

    /**
     * Get order status list
     *
     * @param object $order
     *
     * @return array
     */
    public function getOrderStatusList($order)
    {
        return $this->orderTrackingHelper->getOrderStatusList($order);
    }

    /**
     * Order date formate
     *
     * @param string $date
     *
     * @return string
     */
    public function formateOrderDate($date)
    {
        return date("d M Y", strtotime($date));
    }

    /**
     * Get formatted price
     *
     * @param string $price
     *
     * @return string
     */
    public function formatePrice($price)
    {
        return $this->pricingHelper->currency($price, true, false);
    }
}
