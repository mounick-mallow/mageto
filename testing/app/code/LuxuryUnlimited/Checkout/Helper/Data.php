<?php
namespace LuxuryUnlimited\Checkout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    /**
     * Check current mobile device
     *
     * @return bool
     */
    public function isMobileDevice()
    {
        $req =
            "/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|" .
            "phone|pie|tablet|up\.browser|up\.link|webos|wos)/i";
        return preg_match($req, $this->_httpHeader->getHttpUserAgent());
    }
}
