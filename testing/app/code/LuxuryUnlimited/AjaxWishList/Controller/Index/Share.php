<?php

namespace LuxuryUnlimited\AjaxWishList\Controller\Index;

use BelVG\GuestWishlist\Controller\WishlistProviderInterface as GuestWishlistProviderInterface;
use Magento\Framework\App\Action;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Result\Layout as ResultLayout;

use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Share
 *
 * Luxury Unlimited Ajax WishList Share
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.AllPurposeAction)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
class Share extends \Magento\Framework\App\Action\Action
{
    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Magento\Wishlist\Model\Config
     */
    protected $_wishlistConfig;

    /**
     * @var \Magento\Wishlist\Controller\WishlistProviderInterface
     */
    protected $wishlistProvider;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var GuestWishlistProviderInterface
     */
    protected $guestWishlistProvider;

    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var \Magento\Customer\Helper\View
     */
    protected $_customerHelperView;

    /**
     * Share constructor.
     * @param Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider
     * @param \Magento\Wishlist\Model\Config $wishlistConfig
     * @param \Magento\Customer\Helper\View $customerHelperView
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param ResultLayout $resultFactory
     * @param GuestWishlistProviderInterface $guestWishlistProvider
     * @param JsonFactory $resultJsonFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param Escaper|null $escaper
     */
    public function __construct(
        Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider,
        \Magento\Wishlist\Model\Config $wishlistConfig,
        \Magento\Customer\Helper\View $customerHelperView,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\View\Result\Layout $resultFactory,
        GuestWishlistProviderInterface $guestWishlistProvider,
        JsonFactory $resultJsonFactory,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        Escaper $escaper = null
    ) {
        $this->_customerSession = $customerSession;
        $this->wishlistProvider = $wishlistProvider;
        $this->guestWishlistProvider = $guestWishlistProvider;
        $this->_wishlistConfig = $wishlistConfig;
        $this->_transportBuilder = $transportBuilder;
        $this->_customerHelperView = $customerHelperView;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->resultFactory = $resultFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->escaper = $escaper ?? ObjectManager::getInstance()->get(
            Escaper::class
        );
        parent::__construct($context);
    }

    /**
     * Adding new item
     *
     * @return ResultInterface
     * @throws NotFoundException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function execute()
    {
        $senderName = $senderEmail = '';
        if ($this->_customerSession->isLoggedIn()) {
            $wishlist = $this->wishlistProvider->getWishlist();
        } else {
            $wishlist = $this->guestWishlistProvider->getWishlist();
            $senderEmail = $this->getRequest()->getParam('email');
            $senderName = $this->getRequest()->getParam('username');
        }

        $sharingLimit = $this->_wishlistConfig->getSharingEmailLimit();
        $textLimit = $this->_wishlistConfig->getSharingTextLimit();
        $emailsLeft = $sharingLimit - $wishlist->getShared();

        $emails = $this->getRequest()->getParam('emails');
        $emails = empty($emails) ? $emails : explode(',', $emails);

        $error = false;
        $message = (string)$this->getRequest()->getParam('message');
        if (strlen($message) > $textLimit) {
            $error = __('Message length must not exceed %1 symbols', $textLimit);
        } else {
            $message = nl2br((string) $this->escaper->escapeHtml($message));
            if (empty($emails)) {
                $error = __('Please enter an email address.');
            } else {
                if (count($emails) > $emailsLeft) {
                    $error = __('Maximum of %1 emails can be sent.', $emailsLeft);
                } else {
                    foreach ($emails as $index => $email) {
                        $email = $email !== null ? trim($email) : '';
                        if (!\Zend_Validate::is($email, \Magento\Framework\Validator\EmailAddress::class)) { // @codingStandardsIgnoreLine
                            $error = __('Please enter a valid email address.');
                            break;
                        }
                        $emails[$index] = $email;
                    }
                }
            }
        }
        $data = [];
        if ($error) {
            $data['type'] = 'error';
            $data['message'] = $error;
        } else {
            /** @var \Magento\Framework\View\Result\Layout $resultLayout */
            $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
            $resultLayout->addHandle('wishlist_email_items');
            $this->inlineTranslation->suspend();

