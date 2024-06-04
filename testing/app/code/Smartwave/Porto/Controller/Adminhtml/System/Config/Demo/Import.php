<?php
/**
 * * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Smartwave\Porto\Controller\Adminhtml\System\Config\Demo;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Smartwave\Porto\Controller\Adminhtml\System\Config\Demo;
use Smartwave\Porto\Model\Import\Demo as ImportDemo;

/**
 * Class controller Import
 */
class Import extends Demo
{
    /**
     * @var JsonFactory
     */
    protected JsonFactory $resultJsonFactory;

    /**
     * Construct
     *
     * @param Context $context
     * @param ImportDemo $importDemo
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        ImportDemo $importDemo,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context, $importDemo);
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Check whether vat is valid
     *
     * @return Json
     */
    public function execute(): Json
    {
        $result = $this->_import();
        $resultJson = $this->resultJsonFactory->create();

        return $resultJson->setData([
            'valid' => (int)$result->getIsValid(),
            'import_path' => $result->getImportPath(),
            'message' => $result->getRequestMessage(),
        ]);
    }
}
