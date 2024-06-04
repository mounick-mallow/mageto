<?php
/**
 * LuxuryUnlimited_WorkerStores
 *
 * @copyright   Copyright (c) 2023
 */
namespace LuxuryUnlimited\WorkerStores\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class WorkerStores extends AbstractDb
{
    protected  $workerTable;

    protected function _construct()
    {
        $this->_init('workers_store_mapping', 'map_id');
        $this->workerTable = $this->getTable('workers_store_mapping');
    }


    /**
     * @param $storeCode
     * @return mixed
     */
    public function isValidStore($storeCode)
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()
            ->from($this->workerTable, 'worker_name')
            ->where('store_code = :store_code');
        $binds = ['store_code' => $storeCode];

        return $adapter->fetchOne($select, $binds);
    }
}