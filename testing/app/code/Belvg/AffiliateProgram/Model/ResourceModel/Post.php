<?php

namespace Belvg\AffiliateProgram\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Post extends AbstractDb
{
    private const TABLE_NAME = 'belvg_affiliate_post';

    private const PRIMARY_KEY = 'affiliate_id';
    public function _construct()
    {
        $this->_init(self::TABLE_NAME, self::PRIMARY_KEY);
    }
}
