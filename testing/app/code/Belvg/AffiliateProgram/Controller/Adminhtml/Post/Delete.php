<?php

namespace Belvg\AffiliateProgram\Controller\Adminhtml\Post;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Belvg\AffiliateProgram\Model\ResourceModel\Post\CollectionFactory;

class Delete extends Action  implements HttpPostActionInterface
{
    public Http $http;

    public Filter $filter;

    public CollectionFactory $collectionFactory;

    public function __construct(
        Context $context,
        Http $http,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->http = $http;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        if ($collectionSize) {
            foreach ($collection as $item) {
                $item->delete();
            }
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
