<?php
namespace LuxuryUnlimited\HomeCategorySection\Model\Category;

class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{

    /**
     * *
     */
    protected function getFieldsMap()
    {
        $fields = parent::getFieldsMap();
        $fields['content'][] = ['section_one_image','section_two_image'];
        return $fields;
    }
}
