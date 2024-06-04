<?php

namespace Smartwave\Megamenu\Model\Attribute;

use Smartwave\Megamenu\Helper\Data;

class Categorylabel extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var Data
     */
    protected $_helper;
    
    /**
     * Constructor
     *
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    ) {
        $this->_helper = $helper;
    }

    /**
     * Return category label option array
     *
     * @return array
     */
    public function getAllOptions()
    {
        $label1 = $this->_helper->getConfig('sw_megamenu/cat_labels/label1');
        $label2 = $this->_helper->getConfig('sw_megamenu/cat_labels/label2');
        $label3 = $this->_helper->getConfig('sw_megamenu/cat_labels/label3');
        
        if (!$this->_options) {
            $this->_options = [
                ['value' => '', 'label' => __('No Label')],
                ['value' => 'label1', 'label' => $label1],
                ['value' => 'label2', 'label' => $label2],
                ['value' => 'label3', 'label' => $label3]
            ];
        }
        
        return $this->_options;
    }
}
