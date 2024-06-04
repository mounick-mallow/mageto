<?php

declare(strict_types=1);

namespace Smile\ElasticCatalog\Model\Layer\Filter\Item;

use Magento\Catalog\Model\Layer\Filter\Item;
use Magento\Framework\Exception\LocalizedException;

/**
 * Category class
 */
class Category extends Item
{
    /**
     * Get category filter URL
     *
     * @return string
     */
    public function getCategoryFilterUrl(): string
    {
        $qsParams = $this->getApplyQueryStringParams();
        $url = $this->_url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $qsParams]);

        return $url;
    }

    /**
     * Get category URL
     *
     * @return string
     */
    public function getCategoryUrl(): string
    {
        $url = parent::getUrl();

        if ($this->getUrlRewrite()) {
            $url = $this->getUrlRewrite();
        }

        return $url;
    }

    /**
     * Get remove URL
     *
     * @return string
     * @throws LocalizedException
     */
    public function getRemoveUrl(): string
    {
        $query = [$this->getFilter()->getRequestVar() => $this->getFilter()->getResetValue()];

        if ($this->getApplyValue()) {
            $query = [$this->getFilter()->getRequestVar() => $this->getApplyValue()];
        }

        $params = [
            '_current'     => true,
            '_use_rewrite' => true,
            '_query'       => $query,
            '_escape'      => true,
        ];

        return $this->_url->getUrl('*/*/*', $params);
    }

    /**
     * Get apply query params string
     *
     * @return array
     * @throws LocalizedException
     */
    private function getApplyQueryStringParams(): array
    {
        $qsParams = [
            $this->getFilter()->getRequestVar() => $this->getApplyValue(),
            $this->_htmlPagerBlock->getPageVarName() => null,
        ];

        return $qsParams;
    }

    /**
     * Get apply value
     *
     * @return mixed
     */
    private function getApplyValue(): mixed
    {
        $value = $this->getValue();

        if (is_array($this->getApplyFilterValue())) {
            $value = $this->getApplyFilterValue();
        }

        if (is_array($value) && count($value) == 1) {
            $value = current($value);
        }

        return $value;
    }
}
