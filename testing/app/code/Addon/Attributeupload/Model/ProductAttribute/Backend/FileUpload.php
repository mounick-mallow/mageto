<?php

namespace Addon\Attributeupload\Model\ProductAttribute\Backend;

class FileUpload extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_uploaderFactory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_filesystem;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_fileUploaderFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magento\Catalog\Model\ImageUploader
     */
    private $imageUploader;

    /**
     * @var string
     */
    private $additionalData = '_additional_data_';

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\App\RequestInterface $request
    ) {

        $this->_filesystem = $filesystem;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_logger = $logger;
        $this->request = $request;
    }

    /**
     * Gets image name from $value array.
     *
     * Will return empty string in a case when $value is not an array
     *
     * @param array $value Attribute value
     * @return string
     */
    private function getUploadedImageName($value)
    {
        if (is_array($value) && isset($value[0]['name'])) {
            return $value[0]['name'];
        }

        return '';
    }

    /**
     * Avoiding saving potential upload data to DB
     *
     * Will set empty image attribute value if image was not uploaded
     *
     * @param \Magento\Framework\DataObject $object
     * @return $this
     * @since 101.0.8
     */
    public function beforeSave($object)
    {
        $attributeName = $this->getAttribute()->getName();
        $value = $object->getData($attributeName);
        $postData = $this->request->getPost();
        if (isset($postData['product'][$attributeName]) && !empty($postData['product'][$attributeName])) {

            if ($imageName = $this->getUploadedImageName($value)) {
                $object->setData($this->additionalData . $attributeName, $value);
                $object->setData($attributeName, $imageName);
            } elseif (!is_string($value)) {
                $object->setData($attributeName, '');
            }
        } else {
            $object->setData($attributeName, '');
        }

        return parent::beforeSave($object);
    }

    /**
     * Get Image Uploader
     *
     * @return \Magento\Catalog\CategoryImageUpload|\Magento\Catalog\Model\ImageUploader|mixed
     */
    private function getImageUploader()
    {
        if ($this->imageUploader === null) {
            //@codingStandardsIgnoreLine
            $this->imageUploader = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Catalog\CategoryImageUpload');
        }

        return $this->imageUploader;
    }

    /**
     * Check if temporary file is available for new image upload.
     *
     * @param array $value
     * @return bool
     */
    private function isTmpFileAvailable($value)
    {
        return is_array($value) && isset($value[0]['tmp_name']);
    }

    /**
     * Save uploaded file and set its name to category
     *
     * @param \Magento\Framework\DataObject $object
     * @return \Magento\Catalog\Model\Category\Attribute\Backend\Image
     */
    public function afterSave($object)
    {

        $value = $object->getData($this->additionalData . $this->getAttribute()->getName());
        if ($this->isTmpFileAvailable($value) && $imageName = $this->getUploadedImageName($value)) {
            try {
                $this->getImageUploader()->setBaseTmpPath('catalog/tmp/attribute-data');
                $this->getImageUploader()->setBasePath('catalog/attribute-data');
                $this->getImageUploader()->moveFileFromTmp($imageName);
            } catch (\Exception $e) {
                $this->_logger->critical($e);
            }
        }
        return $this;
    }
}
