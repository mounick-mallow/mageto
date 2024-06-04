<?php
/**
 * LuxuryUnlimited_WorkerStores
 *
 * @copyright   Copyright (c) 2023
 */
namespace LuxuryUnlimited\WorkerStores\Model\ResourceModel\WorkerStores;

use Devhooks\WorkerStores\Model\WorkerStores as WorkerStoresModel;
use Devhooks\WorkerStores\Model\ResourceModel\WorkerStores as WorkerStoresResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            WorkerStoresModel::class,
            WorkerStoresResourceModel::class
        );
    }
}