            $sent = 0;

            try {
                $emails = array_unique($emails);
                $sharingCode = $wishlist->getSharingCode();
                if ($this->_customerSession->isLoggedIn()) {
                    $customer = $this->_customerSession->getCustomerDataObject();
                    $customerName = $this->_customerHelperView->getCustomerName($customer);
                    try {
                        foreach ($emails as $email) {
                            $transport = $this->_transportBuilder->setTemplateIdentifier(
                                $this->scopeConfig->getValue(
                                    'wishlist/email/email_template',
                                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                                )
                            )->setTemplateOptions(
                                [
                                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                                    'store' => $this->storeManager->getStore()->getStoreId(),
                                ]
                            )->setTemplateVars(
                                [
                                    'customer' => $customer,
                                    'customerName' => $customerName,
                                    'salable' => $wishlist->isSalable() ? 'yes' : '',
                                    'items' => $this->getWishlistItems($resultLayout),
                                    'viewOnSiteLink' => $this->_url->getUrl('*/shared/index', ['code' => $sharingCode]),
                                    'message' => $message,
                                    'store' => $this->storeManager->getStore(),
                                ]
                            )->setFrom(
                                $this->scopeConfig->getValue(
                                    'wishlist/email/email_identity',
                                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                                )
                            )->addTo(
                                $email
                            )->getTransport();

                            /**
                             * @TODO: As the emails are not verified, we will verify this after applying the UI changes
                             */
                            /**
                             * $transport->sendMessage();
                             */

                            $sent++;
                        }
                    } catch (\Exception $e) {
                        $wishlist->setShared($wishlist->getShared() + $sent);
                        $wishlist->save();
                        throw $e;
                    }
                } else {
                    try {
                        foreach ($emails as $email) {
                            $transport = $this->_transportBuilder->setTemplateIdentifier(
                                $this->scopeConfig->getValue(
                                    'wishlist/email/email_template',
                                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                                )
                            )->setTemplateOptions(
                                [
                                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                                    'store' => $this->storeManager->getStore()->getStoreId(),
                                ]
                            )->setTemplateVars(
                                [
                                    'customer' => $senderName,
                                    'customerName' => $senderName . '(' . $senderEmail . ')',
                                    'salable' => $wishlist->isSalable() ? 'yes' : '',
                                    'items' => $this->getWishlistItems($resultLayout),
                                    'viewOnSiteLink' => $this->_url
                                        ->getUrl('*/shared/index', ['code' => $sharingCode]),
                                    'message' => $message,
                                    'store' => $this->storeManager->getStore(),
                                ]
                            )->setFrom(
                                $this->scopeConfig->getValue(
                                    'wishlist/email/email_identity',
                                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                                )
                            )->addTo(
                                $email
                            )->getTransport();

                            /**
                             * @TODO: As the emails are not verified, we will verify this after applying the UI changes
                             */
                            /**
                             * $transport->sendMessage();
                             */

                            $sent++;
                        }
                    } catch (\Exception $e) {
                        $wishlist->setShared($wishlist->getShared() + $sent);
                        $wishlist->save();
                        throw $e;
                    }
                }

                $wishlist->setShared($wishlist->getShared() + $sent);
                $wishlist->save();

                $this->inlineTranslation->resume();
                $this->_eventManager->dispatch('wishlist_share', ['wishlist' => $wishlist]);
                $successMessage = __('Thank you for sharing your wishlist with your friends and family.
                Yours selections will surely make their day brighter.');

                $data['type'] = 'success';
                $data['message'] = $successMessage;
            } catch (\Exception $e) {
                $error = $e->getMessage();
                $data['type'] = 'error';
                $data['message'] = $error;
            }
        }
        $result = $this->_resultJsonFactory->create();
        $result->setData(['data' => $data]);
        return $result;
    }

    /**
     * Retrieve wishlist items content (html)
     *
     * @param \Magento\Framework\View\Result\Layout $resultLayout
     * @return string
     */
    protected function getWishlistItems(ResultLayout $resultLayout)
    {
        return $resultLayout->getLayout()
            ->getBlock('wishlist.email.items')
            ->toHtml();
    }
}
