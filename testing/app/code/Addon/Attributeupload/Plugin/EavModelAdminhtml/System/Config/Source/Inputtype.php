<?php

namespace Addon\Attributeupload\Plugin\EavModelAdminhtml\System\Config\Source;

/**
 * @SuppressWarnings("PMD.AllPurposeAction")
 */
class Inputtype
{

    /**
     * Add File Option
     *
     * @param \Magento\Eav\Model\Adminhtml\System\Config\Source\Inputtype $subject
     * @param array $result
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterToOptionArray(\Magento\Eav\Model\Adminhtml\System\Config\Source\Inputtype $subject, $result)
    {
        $result[] = ['value' => 'file', 'label' => __('Upload Files')];
        return $result;
    }
}
