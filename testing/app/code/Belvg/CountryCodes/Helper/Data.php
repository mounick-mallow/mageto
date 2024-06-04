<?php

namespace Belvg\CountryCodes\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ResourceConnection;


class Data extends  AbstractHelper
{
    public ResourceConnection $resourceConnection;

    public function __construct(
        Context $context,
        ResourceConnection $resourceConnection,
    ) {
        $this->resourceConnection = $resourceConnection;
        parent::__construct($context);
    }

    /**
     *
     * @param string $countryId
     * @return string|null
     */
    public function getPhoneCode(string $countryId): ?string
    {
        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName('msp_tfa_country_codes');
        $select = $connection->select()->from($tableName, ['dial_code'])->where('code = ?', $countryId);
        return $connection->fetchOne($select);
    }
}
