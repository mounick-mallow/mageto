<?php

declare(strict_types=1);

namespace Intobi\ERP\App\Response\HeaderProvider;

use Exception;
use Throwable;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\State;
use Magento\Framework\App\Response\HeaderProvider\XFrameOptions as CoreXFrameOptions;
use Intobi\ERP\Model\PageBuilder\Api;

class XFrameOptions extends CoreXFrameOptions
{
    public function __construct(
        Api $api,
        $xFrameOpt = self::BACKEND_X_FRAME_OPT
    ) {
        try {
            if (!$api->isEnable()) {
                throw new Exception();
            }
            if (!empty($_SERVER['HTTP_SEC_FETCH_DEST']) && $_SERVER['HTTP_SEC_FETCH_DEST'] !== 'iframe') {
                throw new Exception();
            }
            $parseUrl = parse_url($_SERVER['HTTP_REFERER']);
            $host = $parseUrl['host'];

            $redirect = false;

            foreach ($api->getAllowedHosts() as $url) {
                if (strpos($url, $host) !== false) {
                    $redirect = true;
                }
            }

            $state = ObjectManager::getInstance()->get(State::class);
            /** @var ResponseInterface $response */
            $response = ObjectManager::getInstance()->get(ResponseInterface::class);
            $backendUrl = ObjectManager::getInstance()->get(UrlInterface::class);
            $currentArea = $state->getAreaCode();

            if ($currentArea !== 'adminhtml' && $redirect) {
                $response->setRedirect($backendUrl->getUrl('adminhtml/'));
                return;
            }
        } catch (Exception|Throwable $e) {
            parent::__construct($xFrameOpt);
            return;
        }
    }
}
