<?php
/**
 * Copyright © MagePal LLC. All rights reserved.
 * See COPYING.txt for license details.
 * http://www.magepal.com | support@magepal.com
 */
namespace MagePal\GmailSmtpApp\Plugin\Mail\Template;

use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\Mail\Template\TransportBuilderByStore;
use MagePal\GmailSmtpApp\Model\Store;

class TransportBuilderByStorePlugin
{
    /**
     * @var Store
     */
    protected $storeModel;

    /**
     * @var SenderResolverInterface
     */
    private $senderResolver;

    /**
     * @param Store $storeModel
     * @param SenderResolverInterface $senderResolver
     */
    public function __construct(
        Store $storeModel,
        SenderResolverInterface $senderResolver
    ) {
        $this->storeModel = $storeModel;
        $this->senderResolver = $senderResolver;
    }

    /**
     * Before Set From By Store
     *
     * @param TransportBuilderByStore $subject
     * @param string|array $from
     * @param int|null $store
     * @return array
     * @throws MailException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSetFromByStore(
        TransportBuilderByStore $subject,
        $from,
        $store
    ) {
        if (!$this->storeModel->getStoreId()) {
            $this->storeModel->setStoreId($store);
        }

        $email = $this->senderResolver->resolve($from, $store);
        $this->storeModel->setFrom($email);

        return [$from, $store];
    }
}
