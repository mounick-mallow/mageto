<?php

namespace Smartwave\Porto\Model\Attribute;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class get options array with all product page types
 */
class Productpagetype extends AbstractSource
{
    /**
     * Get All Options
     *
     * @return array|array[]|null
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['value' => '', 'label' => __('-')],
                ['value' => 'default', 'label' => __('Default')],
                ['value' => 'carousel', 'label' => __('Extended')],
                ['value' => 'fullwidth', 'label' => __('Full Width')],
                ['value' => 'grid', 'label' => __('Grid Images')],
                ['value' => 'sticky_right', 'label' => __('Sticky Right Info')],
                ['value' => 'wide_grid', 'label' => __('Vertical Thumbnail')]
            ];
        }
        
        return $this->_options;
    }
}
