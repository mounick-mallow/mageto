<?php

namespace Belvg\AffiliateProgram\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;

class Index extends Action implements HttpGetActionInterface
{
    public function execute(): Page
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Belvg_AffiliateProgram::post');
        $resultPage->getConfig()->getTitle()->prepend(__('Affiliate Posts'));

        return $resultPage;
    }
}
