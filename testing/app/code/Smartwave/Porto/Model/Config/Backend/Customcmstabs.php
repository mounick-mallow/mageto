<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Smartwave\Porto\Model\Config\Backend;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Backend for serialized array data
 */
class Customcmstabs extends Value
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * Construct
     *
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param SerializerInterface $serializer
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        SerializerInterface $serializer,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );
        $this->serializer = $serializer;
    }

    /**
     * Process data after load
     *
     * @return Customcmstabs
     */
    protected function _afterLoad(): Customcmstabs
    {
        $value = $this->getValue();
        $arr =  $this->serializer->unserialize($value);
        $this->setValue($arr);

        return $this;
    }

    /**
     * Prepare data before save
     *
     * @return Customcmstabs
     */
    public function beforeSave(): Customcmstabs
    {
        $value = $this->getValue();
        $arr =  $this->serializer->serialize($value);
        $this->setValue($arr);

        return $this;
    }
}
