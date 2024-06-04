<?php
/**
 * Copyright © MagePal LLC. All rights reserved.
 * See COPYING.txt for license details.
 * http://www.magepal.com | support@magepal.com
 */

namespace MagePal\GmailSmtpApp\Model\ZendMailOne;

use Exception;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Phrase;
use MagePal\GmailSmtpApp\Helper\Data;
use MagePal\GmailSmtpApp\Model\Store;
use Laminas\Mail\Transport\Sendmail;
use Laminas\Mail\Exception as Lamina_Exception;
use Laminas\Mail\Transport\Smtp as Laminas_SMTP;

/**
 * Class Smtp
 * For Magento <= 2.2.7
 */

class Smtp extends Laminas_SMTP
{
    /**
     * @var Data
     */
    protected $dataHelper;

    /**
     * @var Store
     */
    protected $storeModel;

    /**
     * @var $_name
     */
    public $_name;

    /**
     * @var $_port
     */
    public $_port;

    /**
     * @var $_auth
     */
    public $_auth;

    /**
     * @var $_host
     */
    public $_host;

    /**
     * @var $_config
     */
    public $_config;

    /**
     * @param Data $dataHelper
     * @param Store $storeModel
     */
    public function __construct(
        Data $dataHelper,
        Store $storeModel
    ) {
        $this->dataHelper = $dataHelper;
        $this->storeModel = $storeModel;
    }

    /**
     * Set Data Helper
     *
     * @param Data $dataHelper
     * @return $this
     */
    public function setDataHelper(Data $dataHelper)
    {
        $this->dataHelper = $dataHelper;
        return $this;
    }

    /**
     * SetStoreModel
     *
     * @param Store $storeModel
     * @return Smtp
     */
    public function setStoreModel(Store $storeModel)
    {
        $this->storeModel = $storeModel;
        return $this;
    }

    /**
     * Send Smtp Message
     *
     * @param MessageInterface $message
     * @throws MailException
     * @throws Zend_Mail_Exception
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function sendSmtpMessage(
        MessageInterface $message
    ) {
        $dataHelper = $this->dataHelper;
        $dataHelper->setStoreId($this->storeModel->getStoreId());

        if ($message instanceof Sendmail) {
            if ($message->getDate() === null) {
                $message->setDate();
            }
        }

        //Set reply-to path
        switch ($dataHelper->getConfigSetReturnPath()) {
            case 1:
                $returnPathEmail = $message->getFrom() ?: $this->getFromEmailAddress();
                break;
            case 2:
                $returnPathEmail = $dataHelper->getConfigReturnPathEmail();
                break;
            default:
                $returnPathEmail = null;
                break;
        }

        if ($returnPathEmail !== null && $dataHelper->getConfigSetReturnPath()) {
            $message->setReturnPath($returnPathEmail);
        }

        if ($message->getReplyTo() === null && $dataHelper->getConfigSetReplyTo()) {
            $message->setReplyTo($returnPathEmail);
        }

        //Set from address
        switch ($dataHelper->getConfigSetFrom()) {
            case 1:
                $setFromEmail = $message->getFrom() ?: $this->getFromEmailAddress();
                break;
            case 2:
                $setFromEmail = $dataHelper->getConfigCustomFromEmail();
                break;
            default:
                $setFromEmail = null;
                break;
        }
        if ($setFromEmail !== null && $dataHelper->getConfigSetFrom()) {
            $message->clearFrom();
            $message->setFrom($setFromEmail);
        }

        if (!$message->getFrom()) {
            $result = $this->storeModel->getFrom();
            $message->setFrom($result['email'], $result['name']);
        }

        //set config
        $smtpConf = [
            'name' => $dataHelper->getConfigName(),
            'port' => $dataHelper->getConfigSmtpPort(),
        ];

        $auth = strtolower($dataHelper->getConfigAuth());
        if ($auth != 'none') {
            $smtpConf['auth'] = $auth;
            $smtpConf['username'] = $dataHelper->getConfigUsername();
            $smtpConf['password'] = $dataHelper->getConfigPassword();
        }

        $ssl = $dataHelper->getConfigSsl();
        if ($ssl != 'none') {
            $smtpConf['ssl'] = $ssl;
        }

        $smtpHost = $dataHelper->getConfigSmtpHost();
        $this->initialize($smtpHost, $smtpConf);

        try {
            parent::send($message);
        } catch (\Exception $e) {
            throw new MailException(
                new Phrase($e->getMessage()),
                $e
            );
        }
    }

    /**
     * Get From Email Address
     *
     * @return string
     */
    public function getFromEmailAddress()
    {
        $result = $this->storeModel->getFrom();
        return $result['email'];
    }

    /**
     * Initialize
     *
     * @param string $host
     * @param array $config
     */
    public function initialize($host = '127.0.0.1', array $config = [])
    {
        if (isset($config['name'])) {
            $this->_name = $config['name'];
        }
        if (isset($config['port'])) {
            $this->_port = $config['port'];
        }
        if (isset($config['auth'])) {
            $this->_auth = $config['auth'];
        }

        $this->_host = $host;
        $this->_config = $config;
    }
}
