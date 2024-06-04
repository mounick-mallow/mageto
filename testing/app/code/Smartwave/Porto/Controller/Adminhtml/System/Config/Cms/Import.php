<?php
/**
 * * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Smartwave\Porto\Controller\Adminhtml\System\Config\Cms;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Smartwave\Porto\Controller\Adminhtml\System\Config\Cms;
use Smartwave\Porto\Model\Import\Cms as ImportCms;

/**
 * Class controller Import
 */
class Import extends Cms
{
    /**
     * @var JsonFactory
     */
    protected JsonFactory $resultJsonFactory;

    /**
     * Construct
     *
     * @param Context $context
     * @param ImportCms $importCms
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        ImportCms $importCms,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context, $importCms);
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Check whether vat is valid
     *
     * @return Json
     */
    public function execute()
    {
        $result = $this->_import();
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData([
            'valid' => (int)$result->getData('is_valid'),
            'import_path' => $result->getData('import_path'),
            'message' => $result->getData('request_message'),
        ]);
    }
}
