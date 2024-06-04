<?php

namespace Magetop\GiftCard\Ui\Component\Listing\Columns;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class ExpiryDate extends Column
{
    /**
     * @var \Magetop\GiftCard\Helper\Data
     */
    protected $_helper;

    /**
     * Constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Magetop\GiftCard\Helper\Data $helperData
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magetop\GiftCard\Helper\Data $helperData,
        array $components = [],
        array $data = []
    ) {
        $this->_helper = $helperData;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source.
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item['expiry'] = $this->_helper->createExpirationDateOfGiftCard($item['duration'], $item['alloted']);
            }
        }
        return $dataSource;
    }
}
