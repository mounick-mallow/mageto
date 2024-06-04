<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ethnic\Base\Observer;

use Magento\Captcha\Helper\Data;
use Magento\Captcha\Observer\CaptchaStringResolver;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class CheckForgotpassword overrides
 * \Magento\Captcha\Observer\CheckForgotpasswordObserver
 */
class CheckForgotpassword implements ObserverInterface
{
    /**
     * @var Data
     */
    protected Data $helper;

    /**
     * @var ActionFlag
     */
    protected ActionFlag $actionFlag;

    /**
     * @var ManagerInterface
     */
    protected ManagerInterface $messageManager;

    /**
     * @var RedirectInterface
     */
    protected RedirectInterface $redirect;

    /**
     * @var CaptchaStringResolver
     */
    protected CaptchaStringResolver $captchaStringResolver;

    /**
     * @var RequestInterface
     */
    private RequestInterface $_request;

    /**
     * @var Http
     */
    private Http $_response;

    /**
     * @param Data                  $helper
     * @param ActionFlag            $actionFlag
     * @param ManagerInterface      $messageManager
     * @param RedirectInterface     $redirect
     * @param Http                  $response
     * @param RequestInterface      $request
     * @param CaptchaStringResolver $captchaStringResolver
     */
    public function __construct(
        Data $helper,
        ActionFlag $actionFlag,
        ManagerInterface $messageManager,
        RedirectInterface $redirect,
        Http $response,
        RequestInterface $request,
        CaptchaStringResolver $captchaStringResolver
    ) {
        $this->helper = $helper;
        $this->actionFlag = $actionFlag;
        $this->messageManager = $messageManager;
        $this->redirect = $redirect;
        $this->captchaStringResolver = $captchaStringResolver;
        $this->_request = $request;
        $this->_response = $response;
    }

    /**
     * Check Captcha On Forgot Password Page
     *
     * @param Observer $observer
     *
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $formId = 'user_forgotpassword';
        $captchaModel = $this->helper->getCaptcha($formId);
        if ($captchaModel->isRequired()) {
            if (!$captchaModel->isCorrect(
                $this->captchaStringResolver->resolve($this->_request, $formId)
            )) {
                $this->messageManager->addErrorMessage(__('Incorrect CAPTCHA'));
                $this->actionFlag->set('', ActionInterface::FLAG_NO_DISPATCH, true);
                $this->redirect->redirect($this->_response, '*/*/forgotpassword');
            }
        }

        return $this;
    }
}
