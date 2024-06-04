<?php

namespace LuxuryUnlimited\MostPopular\Block\Adminhtml\Product\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use PhpParser\Node\Expr\Print_;

class MostPopular extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string $_template
     */
    protected $_template = 'most_popular.phtml';

    /**
     * @var Registry
     */
    private $_coreRegistry = null;

    /**
     * *
     *
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * *
     *
     * @return Registry
     */
    public function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }

    /**
     * *
     *
     * @return array
     */
    public function getMostPopularProductAttribute()
    {
        $product = $this->getProduct();
        $attribute_data =   $product->getResource()
            ->getAttribute('add_to_most_popular');
        $attribute_value = $product->getCustomAttribute('add_to_most_popular')
            ? $product->getCustomAttribute('add_to_most_popular')->getValue()
            : 0;
        $label = '';
        $name = '';
        if ($attribute_data) {
            $label = $attribute_data['frontend_label'];
            $name = $attribute_data['attribute_code'];
        }
        $data['label'] = $label;
        $data['name'] = $name;
        $data['value'] = $attribute_value;
        return $data;
    }
}
