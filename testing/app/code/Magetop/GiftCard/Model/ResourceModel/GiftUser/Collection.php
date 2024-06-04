<?php

namespace Magetop\GiftCard\Model\ResourceModel\GiftUser;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Magetop Marketplace ResourceModel Seller collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'giftuserid';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Magetop\GiftCard\Model\GiftUser::class,
            \Magetop\GiftCard\Model\ResourceModel\GiftUser::class
        );
        $this->_map['fields']['giftuserid'] = 'main_table.giftuserid';
        $this->_map['fields']['created_at'] = 'main_table.created_at';
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        return $this;
    }
}
