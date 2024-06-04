<?php
/**
 * Ethnic_Base
 *
 * @author RuslanP <ruslan.p@ideainyou.com>
 *
 * @copyright Copyright (c) 2023 IdeaInYou
 */
namespace Ethnic\Base\Controller\Account;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class controller Success
 */
class Success implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected PageFactory $pageFactory;

    /**
     * @param PageFactory $pageFactory
     */
    public function __construct(
        PageFactory $pageFactory
    ) {
        $this->pageFactory = $pageFactory;
    }

    /**
     * Function execute
     *
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute()
    {
        return $this->pageFactory->create();
    }
}
