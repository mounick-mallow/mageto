<?php

namespace LuxuryUnlimited\HomeCategorySection\Setup\Patch\Data;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Catalog\Model\Category;

class AddSectionOneDisplayAttribute implements DataPatchInterface
{
    public const ATTRIBUTE_CODE = 'section_one_display';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * @inheritdoc
     *
     * @return void
     */
    public function apply()
    {
        $customerSetup = $this->customerSetupFactory->create(
            ['setup' => $this->moduleDataSetup]
        );
        $customerSetup->addAttribute(
            Category::ENTITY,
            self::ATTRIBUTE_CODE,
            [
                'type' => 'int',
                'label' => 'Display In Section One',
                'input' => 'select',
                'sort_order' => 8,
                'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => null,
                'group' => 'General Information',
                'backend' => ''
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
}
