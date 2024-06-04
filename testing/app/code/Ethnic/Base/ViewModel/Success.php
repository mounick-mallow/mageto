<?php

/**
 * Ethnic_Base
 *
 * @author RuslanP <ruslan.p@ideainyou.com>
 *
 * @copyright Copyright (c) 2023 IdeaInYou
 */

namespace Ethnic\Base\ViewModel;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Url\DecoderInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class View Model get "Size Chart" link
 */
class Success implements ArgumentInterface
{
    /**
     * @var DecoderInterface
     */
    private DecoderInterface $_decoder;

    /**
     * @var RequestInterface
     */
    private RequestInterface $_request;

    /**
     * @param DecoderInterface $decoder
     * @param RequestInterface $request
     */
    public function __construct(
        DecoderInterface $decoder,
        RequestInterface $request
    ) {
        $this->_decoder = $decoder;
        $this->_request = $request;
    }

    /**
     * Decode Url
     *
     * @param string $url
     *
     * @return string
     */
    public function decodeUrl($url): string
    {
        return $this->_decoder->decode($url);
    }

    /**
     * Get email from request
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->_request->getPost('email');
    }
}
