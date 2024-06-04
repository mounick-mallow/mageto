<?php

namespace Belvg\MenuAdditional\Setup\Patch\Data;

use Magento\Catalog\Model\Category;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CategoryTextAttribute implements DataPatchInterface
{
    public EavSetupFactory $eavSetupFactory;

    public ModuleDataSetupInterface $moduleDataSetup;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function apply()
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(
            Category::ENTITY,
            'menu_additional_class',
            [
                'type' => 'varchar',
                'label' => 'Menu Additional Class',
                'input' => 'text',
                'sort_order' => 100,
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => null,
                'group' => 'General Information'
            ]
        );
    }

    public static function getDependencies(): array
    {
        return [];
    }
    public function getAliases(): array
    {
        return [];
    }
}
