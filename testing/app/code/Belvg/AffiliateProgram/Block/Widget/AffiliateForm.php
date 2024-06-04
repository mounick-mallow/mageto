<?php
declare(strict_types=1);

namespace Belvg\AffiliateProgram\Block\Widget;

use Magento\Framework\View\Element\Template;

class AffiliateForm extends Template implements \Magento\Widget\Block\BlockInterface
{
    protected $_template = 'widget/affiliate_form.phtml';

    /**
     *
     * @param Template\Context $context

     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }
}
