<?php
/**
 * Ethnic_Base
 *
 * @author RuslanP <ruslan.p@ideainyou.com>
 *
 * @copyright Copyright (c) 2023 IdeaInYou
 */

namespace Ethnic\Base\Controller\Account;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\AccountManagement;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\SecurityViolationException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Phrase;
use Magento\Framework\Validator\EmailAddress;
use Psr\Log\LoggerInterface;

/**
 * ForgotPasswordPost controller
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ForgotPasswordPost implements HttpPostActionInterface
{
    /**
     * @var AccountManagementInterface
     */
    protected AccountManagementInterface $customerAccountManagement;

    /**
     * @var Escaper
     */
    protected Escaper $escaper;

    /**
     * @var Session
     */
    protected Session $session;

    /**
     * @var RedirectFactory
     */
    private RedirectFactory $_redirectFactory;

    /**
     * @var ManagerInterface
     */
    protected ManagerInterface $messageManager;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $_logger;

    /**
     * @var RequestInterface
     */
    private RequestInterface $_request;

    /**
     * @var EmailAddress
     */
    private EmailAddress $_emailAddress;

    /**
     * @param Session                    $session
     * @param EmailAddress               $emailAddress
     * @param AccountManagementInterface $customerAccountManagement
     * @param RedirectFactory            $redirectFactory
     * @param ManagerInterface           $messageManager
     * @param RequestInterface           $request
     * @param Escaper                    $escaper
     * @param LoggerInterface            $logger
     */
    public function __construct(
        Session $session,
        EmailAddress $emailAddress,
        AccountManagementInterface $customerAccountManagement,
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager,
        RequestInterface $request,
        Escaper $escaper,
        LoggerInterface $logger
    ) {
        $this->session = $session;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->escaper = $escaper;
        $this->_redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->_logger = $logger;
        $this->_request = $request;
        $this->_emailAddress = $emailAddress;
    }

    /**
     * Forgot customer password action
     *
     * @return Redirect
     * @throws \Laminas\Validator\Exception\InvalidArgumentException
     */
    public function execute()
    {
        $resultRedirect = $this->_redirectFactory->create();
        $email = (string)$this->_request->getPost('email');
        if ($email) {
            if (!$this->_emailAddress->isValid($email)) {
                $this->session->setForgottenEmail($email);
                $this->messageManager->addErrorMessage(
                    __('The email address is incorrect. 
                        Verify the email address and try again.')
                );
                return $resultRedirect->setPath('*/*/forgotpassword');
            }

            try {
                $this->customerAccountManagement->initiatePasswordReset(
                    $email,
                    AccountManagement::EMAIL_RESET
                );
            } catch (NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
                $this->_logger->error(
                    $exception->getMessage(),
                    [
                        'email' => $email
                    ]
                );
            } catch (SecurityViolationException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
                $this->_logger->error(
                    $exception->getMessage(),
                    [
                        'email' => $email
                    ]
                );
                return $resultRedirect->setPath('*/*/forgotpassword');
            } catch (\Exception $exception) {
                $this->messageManager->addExceptionMessage(
                    $exception,
                    __('We\'re unable to send the password reset email.')
                );
                $this->_logger->error(
                    $exception->getMessage(),
                    [
                        'email' => $email
                    ]
                );
                return $resultRedirect->setPath('*/*/forgotpassword');
            }
            $this->messageManager->addSuccessMessage(
                $this->getSuccessMessage($email)
            );
            return $resultRedirect->setPath(
                'customer/account/success?email=' . base64_encode($email)
            );
        } else {
            $this->messageManager->addErrorMessage(__('Please enter your email.'));
            return $resultRedirect->setPath('*/*/forgotpassword');
        }
    }

    /**
     * Retrieve success message
     *
     * @param string $email
     *
     * @return Phrase
     */
    protected function getSuccessMessage(string $email): Phrase
    {
        return __(
            'If there is an account associated with %1 you will receive an
             email with a link to reset your password',
            $this->escaper->escapeHtml($email)
        );
    }
}
