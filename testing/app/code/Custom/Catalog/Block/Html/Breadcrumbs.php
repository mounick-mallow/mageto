<?php

namespace Custom\Catalog\Block\Html;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;

/**
 * Html page breadcrumbs block
 *
 * @api
 * @since 100.0.2
 */
class Breadcrumbs extends \Magento\Theme\Block\Html\Breadcrumbs
{
    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (is_array($this->_crumbs)) {
            reset($this->_crumbs);
            $this->_crumbs[key($this->_crumbs)]['first'] = true;
            end($this->_crumbs);
            $this->_crumbs[key($this->_crumbs)]['last'] = true;
        }
        if ($this->getRequest()->getFullActionName() == 'catalog_product_view') {
            unset($this->_crumbs['product']);
            unset($this->_crumbs['cms_page']);
        }
        $this->assign('crumbs', $this->_crumbs);

        return parent::_toHtml();
    }
}
