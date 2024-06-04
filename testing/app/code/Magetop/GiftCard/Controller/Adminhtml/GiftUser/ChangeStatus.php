<?php

namespace Magetop\GiftCard\Controller\Adminhtml\GiftUser;

use Magento\Framework\App\ResponseInterface;
use Magento\Ui\Component\MassAction\Filter;

/**
 * @SuppressWarnings(PHPMD.AllPurposeAction)
 */
class ChangeStatus extends \Magento\Backend\App\Action
{
    /**
     * @var \Magetop\GiftCard\Model\ResourceModel\GiftUser\CollectionFactory
     */
    protected $_giftUserCollection;

    /**
     * @var Filter
     */
    protected $_filter;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magetop\GiftCard\Model\ResourceModel\GiftUser\CollectionFactory $giftUserCollection
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magetop\GiftCard\Model\ResourceModel\GiftUser\CollectionFactory $giftUserCollection,
        Filter $filter
    ) {
        parent::__construct($context);
        $this->_giftUserCollection = $giftUserCollection;
        $this->_filter = $filter;
    }

    /**
     * Execute
     *
     * @return ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $GiftCardIds = $this->_filter->getCollection($this->_giftUserCollection->create());
        try {
            foreach ($GiftCardIds as $GiftCardId) {
                $GiftCardId->setIsActive($this->getRequest()->getParam('entity_id'))->save();
            }
            $this->messageManager->addSuccess(
                __(
                    'Total of %1 record(s) were successfully updated',
                    $GiftCardIds->getSize()
                )
            );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $resultRedirect->setPath('giftcard/giftuser/index');
        return $resultRedirect;
    }

    /**
     * Check permission via ACL resource
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magetop_GiftCard::giftuser_index');
    }
}
