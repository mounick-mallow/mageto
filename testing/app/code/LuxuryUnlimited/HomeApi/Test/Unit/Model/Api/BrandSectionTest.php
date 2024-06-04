<?php declare(strict_types=1);

namespace LuxuryUnlimited\HomeApi\Test\Unit\Model\Api;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use LuxuryUnlimited\HomeApi\Model\Api\BrandSection;
use LuxuryUnlimited\BrandList\Helper\Data;
use Mage360\Brands\Model\ResourceModel\Brands\CollectionFactory;
use Mage360\Brands\Model\ResourceModel\Brands\Collection;
use Mage360\Brands\Model\Brands;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class BrandSectionTest extends TestCase
{
    /**
     * @var BrandSection $object
     */
    private BrandSection $object;

    /**
     * @var MockObject $logger
     */
    private MockObject $logger;

    /**
     * @var MockObject $storeManager
     */
    private MockObject $storeManager;

    /**
     * @var MockObject $helper
     */
    private MockObject $helper;

    /**
     * @var MockObject $brandCollectionFactory
     */
    private MockObject $brandCollectionFactory;

    protected function setUp(): void
    {
        $this->logger = $this->getMockForAbstractClass(
            LoggerInterface::class,
            [],
            '',
            false,
            false,
            true,
            []
        );
        $this->storeManager = $this->getMockForAbstractClass(
            StoreManagerInterface::class,
            [],
            '',
            false,
            false,
            true,
            ['getStore']
        );
        $this->helper = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->brandCollectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new BrandSection(
            $this->logger,
            $this->storeManager,
            $this->helper,
            $this->brandCollectionFactory
        );
    }

    public function testGetBrandSectionSectionDisabled(): void
    {
        $responseData = [
            'one' => ['success' => false, 'message' => 'first section is not enabled'],
            'two' => ['success' => false, 'message' => 'second section is not enabled']
        ];

        $this->helper->expects($this->any())
            ->method('isEnabledSectionOne')
            ->willReturn('0');
        $this->helper->expects($this->any())
            ->method('isEnabledSectionTwo')
            ->willReturn('0');

        $this->assertEquals($responseData['one'], $this->object->getBrandSection(1));
        $this->assertEquals($responseData['two'], $this->object->getBrandSection(2));
    }

    public function testGetBrandSectionInvalidType(): void
    {
        $this->assertEquals(
            ['success' => false, 'message' => 'invalid section type'],
            $this->object->getBrandSection(5)
        );
    }

    public function testGetBrandSection(): void
    {
        $sectionOne = 1;
        $sectionTwo = 2;
        $limit = Data::DEFAULT_BRAND_COUNT;
        $responseData = [
            'one' => ['success' => true, 'message' => ['entity_id' => 1, 'name' => 'Test Brand #1']],
            'two' => ['success' => true, 'message' => ['entity_id' => 2, 'name' => 'Test Brand #2']],
        ];

        // Enable section
        $this->helper->expects($this->once())
            ->method('isEnabledSectionOne')
            ->willReturn('1');
        $this->helper->expects($this->any())
            ->method('isEnabledSectionTwo')
            ->willReturn('1');

        // Limit
        $this->helper->expects($this->once())
            ->method('getSectionOneBrandCount')
            ->willReturn($limit);
        $this->helper->expects($this->any())
            ->method('getSectionTwoBrandCount')
            ->willReturn($limit);

        // Brand
        $brandMock = $this->getMockBuilder(Brands::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Collection
        $collectionMock = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collectionMock->method('addFieldToFilter')
            ->willReturnSelf();
        $collectionMock->method('addFieldToSelect')
            ->willReturnSelf();
        $this->brandCollectionFactory->method('create')
            ->willReturn($collectionMock);
        $collectionMock->expects($this->any())
            ->method('getIterator')
            ->willReturn(
                new \ArrayIterator([$brandMock]),
            );

        // Results
        $brandMock->expects($this->any())
            ->method('getData')
            ->willReturnOnConsecutiveCalls($responseData['one'], $responseData['two']);

        $this->assertEquals(
            ['success' => true, 'message' => [$responseData['one']]],
            $this->object->getBrandSection($sectionOne)
        );
        $this->assertEquals(
            ['success' => true, 'message' => [$responseData['two']]],
            $this->object->getBrandSection($sectionTwo)
        );
    }

    public function testGetBrandSectionException(): void
    {
        $sectionOne = 1;
        $limit = Data::DEFAULT_BRAND_COUNT;
        $exception = new LocalizedException(
            __('Type Error occurred when creating object.')
        );
        $responseData = ['success' => false, 'message' => $exception->getMessage()];

        // Enable section
        $this->helper->expects($this->once())
            ->method('isEnabledSectionOne')
            ->willReturn('1');

        // Limit
        $this->helper->expects($this->once())
            ->method('getSectionOneBrandCount')
            ->willReturn($limit);

        // Collection
        $this->brandCollectionFactory->method('create')
            ->willThrowException($exception);

        // Logger
        $this->logger->expects($this->once())
            ->method('info')
            ->with($exception->getMessage());

        // Execute
        $this->assertEquals(
            $responseData,
            $this->object->getBrandSection($sectionOne)
        );
    }
}
