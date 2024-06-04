<?php

namespace Magetop\GiftCard\Ui\Component\Listing\Columns;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class IsExpire extends Column
{
    /**
     * @var \Magetop\GiftCard\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magetop\GiftCard\Model\GiftUserFactory
     */
    protected $_giftuser;

    /**
     * @var \Magetop\GiftCard\Helper\Data
     */
    protected $_helperData;

    /**
     * Constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Magetop\GiftCard\Helper\Data $helperData
     * @param \Magetop\GiftCard\Model\GiftUserFactory $giftUser
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magetop\GiftCard\Helper\Data $helperData,
        \Magetop\GiftCard\Model\GiftUserFactory $giftUser,
        array $components = [],
        array $data = []
    ) {
        $this->_helperData = $helperData;
        $this->_giftuser = $giftUser;
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
                $isExpire = $this->_helperData->checkExpirationOfGiftCard($item['alloted'], $item['duration']);
                // $model = $this->_giftuser->create->load($item['giftuserid']);
                if ($isExpire) {
                    $item['is_expire'] = '
                    <div style="background:#f9d4d4;border:1px solid;border-color:#e22626;
                    padding: 0 7px;text-align:center;
                    text-transform: uppercase;color:#e22626;
                    font-weight:bold;" title="Gift Card is expire">Expired</div>';
                } else {
                    $item['is_expire'] = '
                    <div style="background:#d0e5a9;border:1px solid;border-color:#5b8116;
                    padding: 0 7px;text-align:center;
                    text-transform: uppercase;
                    color:#185b00;font-weight:bold;" title="Gift Card is active">Active</div>';
                }
            }
        }
        return $dataSource;
    }
}
