<?php

namespace Smartwave\Porto\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\DataObject;
use Smartwave\Porto\Model\Import\Demo as ImportDemo;

/**
 * Abstract class controller Demo
 */
abstract class Demo extends Action
{
    /**
     * @var ImportDemo
     */
    private ImportDemo $importDemo;

    /**
     * @param Context $context
     * @param ImportDemo $importDemo
     */
    public function __construct(
        Context $context,
        ImportDemo $importDemo
    ) {
        parent::__construct($context);
        $this->importDemo = $importDemo;
    }

    /**
     * Overwritten parent function _import()
     *
     * @return DataObject
     */
    protected function _import(): DataObject
    {
        return $this->importDemo->importDemo(
            $this->getRequest()->getParam('demo_version'),
            $this->getRequest()->getParam('current_store'),
            $this->getRequest()->getParam('current_website')
        );
    }
}
