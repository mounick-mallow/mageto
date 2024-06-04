<?php
/**
 * Copyright © MagePal LLC. All rights reserved.
 * See COPYING.txt for license details.
 * http://www.magepal.com | support@magepal.com
 */

namespace MagePal\GmailSmtpApp\Model;

class Store
{
    /**
     * @var int|null
     */
    protected $store_id = null;

    /**
     * @var null
     */
    protected $from = null;

    /**
     * Get store Id
     *
     * @return int|null
     */
    public function getStoreId()
    {
        return $this->store_id;
    }

    /**
     * Set store id
     *
     * @param string|int $store_id
     * @return $this
     */
    public function setStoreId($store_id)
    {
        $this->store_id = $store_id;
        return $this;
    }

    /**
     * Get from
     *
     * @return string|array|null
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set from
     *
     * @param string|array|null $from
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }
}
