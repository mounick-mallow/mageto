<?php
declare(strict_types=1);

namespace Belvg\ContinueLink\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Checkout\Model\Session;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    public Session $session;

    public StoreManagerInterface $storeManager;

    /**
     *
     * @param Context $context
     * @param Session $session
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        Session $session,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->session = $session;
        $this->storeManager = $storeManager;
    }

    /**
     *
     * @return string|null
     * @throws
     */
    public function getContinueLink(): ?string
    {
        $lastVisitedUrl = $this->session->getLastVisitedUrl();
        $storeUrl = $this->storeManager->getStore()->getBaseUrl();

        return !empty($lastVisitedUrl) ? $lastVisitedUrl : $storeUrl;
    }
}
