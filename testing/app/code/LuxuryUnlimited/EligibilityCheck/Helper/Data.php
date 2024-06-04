<?php

namespace LuxuryUnlimited\EligibilityCheck\Helper;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Sales\Api\Data\OrderInterface;
use LuxuryUnlimited\EligibilityCheck\Logger\Logger;
/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 */
class Data extends AbstractHelper
{


    /**
     * @var ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var ProductFactory $productModel
     */
    protected $productModel;

    /**
     * @var Logger
     */
    protected $logger;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        CategoryCollectionFactory $categoryCollectionFactory,
        ProductFactory $productModel,
        OrderInterface $order,
        Logger $logger
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->productModel = $productModel;
        $this->order = $order;
        $this->logger = $logger;
    }

    public function checkCategoryEligibility($categoryIds){
        $categories = $this->categoryCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('is_active','1')
            ->addAttributeToFilter('is_eligible',1)
            ->addAttributeToFilter('entity_id', $categoryIds)
            ->setStore($this->storeManager->getStore()); //categories from current store will be fetched
        //$categories->addAttributeToFilter(array(array('attribute'=>'is_eligible', 'neq' => '1'),array('attribute'=>'is_eligible', 'null' => 'true')));
        return count($categories);
    }

    /**
     * @param $orderIncrementId
     * @return bool
     */
    public function checkOrderEligibility($orderIncrementId){
        $itemsEligible = $this->orderData($orderIncrementId);
        return $itemsEligible;
    }

    /**
     * @param $orderIncrementId
     * @param $productSku
     * @return array
     */
    public function checkItemEligibility($orderIncrementId,$productSku){
        $order = $this->order->loadByIncrementId($orderIncrementId);
        $orderCreatedOn =$order->getCreatedAt();
        $eligible = 0;
        foreach ($order->getAllVisibleItems() as $item) {

            $itemSku = $item->getSku();

            if($item->getProduct() && $productSku == $itemSku) {
                $categoryIds = $item->getProduct()->getCategoryIds();

                if ($categoryIds) {
                    // If anyone of the assigned category is eligible then  we consider as eligibility true
                    $isEligibleCategory = $this->checkCategoryEligibility($categoryIds);
                    if ($isEligibleCategory > 0) {
                        $eligible = 1;
                    }else{
                        $this->logger->info("ITEM RETURN order id $orderIncrementId,item sku $itemSku categories are not eligible for return");
                    }
                }
                if ($eligible) {
                    $isEligible = $item->getIsReturnEligible();
                    $duration = $item->getReturnDuration();
                    if ($isEligible!="" && $duration!="") {
                        if ($isEligible && is_numeric($duration)) {
                            $todayDate = $this->_getCurrentDateTime();
                            $dtOrder = new \DateTime($orderCreatedOn);
                            $dtNow = new \DateTime($todayDate);
                            $interval = $dtOrder->diff($dtNow);
                            $orderDays = $interval->days;
                            if ($orderDays <= $duration) {
                                $eligible = 1;
                            } else {
                                $eligible = 0;
                                $this->logger->info("ITEM RETURN order id $orderIncrementId,item sku $itemSku not available for return");
                            }
                        } else {
                            $this->logger->info("ITEM RETURN order id $orderIncrementId,item sku $itemSku not configured for correctly");
                        }
                    }else{
                        //negative scenario
                        $eligible = 2;
                        $this->logger->info("ITEM RETURN order id $orderIncrementId,item sku $itemSku eligible flags are not updated");
                    }
                }
                break;
            }else{
                $this->logger->info("ITEM RETURN $itemSku is not valid product");
            }

        }
        return $eligible;
    }

    /**
     * @param $orderIncrementId
     * @return array
     */
    public function orderData($orderIncrementId)
    {
        $order = $this->order->loadByIncrementId($orderIncrementId);
        $orderCreatedOn =$order->getCreatedAt();
        $item_data = [];

        foreach ($order->getAllVisibleItems() as $item) {
            $eligible = 0;
            $itemSku = $item->getSku();
            if($item->getProduct()) {
                $categoryIds = $item->getProduct()->getCategoryIds();
                if ($categoryIds) {
                    // If anyone of the assigned category is eligible then  we consider as eligibility true
                    $isEligibleCategory = $this->checkCategoryEligibility($categoryIds);
                    if ($isEligibleCategory > 0) {
                        $eligible = 1;
                    }else{
                        $this->logger->info("ORDER RETURN order id $orderIncrementId,item sku $itemSku categories are not eligible for return");
                    }
                }
                if ($eligible) {
                    $isEligible = $item->getIsReturnEligible();
                    $duration = $item->getReturnDuration();
                    if ($isEligible!="" && $duration!="") {
                        if ($isEligible && is_numeric($duration)) {
                            $todayDate = $this->_getCurrentDateTime();
                            $dtOrder = new \DateTime($orderCreatedOn);
                            $dtNow = new \DateTime($todayDate);
                            $interval = $dtOrder->diff($dtNow);
                            $orderDays = $interval->days;
                            if ($orderDays <= $duration) {
                                $eligible = 1;
                            } else {
                                $eligible = 0;
                                $this->logger->info("ORDER RETURN order id $orderIncrementId,item sku $itemSku not available for return");
                            }
                        }else{
                            $this->logger->info("ORDER RETURN order id $orderIncrementId,item sku $itemSku not configured for correctly");
                        }
                    }else{
                        //negative scenario
                        $eligible = 2;
                        $this->logger->info("ORDER RETURN order id $orderIncrementId,item sku $itemSku eligible flags are not updated");
                    }
                }
            }else{
                $this->logger->info("ORDER RETURN $itemSku is not valid product");
            }
            //for product Id
            $item_data[$itemSku] = $eligible;
        }
        return $item_data;
    }

    public function _getCurrentDateTime(): string
    {
        return (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
    }

    public function checkCancelOrder($orderIncrementId){
        $order = $this->order->loadByIncrementId($orderIncrementId);
        $eligibleStatus = ['ordered','processing'];
        $isCancel = true;
        foreach ($order->getAllVisibleItems() as $item) {
            $itemStatus = strtolower($item->getData('status'));
            if(!in_array($itemStatus,$eligibleStatus)){
                $isCancel = false;
            }
        }
        return $isCancel;
    }
}
