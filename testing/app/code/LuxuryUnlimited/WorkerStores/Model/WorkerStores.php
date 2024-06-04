<?php
/**
 * LuxuryUnlimited_WorkerStores
 *
 * @copyright   Copyright (c) 2023
 */
namespace LuxuryUnlimited\WorkerStores\Model;

use LuxuryUnlimited\WorkerStores\Model\ResourceModel\WorkerStores as WorkerStoresResourceModel;

use Magento\Framework\Model\AbstractModel;

class WorkerStores extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(WorkerStoresResourceModel::class);
    }
}