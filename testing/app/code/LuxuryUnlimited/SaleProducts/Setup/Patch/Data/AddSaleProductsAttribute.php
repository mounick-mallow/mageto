<?php
namespace LuxuryUnlimited\SaleProducts\Setup\Patch\Data;

use Magento\Catalog\Model\Product\Attribute\Source\Boolean;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddSaleProductsAttribute implements DataPatchInterface
{
    /**
     * *
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $_moduleDataSetup;

    /**
     * *
     * @var EavSetupFactory $eavSetupFactory
     */
    private $_eavSetupFactory;

    /**
     * *
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->_moduleDataSetup = $moduleDataSetup;
        $this->_eavSetupFactory = $eavSetupFactory;
    }

    /**
     * *
     *
     * @var EavSetup $eavSetup
     *
     * @return void
     */
    public function apply()
    {
        $eavSetup = $this->_eavSetupFactory->create(
            [
                'setup' => $this->_moduleDataSetup
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'add_to_sale_products',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Add to Sale Products',
                'input' => 'select',
                'class' => 'add_to_sale_products',
                'source' => Boolean::class,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => false,
                'required' => false,
                'user_defined' => false,
                'default' => 0,
                'searchable' => true,
                'filterable' => true,
                'comparable' => true,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
            ]
        );
    }

    /**
     * *
     *
     * @return
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * *
     *
     * @return
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * *
     *
     * @return
     */
    public static function getVersion()
    {
        return '1.0.0';
    }
}
