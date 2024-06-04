<?php

namespace Smartwave\Filterproducts\Setup\Patch\Data;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Catalog\Model\Product;
use Psr\Log\LoggerInterface;

/**
 * Class Data Patch
 */
class CreateSwReaturedAtt implements DataPatchInterface, PatchRevertableInterface
{
    private const ATTRIBUTE_CODE = 'sw_featured';

    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var EavSetupFactory
     */
    private EavSetupFactory $eavSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param LoggerInterface $logger
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $moduleDataSetup,
        LoggerInterface $logger
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->logger = $logger;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Function apply()
     *
     * {@inheritdoc}
     */
    public function apply(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        try {
            $eavSetup->addAttribute(
                Product::ENTITY,
                self::ATTRIBUTE_CODE,
                [
                    'group' => 'Product Details',
                    'type' => 'int',
                    'sort_order' => 102,
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Is Featured',
                    'input' => 'boolean',
                    'class' => '',
                    'source' => Boolean::class,
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => '',
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => true,
                    'unique' => false,
                    'apply_to' => 'simple,configurable,virtual,bundle,downloadable'
                ]
            );
        } catch (LocalizedException|\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * Function getDependencies()
     *
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * Function getAliases()
     *
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * Function revert()
     *
     * {@inheritdoc}
     */
    public function revert(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->removeAttribute(Product::ENTITY, self::ATTRIBUTE_CODE);

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
