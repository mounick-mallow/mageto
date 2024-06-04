<?php

namespace LuxuryUnlimited\ConvertCurrencyCode\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class plugin to convert product price to store currency
 */
class ConvertStoreCurrency
{
    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * ConvertStoreCurrency constructor.
     *
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * Change price param value to store currency
     *
     * @param ProductRepositoryInterface $subject
     * @param SearchCriteriaInterface $searchCriteria
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeGetList(
        ProductRepositoryInterface $subject,
        SearchCriteriaInterface $searchCriteria
    ): array {
        /** @var Store $store */
        $store = $this->storeManager->getStore();
        $rate = $store->getCurrentCurrencyRate();
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $filters = $filterGroup->getFilters();
            foreach ($filters as $filter) {
                if ($filter->getField() == "price" && $rate) {
                    $amount = $filter->getValue() / $rate;
                    $filter->setValue($amount);
                }
            }
        }

        return [$searchCriteria];
    }
}
