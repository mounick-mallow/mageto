<?php

namespace Belvg\ReturnOrders\Model;

use Belvg\ReturnOrders\Api\ReturnListInterface;
use Dynamic\Orderreturn\Model\Orderreturn;

class ReturnItemsList implements ReturnListInterface
{
    public Orderreturn $orderReturnCollection;

    /**
     *
     * @param Orderreturn $orderreturn
     */
    public function __construct(Orderreturn $orderreturn)
    {
        $this->orderReturnCollection = $orderreturn;
    }

    /**
     *
     * @return array|\Belvg\ReturnOrders\Api\Data\ReturnListItemDataInterface[]
     */
    public function getItemsList(): array
    {
        $ordersCollection = $this->orderReturnCollection->getCollection();
        $ordersCollection->setOrder('orderreturn_id ', 'DESC');

        return !empty($ordersCollection->getItems()) ?
            $ordersCollection->getItems() : [];
    }
}
