<?php
/**
 * Copyright © MagePal LLC. All rights reserved.
 * See COPYING.txt for license details.
 * http://www.magepal.com | support@magepal.com
 */

namespace MagePal\GmailSmtpApp\Plugin\Mail\Template;

use Magento\Framework\Mail\Template\TransportBuilder;
use MagePal\GmailSmtpApp\Model\Store;

class TransportBuilderPlugin
{

    /** @var Store */
    protected $storeModel;

    /**
     * @param Store $storeModel
     */
    public function __construct(
        Store $storeModel
    ) {
        $this->storeModel = $storeModel;
    }

    /**
     * Before Set Template Options
     *
     * @param TransportBuilder $subject
     * @param array $templateOptions
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSetTemplateOptions(
        TransportBuilder $subject,
        $templateOptions
    ) {
        if (array_key_exists('store', $templateOptions)) {
            $this->storeModel->setStoreId($templateOptions['store']);
        } else {
            $this->storeModel->setStoreId(null);
        }

        return [$templateOptions];
    }
}
