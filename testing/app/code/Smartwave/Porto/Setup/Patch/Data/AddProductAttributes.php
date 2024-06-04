<?php

namespace Smartwave\Porto\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchInterface;
use Smartwave\Porto\Model\Attribute\Productimagesize;
use Smartwave\Porto\Model\Attribute\Productpagetype;

/**
 * Class data patch to add product attributes
 */
class AddProductAttributes implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private EavSetupFactory $eavSetupFactory;

    /**
     * Construct
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Apply
     *
     * @inerhitDoc
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function apply(): PatchInterface
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(
            ['setup' => $this->moduleDataSetup]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            'product_page_type',
            [
            'group' => 'Product Details',
            'type' => 'varchar',
            'sort_order' => 200,
            'backend' => '',
            'frontend' => '',
            'label' => 'Product Page Type',
            'input' => 'select',
            'source' => Productpagetype::class,
            'class' => '',
            'global' => Attribute::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => false,
            'unique' => false,
            'wysiwyg_enabled' => false,
            'apply_to' => 'simple,configurable,virtual,bundle,downloadable'
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            'product_image_size',
            [
            'group' => 'Product Details',
            'type' => 'varchar',
            'sort_order' => 201,
            'backend' => '',
            'frontend' => '',
            'label' => 'Product Image Size',
            'input' => 'select',
            'source' => Productimagesize::class,
            'class' => '',
            'global' => Attribute::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => false,
            'unique' => false,
            'wysiwyg_enabled' => false,
            'apply_to' => 'simple,configurable,virtual,bundle,downloadable'
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            'custom_block',
            [
            'group' => 'Product Details',
            'type' => 'text',
            'sort_order' => 202,
            'backend' => '',
            'frontend' => '',
            'label' => 'Custom Block',
            'input' => 'textarea',
            'class' => '',
            'global' => Attribute::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => false,
            'unique' => false,
            'wysiwyg_enabled' => true,
            'apply_to' => 'simple,configurable,virtual,bundle,downloadable'
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            'custom_block_2',
            [
            'group' => 'Product Details',
            'type' => 'text',
            'sort_order' => 203,
            'backend' => '',
            'frontend' => '',
            'label' => 'Custom Block 2',
            'input' => 'textarea',
            'class' => '',
            'global' => Attribute::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => false,
            'unique' => false,
            'wysiwyg_enabled' => true,
            'apply_to' => 'simple,configurable,virtual,bundle,downloadable'
            ]
        );

        return $this;
    }

    /**
     * Get Dependencies
     *
     * @inerhitDoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * Get Aliases
     *
     * @inerhitDoc
     */
    public function getAliases(): array
    {
        return [];
    }
}
