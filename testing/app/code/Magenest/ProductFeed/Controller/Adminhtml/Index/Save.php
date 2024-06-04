<?php

namespace Magenest\ProductFeed\Controller\Adminhtml\Index;

use Exception;
use Magenest\ProductFeed\Controller\Adminhtml\AbstractFeed;
use Magenest\ProductFeed\Model\ProductFeed;
use Magento\Framework\Exception\LocalizedException;

class Save extends AbstractFeed
{
    public function execute()
    {
        $this->getRequest()->getParams();
        $id = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $modelFeed = $this->initFeed();
            if (!$modelFeed->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This feed no longer exists.'));
                return $modelFeed->setPath('*/*/');
            }

            $modelFeed->addData($data);
            try {
                $this->saveGeneral($modelFeed);
                $this->saveAttrTemplate($modelFeed);
                $this->saveRule($modelFeed);
                $this->saveMapping($modelFeed);

                $this->resourceFeed->save($modelFeed);
                $this->messageManager->addSuccessMessage(__('You saved the feed.'));
                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath('feed/*/edit', ['feed_id' => $modelFeed->getId(), '_current' => true]);
                } else {
                    $resultRedirect->setPath('feed/*/');
                }
                return $resultRedirect;
            } catch (\Magento\Framework\Validator\Exception $e) {
                $this->_session->setFormData($this->getRequest()->getParams());
                $this->messageManager->addErrorMessage(__('File name already exist!'));
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        } else {
            $resultRedirect->setPath('*/*/');
            $this->messageManager->addErrorMessage('No data to save.');
            return $resultRedirect;
        }
        $resultRedirect->setPath('feed/*/edit', ['feed_id' => $modelFeed->getId(), '_current' => true]);
        return $resultRedirect;
    }

    /**
     *  Save data tab "General Infomation"
     */
    // save data in general tab in admin
    public function saveGeneral($modelFeed)
    {
        $feedName = $this->getRequest()->getParam('feed_name');
        $feedStatus = $this->getRequest()->getParam('status');
        $feedStoreView = $this->getRequest()->getParam('store_id');
        $feedFileName = $this->getRequest()->getParam('filename');
        $feedFileType = $this->getRequest()->getParam('filetype');

        $arrSave = [
            'feed_name' => $feedName,
            'filename' => trim($feedFileName),
            'filetype' => $feedFileType,
            'status' => $feedStatus,
            'store_id' => $feedStoreView
        ];

        $modelFeed->addData($arrSave);
    }
    // save attribute template in template tab in admin
    public function saveAttrTemplate($modelFeed)
    {
        $dataTemplate = $this->getRequest()->getPostValue('feed');
        if (empty($dataTemplate)) {
            return $this;
        }
        if (isset($dataTemplate)) {
            $attrTemp = $this->jsonHelper->serialize($dataTemplate);
        }
        $arrSave = [
            'attribute_template' => $attrTemp,
        ];

        $modelFeed->addData($arrSave);
    }

    /**+
     * @param ProductFeed $model
     */
    // save rule in filter   tab in admin
    public function saveRule($modelFeed)
    {
        $dataPost = $this->getRequest()->getPostValue();
        if (isset($dataPost['rule'])) {
            $modelFeed->loadPost($dataPost['rule']);
        }
    }
    // save mapping in mapping tab in admin
    public function saveMapping($modelFeed)
    {
        $mappingCategory = $this->getRequest()->getParam('mapping');
        $arrSave = [
            'mapping_json' => json_encode($mappingCategory),
        ];
        $modelFeed->addData($arrSave);
    }
}
