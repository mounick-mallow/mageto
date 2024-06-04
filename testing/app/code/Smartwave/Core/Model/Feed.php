<?php

namespace Smartwave\Core\Model;

class Feed extends \Magento\AdminNotification\Model\Feed
{
    public const SMARTWAVE_FEED_URL = 'www.portotheme.com/envato/porto2_notifications.rss';

     /**
      * Return feed url
      *
      * @return string
      */
    public function getFeedUrl()
    {
        $httpPath = $this->_backendConfig->isSetFlag(self::XML_USE_HTTPS_PATH) ? 'https://' : 'http://';
        if ($this->_feedUrl === null) {
            $this->_feedUrl = $httpPath . self::SMARTWAVE_FEED_URL;
        }
        return $this->_feedUrl;
    }

     /**
      * Return last update time
      *
      * @return mixed
      */
    public function getLastUpdate()
    {
        return $this->_cacheManager->load('smartwave_notifications_lastcheck');
    }

     /**
      * Save last update time
      *
      * @return $this
      */
    public function setLastUpdate()
    {
        $this->_cacheManager->save((string) time(), 'smartwave_notifications_lastcheck');
        return $this;
    }
}
