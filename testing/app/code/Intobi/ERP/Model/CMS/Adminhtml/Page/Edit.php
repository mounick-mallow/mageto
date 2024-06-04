<?php

namespace Intobi\ERP\Model\CMS\Adminhtml\Page;

use Throwable;
use Exception;
use Magento\Cms\Controller\Adminhtml\Page\Edit as OriginalEdit;

class Edit extends OriginalEdit
{
    public function execute()
    {
        $resultPage = parent::execute();

        try {
            $id = $this->getRequest()->getParam('page_id');
            $model = $this->_objectManager->create(\Magento\Cms\Model\Page::class);

            if ($id) {
                $model->load($id);
            }

            $array = (string)json_encode([
                'action' => 'cms_page_edit',
                'content' => $model->getContent()
            ]);

            print <<<HERODOC
<script>
    const parentWindow = window.parent;
    var obj = $array;;
    console.log(obj);
    parentWindow.postMessage(obj, '*');
</script>
HERODOC;
        } catch (Exception|Throwable $e) {
            return $resultPage;
        }

        return $resultPage;
    }
}
