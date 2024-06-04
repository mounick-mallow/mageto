<?php

namespace LuxuryUnlimited\SizeAttribute\Model\Config\Source;

use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;

/**
 * List Attributes
 * Class AttributeOptions
 */
class AttributeOptions implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var CollectionFactory $attributeCollectionFactory
     */
    protected $attributeCollectionFactory;

    /**
     * Constructor
     *
     * @param CollectionFactory $attributeCollectionFactory
     */
    public function __construct(
        CollectionFactory $attributeCollectionFactory
    ) {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
    }

    /**
     * Get Attribute as Array
     *
     * @return []
     */
    public function toOptionArray()
    {
        $options = [];
        $attributes = $this->attributeCollectionFactory->create()
            ->addFieldToFilter('is_user_defined', 1);

        foreach ($attributes as $attribute) {
            $label = $attribute->getStoreLabel()." (".$attribute->getAttributeCode().")";
            $options[] = [
                'value' => $attribute->getAttributeId(),
                'label' => $label,
            ];
        }

        return $options;
    }
}
