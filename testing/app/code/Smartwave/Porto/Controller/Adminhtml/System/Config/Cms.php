<?php

namespace Smartwave\Porto\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\DataObject;
use Smartwave\Porto\Model\Import\Cms as ImportCms;

/**
 * Abstract class controller Cms
 */
abstract class Cms extends Action
{
    /**
     * @var ImportCms
     */
    private ImportCms $importCms;

    /**
     * Construct
     *
     * @param Context $context
     * @param ImportCms $importCms
     */
    public function __construct(
        Context $context,
        ImportCms $importCms
    ) {
        parent::__construct($context);
        $this->importCms = $importCms;
    }

    /**
     * Overwritten parent function _import()
     *
     * @return DataObject
     */
    protected function _import(): DataObject
    {
        return $this->importCms->importCms(
            $this->getRequest()->getParam('import_type'),
            $this->getRequest()->getParam('demo_version')
        );
    }
}
