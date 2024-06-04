<?php

namespace Belvg\AffiliateProgram\Cron;

use Belvg\AffiliateProgram\Model\ResourceModel\Post\CollectionFactory;
use SebastianBergmann\Diff\Exception;
use Shellpea\ERP\Client\Client as ErpClient;
use Psr\Log\LoggerInterface;

class ErpSend
{
    public CollectionFactory $collectionFactory;

    public  ErpClient $erpClient;

    public LoggerInterface $logger;

    /**
     *
     * @param CollectionFactory $collectionFactory
     * @param ErpClient $erpClient
     * @param LoggerInterface $logger
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ErpClient $erpClient,
        LoggerInterface $logger
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->erpClient = $erpClient;
        $this->logger = $logger;
    }

    public function execute(): void
    {
        $collection = $this->getAffiliatesCollection();
        $this->send($collection);
    }

    public function getAffiliatesCollection(): Belvg\AffiliateProgram\Model\ResourceModel\Post\CollectionFactory
    {
        /** @var Belvg\AffiliateProgram\Model\ResourceModel\Post\CollectionFactory  $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('sent', 0);

        return $collection;
    }

    public function send($collection): void
    {
        foreach ($collection->getItems() as $item) {
            $params = [
                "first_name" => $item->getFirstName(),
                "last_name" => $item->getLastName(),
                "phone" => $item->getPhone(),
                "emailaddress" => $item->getEmail(),
                "street_address" => $item->getStreetAddress1(),
                "street_address2" => $item->getStreetAddress2(),
                "city" => $item->getCity(),
                "postcode" => $item->getPostCode(),
                "country" => $item->getCountry(),
                "url" => $item->getWebsiteUrl(),
                "unique_visitors_per_month" => $item->getVisitors(),
                "page_views_per_month" => $item->getViews(),
            ];

            try {
                $result = $this->erpClient->addAffiliate($params)[0];
            } catch (Exception $e) {
                $this->logger->info('Error during sending affiliate request to the ERP');
                $this->logger->error($e->getMessage());
            }
        }
    }
}
