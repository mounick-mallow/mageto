<?php
namespace Smartwave\Core\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Smartwave\Core\Model\Feed;
use Magento\Backend\Model\Auth\Session;

/**
 * Check feed for modification
 *
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class PredispatchAdminActionControllerObserver implements ObserverInterface
{
    /**
     * @var Feed
     */
    protected $_feed;

    /**
     * @var Session
     */
    protected $_backendAuthSession;

    /**
     * Constructor
     *
     * @param Feed $feed
     * @param Session $backendAuthSession
     */
    public function __construct(
        Feed $feed,
        Session $backendAuthSession
    ) {
        $this->_feed = $feed;
        $this->_backendAuthSession = $backendAuthSession;
    }

    /**
     * Index action
     *
     * @param Observer $observer
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(Observer $observer)
    {
        if ($this->_backendAuthSession->isLoggedIn()) {
            $this->_feed->checkUpdate();
        }
    }
}
