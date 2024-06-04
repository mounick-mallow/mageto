<?php

namespace Belvg\ReturnOrders\Api;


interface ReturnListInterface
{
    /**
     *
     * @return \Belvg\ReturnOrders\Api\Data\ReturnListItemDataInterface[]
     */
    public function getItemsList(): array;
}
