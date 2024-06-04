<?php

namespace Magetop\GiftCard\Model;

use Magento\Framework\Model\AbstractModel;

class GiftDetail extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Construct
     */

    protected function _construct()
    {
        $this->_init(\Magetop\GiftCard\Model\ResourceModel\GiftDetail::class);
    }
}
