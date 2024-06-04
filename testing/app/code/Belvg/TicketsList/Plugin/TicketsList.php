<?php

namespace Belvg\TicketsList\Plugin;

use Magento\Framework\App\Request\Http;
use Dynamic\Mytickets\Model\ResourceModel\Mytickets\CollectionFactory;

class TicketsList
{
    public Http $http;

    public CollectionFactory $collectionFactory;

    /**
     *
     * @param Http $http
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Http $http,
        CollectionFactory $collectionFactory
    ) {
        $this->http = $http;
        $this->collectionFactory = $collectionFactory;
    }
    public function afterGetMyTicketsCollection($subject, $result)
    {
        if ($result != null) {
            return $result;
        }

        $email = $this->http->getParam('email');
        if (empty($email)) {
            return  $result;
        }

        return
            $this->collectionFactory->create()
                ->addFieldToFilter('email', ['eq' => $email]);
    }
}
