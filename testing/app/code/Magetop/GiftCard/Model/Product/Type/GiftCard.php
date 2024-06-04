<?php

namespace Magetop\GiftCard\Model\Product\Type;

class GiftCard extends \Magento\Catalog\Model\Product\Type\Virtual
{
    public const TYPE_ID = "giftcard";

    /**
     * Delete Type Specific Data
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function deleteTypeSpecificData(\Magento\Catalog\Model\Product $product)
    {
        return null;
    }
}
