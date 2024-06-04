<?php

namespace Smartwave\Porto\Block\Form;

use Magento\Customer\Block\Form\Login as BaseLogin;

/**
 * Class Block extension for login form
 */
class Login extends BaseLogin
{
    /**
     * Prepare layout function
     *
     * @return $this
     */
    protected function _prepareLayout(): static
    {
        return $this;
    }
}
