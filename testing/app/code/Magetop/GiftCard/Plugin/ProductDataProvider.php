<?php

namespace Magetop\GiftCard\Plugin;

class ProductDataProvider
{
    /**
     * After Get Meta
     *
     * @param \Magento\Catalog\Ui\DataProvider\Product\Form\ProductDataProvider $subject
     * @param array $result
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetMeta(\Magento\Catalog\Ui\DataProvider\Product\Form\ProductDataProvider $subject, $result)
    {
        //@codingStandardsIgnoreLine
        $productType = @$result['add_attribute_modal']
        ['children']['create_new_attribute_modal']
        ['children']['product_attribute_add_form']
        ['arguments']['data']['config']['productType'];
        if ($productType == 'giftcard') {
            unset($result['custom_options']);
            return $result;
        } else {
            return $result;
        }
    }
}
