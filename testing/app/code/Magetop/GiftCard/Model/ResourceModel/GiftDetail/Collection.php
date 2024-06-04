<?php

namespace Magetop\GiftCard\Model\ResourceModel\GiftDetail;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Magetop Marketplace ResourceModel Seller collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Magetop\GiftCard\Model\GiftDetail::class,
            \Magetop\GiftCard\Model\ResourceModel\GiftDetail::class
        );
        $this->_map['fields']['gift_id'] = 'main_table.gift_id';
        $this->_map['fields']['created_at'] = 'main_table.created_at';
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        return $this;
    }
}
