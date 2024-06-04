<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Smartwave\Porto\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class observer SavePortoLicense on event
 * "admin_system_config_changed_section_porto_license"
 */
class SavePortoLicense implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
    protected ManagerInterface $messageManager;

    /**
     * Construct
     *
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        ManagerInterface $messageManager,
    ) {
        $this->messageManager = $messageManager;
        ;
    }

    /**
     * Log out user and redirect to new admin custom url
     *
     * @param Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.ExitExpression)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(Observer $observer): void
    {
        $this->messageManager->getMessages(true);
        $this->messageManager->addSuccessMessage(
            'Smartwave Porto Theme was activated!'
        );
    }
}
