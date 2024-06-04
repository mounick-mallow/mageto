<?php
/**
 * LuxuryUnlimited_AjaxTrackOrder
 *
 * @copyright   Copyright (c) 2023
 */

namespace LuxuryUnlimited\AjaxTrackOrder\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Class Track - Handles request to show order information
 *
 * Luxury Unlimited Ajax TrackOrder
 */
class Track extends Action implements HttpPostActionInterface
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * Track constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(Context $context, PageFactory $resultPageFactory, JsonFactory $resultJsonFactory)
    {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    /**
     * Handles ajax request for order track
     *
     * @return mixed
     */
    public function execute()
    {
        $result = $this->_resultJsonFactory->create();
        try {

            $resultPage = $this->_resultPageFactory->create();
            $orderId = $this->getRequest()->getParam('order-increment');
            $website = $this->getRequest()->getParam('website');
            $data = ['orderId' => $orderId, 'website' => $website];
            $block = $resultPage->getLayout()
                ->createBlock(\LuxuryUnlimited\AjaxTrackOrder\Block\Index\View::class)
                ->setTemplate('LuxuryUnlimited_AjaxTrackOrder::view.phtml')
                ->setData('data', $data)
                ->toHtml();
            if (!empty($block)) {
                $result->setData(["errors" => false, "html" => $block]);
            } else {
                $result->setData(["errors" => true, "html" => __("Invalid Order")]);
            }

            return $result;
        } catch (\Exception $e) {
            $result->setData(["errors" => true, "html" => __("Something went wrong")]);
            return $result;
        }
    }
}
