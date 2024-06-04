<?php

namespace Belvg\Products\Plugin;

use Belvg\Products\Helper\Data;

class Add
{
    public Data $helper;

    private const IMAGE_TYPE = 'mini_cart_product_thumbnail';

    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    public function afterExecute($subject, $result)
    {
        if (!$result instanceof \Magento\Framework\Controller\Result\Json) {
            return $result;
        }

        $reflectionClass = new \ReflectionClass(get_class($result));
        $property = $reflectionClass->getProperty('json');
        $property->setAccessible(true);
        $value = $property->getValue($result);

        if (empty($value)) {
            return $result;
        }

        $jsonParams = json_decode($value, true);
        if (!empty($jsonParams['data']['product'])) {
            $product = $this->helper->getProduct($jsonParams['data']['product']);
            $productImage = $this->helper->getImageByProductId(
                $jsonParams['data']['product'],
                self::IMAGE_TYPE
            );

            $jsonParams['data']['product_name'] = $product->getName();
            $jsonParams['data']['image'] = $productImage;

            $result->setData($jsonParams);
        }

        return $result;
    }
}
