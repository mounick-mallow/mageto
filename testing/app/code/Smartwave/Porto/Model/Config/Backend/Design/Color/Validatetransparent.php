<?php

namespace Smartwave\Porto\Model\Config\Backend\Design\Color;

use Magento\Framework\App\Config\Value;

/**
 * Class to set values before saving
 */
class Validatetransparent extends Value
{
    /**
     * Before Save
     *
     * @return $this
     */
    public function beforeSave()
    {
        $v = $this->getValue();
        if ($v == 'rgba(0, 0, 0, 0)') {
            $this->setValue('transparent');
        }
        return $this;
    }
}
