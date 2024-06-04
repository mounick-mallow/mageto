<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace LuxuryUnlimited\Customer\Controller\Address;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\InputException;
use Magento\Customer\Api\Data\RegionInterface;
use Magento\Customer\Api\Data\RegionInterfaceFactory;
use Magento\Customer\Model\Address\Mapper;
use Magento\Customer\Model\Metadata\FormFactory;
use Magento\Customer\Model\Session;
use Magento\Directory\Helper\Data as HelperData;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\View\Result\PageFactory;
//use Magento\Framework\Filesystem;
//use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\NotFoundException;
use Magento\Customer\Controller\Address;

/**
 * Customer Address Form Post Controller
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)

 */
class FormPostAjax extends Address implements HttpPostActionInterface
{

    /**
     * @var RegionFactory
     */
    protected $regionFactory;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var Filesystem
     */
    //private $filesystem;
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var AddressRepositoryInterface
     */
    protected $addressRepository;

    /**
     * @var AddressInterfaceFactory
     */
    protected $addressDataFactory;

    /**
     * @var Validator
     */
    protected $formKeyValidator;

    /**
     * @var Mapper
     */
    private $_customerAddressMapper;

    /**
     * FormPostAjax constructor.
     *
     * @param Context $context
     * @param Session $customerSession
     * @param FormFactory $formFactory
     * @param RegionInterfaceFactory $regionDataFactory
     * @param DataObjectProcessor $dataProcessor
     * @param DataObjectHelper $dataObjectHelper
     * @param ForwardFactory $resultForwardFactory
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $jsonFactory
     * @param AddressRepositoryInterface $addressRepository
     * @param AddressInterfaceFactory $addressDataFactory
     * @param Validator $formKeyValidator
     * @param RegionFactory $regionFactory
     * @param HelperData $helperData
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        FormFactory $formFactory,
        RegionInterfaceFactory $regionDataFactory,
        DataObjectProcessor $dataProcessor,
        DataObjectHelper $dataObjectHelper,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        JsonFactory $jsonFactory,
        AddressRepositoryInterface $addressRepository,
        AddressInterfaceFactory $addressDataFactory,
        Validator $formKeyValidator,
        RegionFactory $regionFactory,
        //Filesystem $filesystem = null,
        HelperData $helperData
    ) {
        $this->regionFactory = $regionFactory;
        $this->helperData = $helperData;
        //$this->filesystem = $filesystem ?: ObjectManager::getInstance()->get(Filesystem::class);
        $this->jsonFactory = $jsonFactory;
        $this->addressRepository = $addressRepository;
        $this->addressDataFactory = $addressDataFactory;
        $this->formKeyValidator = $formKeyValidator;
        parent::__construct(
            $context,
            $customerSession,
            $formKeyValidator,
            $formFactory,
            $addressRepository,
            $addressDataFactory,
            $regionDataFactory,
            $dataProcessor,
            $dataObjectHelper,
            $resultForwardFactory,
            $resultPageFactory
        );
    }

    /**
     * Process AJAX form submission
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = $this->jsonFactory->create();
        
        if (!$this->getRequest()->isAjax()) {
            return $result->setData(['error' => true, 'message' => 'Invalid request.']);
        }
        
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return $result->setData(['error' => true, 'message' => 'Invalid form key.']);
        }

        try {
            $address = $this->_extractAddress();
            
            // Save the address
            $this->addressRepository->save($address);
            
            return $result->setData(['success' => true, 'message' => 'Address saved successfully.']);
        } catch (InputException $e) {
            return $result->setData(['error' => true, 'message' => $e->getMessage()]);
        } catch (\Exception $e) {
            return $result->setData(['error' => true, 'message' => 'An error occurred.'.$e->getMessage()]);
        }
    }

    /**
     * Extract address from request
     *
     * @return \Magento\Customer\Api\Data\AddressInterface
     */
    protected function _extractAddress()
    {
        $existingAddressData = $this->getExistingAddressData();

        /** @var \Magento\Customer\Model\Metadata\Form $addressForm */
        $addressForm = $this->_formFactory->create(
            'customer_address',
            'customer_address_edit',
            $existingAddressData
        );
        $addressData = $addressForm->extractData($this->getRequest());
        $attributeValues = $addressForm->compactData($addressData);

        $this->updateRegionData($attributeValues);

        $addressDataObject = $this->addressDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $addressDataObject,
            array_merge($existingAddressData, $attributeValues),
            \Magento\Customer\Api\Data\AddressInterface::class
        );
        $addressDataObject->setCustomerId($this->_getSession()->getCustomerId())
            ->setIsDefaultBilling(
                $this->getRequest()->getParam(
                    'default_billing',
                    isset($existingAddressData['default_billing']) ? $existingAddressData['default_billing'] : false
                )
            )
            ->setIsDefaultShipping(
                $this->getRequest()->getParam(
                    'default_shipping',
                    isset($existingAddressData['default_shipping']) ? $existingAddressData['default_shipping'] : false
                )
            );

        return $addressDataObject;
    }

    /**
     * Retrieve existing address data
     *
     * @return array
     * @throws \Exception
     */
    protected function getExistingAddressData()
    {
        $existingAddressData = [];
        if ($addressId = $this->getRequest()->getParam('id')) {
            $existingAddress = $this->_addressRepository->getById($addressId);
            if ($existingAddress->getCustomerId() !== $this->_getSession()->getCustomerId()) {
                throw new NotFoundException(__('Address not found.'));
            }
            $existingAddressData = $this->getCustomerAddressMapper()->toFlatArray($existingAddress);
        }
        return $existingAddressData;
    }

    /**
     * Update region data
     *
     * @param array $attributeValues
     * @return void
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function updateRegionData(&$attributeValues)
    {
        if (!empty($attributeValues['region_id'])) {
            $newRegion = $this->regionFactory->create()->load($attributeValues['region_id']);
            $attributeValues['region_code'] = $newRegion->getCode();
            $attributeValues['region'] = $newRegion->getDefaultName();
        }

        $regionData = [
            RegionInterface::REGION_ID => !empty($attributeValues['region_id']) ? $attributeValues['region_id'] : null,
            RegionInterface::REGION => !empty($attributeValues['region']) ? $attributeValues['region'] : null,
            RegionInterface::REGION_CODE => !empty($attributeValues['region_code'])
                ? $attributeValues['region_code']
                : null,
        ];

        $region = $this->regionDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $region,
            $regionData,
            \Magento\Customer\Api\Data\RegionInterface::class
        );
        $attributeValues['region'] = $region;
    }

    /**
     * Get Customer Address Mapper instance
     *
     * @return Mapper
     */
    private function getCustomerAddressMapper()
    {
        if ($this->_customerAddressMapper === null) {
            $this->_customerAddressMapper = ObjectManager::getInstance()->get(
                \Magento\Customer\Model\Address\Mapper::class
            );
        }
        return $this->_customerAddressMapper;
    }
}
