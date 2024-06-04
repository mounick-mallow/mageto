<?php

namespace Smartwave\Filterproducts\Model;

use Magento\Catalog\Block\Product\ListProduct;

/**
 * Class Model ListProductExtend
 */
class ListProductExtend extends ListProduct
{
    /**
     * Function Get Product Count
     *
     * @return array|int|mixed|null
     */
    public function getProductCount(): mixed
    {
        $limit = $this->getData("product_count");
        if (!$limit) {
            $limit = 10;
        }
        return $limit;
    }
}
