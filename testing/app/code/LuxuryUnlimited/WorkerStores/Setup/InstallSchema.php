<?php
namespace LuxuryUnlimited\WorkerStores\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $table = $installer->getConnection()
            ->newTable($installer->getTable('workers_store_mapping'))
            ->addColumn(
                'map_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )
            ->addColumn(
                'worker_name',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false, 'default' => ''],
                'Worker #'
            )->addColumn(
                'instance_name',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true, 'default' => ''],
                'Instance name like 1-1,2-1'
            )
            ->addColumn(
                'store_code',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false, 'default' => ''],
                'store code'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Created At'
            )->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Updated At'
            )->setComment('Contains the mapping of stores and worker instances');



        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}