<?php

namespace Belvg\AffiliateProgram\Ui\Grid;

use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /** @var \Belvg\AffiliateProgram\Model\ResourceModel\Post\Collection  */
    public $collection;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Belvg\AffiliateProgram\Model\ResourceModel\Post\CollectionFactory $collection,
        array $meta = [],
        array $data = []
    ) {

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collection->create();
    }
}
