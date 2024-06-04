<?php

namespace Addon\Attributeupload\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Framework\Exception\LocalizedException;

class AttributeBeforeSave implements ObserverInterface
{

    /**
     * Execute
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this|void
     * @throws LocalizedException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $object = $observer->getData('object');
            if ($object instanceof Attribute &&
                $object->getAttributeId() === null &&
                $object->getFrontendInput() === 'file'
            ) {
                $object->setBackendModel(\Addon\Attributeupload\Model\ProductAttribute\Backend\FileUpload::class);
                $object->setFrontendModel(\Addon\Attributeupload\Model\ProductAttribute\Frontend\FileUpload::class);
                $object->setBackendType('varchar');
            }

            return $this;
        } catch (LocalizedException $e) {
            throw new LocalizedException(
                __('Observer not works on attribute')
            );
        }
    }
}
