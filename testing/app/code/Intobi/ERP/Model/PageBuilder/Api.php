<?php

declare(strict_types=1);

namespace Intobi\ERP\Model\PageBuilder;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\Client\CurlFactory;

class Api
{
    const ALLOWED_IFRAME = 'erp/general/allowed_iframe';
    const HOSTS_API = 'erp/general/hosts_api';
    const IS_ENABLE = 'erp/general/is_enable';
    const SEND_REQUEST = 'erp/general/send_request';
    const PAVE_SAVE_URL = 'erp/general/page_save';
    const CURL_TIMEOUT = 10;

    /**
     * @var ScopeConfigInterface
     */
    private $config;
    /**
     * @var CurlFactory
     */
    private $curl;

    /**
     * @param ScopeConfigInterface $config
     * @param CurlFactory $curl
     */
    public function __construct(
        ScopeConfigInterface $config,
        CurlFactory $curl
    ) {
        $this->config = $config;
        $this->curl = $curl;
    }

    /**
     * return array with allowed hosts for use iframe
     * @return array
     */
    public function getAllowedHosts(): array
    {
        $config = $this->config->getValue(self::ALLOWED_IFRAME);

        if (!$config) {
            return [];
        }

        return explode(',', $config);
    }

    /**
     * @return array
     */
    public function getHostsApi(): array
    {
        $config = $this->config->getValue(self::HOSTS_API);

        if (!$config) {
            return [];
        }

        return array_map(fn ($host) => rtrim(strtolower(trim($host)), '/'), explode(',', $config));
    }

    /**
     * @return string|null
     */
    public function getPageSavePath(): ?string
    {
        return $this->config->getValue(self::PAVE_SAVE_URL);
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return (bool)$this->config->getValue(self::IS_ENABLE);
    }

    /**
     * @return bool
     */
    public function hasSendRequest(): bool
    {
        return (bool)$this->config->getValue(self::SEND_REQUEST);
    }

    /**
     * @param $data
     * @return void
     */
    public function sendPageData($data): void
    {
        $curl = $this->curl->create();
        $curl->setTimeout(self::CURL_TIMEOUT);

        foreach ($this->getHostsApi() as $url)
        {
            $curl->post($url . $this->getPageSavePath(), $data);
        }
    }
}
