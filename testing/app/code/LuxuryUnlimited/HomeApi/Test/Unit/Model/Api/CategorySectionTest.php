<?php declare(strict_types=1);

namespace LuxuryUnlimited\HomeApi\Test\Unit\Model\Api;

use Magento\Catalog\Model\Category;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Api\Data\StoreInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use LuxuryUnlimited\HomeApi\Model\Api\CategorySection;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\StoreManagerInterface;
use LuxuryUnlimited\HomeCategorySection\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Category\Collection;

class CategorySectionTest extends TestCase
{
    /**
     * @var CategorySection $object
     */
    private CategorySection $object;

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
     * @var MockObject $categoryCollectionFactory
     */
    private MockObject $categoryCollectionFactory;

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
        $this->categoryCollectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new CategorySection(
            $this->logger,
            $this->storeManager,
            $this->helper,
            $this->categoryCollectionFactory
        );
    }

    public function testGetCategorySectionInvalidSectionType(): void
    {
        $this->assertEquals(
            ['success' => false, 'message' => "invalid section type"],
            $this->object->getCategorySection('')
        );
    }

    public function testGetCategorySection(): void
    {
        $section = 1;
        $limit = 2;
        $responseData = [
            'entity_id' => 1,
            'name' => 'Test Category #1'
        ];
        $this->helper->expects($this->once())->method('isEnabledSectionOne')->willReturn('1');
        $this->helper->expects($this->once())->method('getCategorySectionOneCount')->willReturn($limit);

        // Store
        $storeMock = $this->getMockForAbstractClass(StoreInterface::class);
        $storeMock->expects($this->once())->method('getId')->willReturn(208);
        $this->storeManager->expects($this->once())
            ->method('getStore')
            ->willReturn($storeMock);

        // Category
        $categoryMock = $this->getMockBuilder(Category::class)
            ->disableOriginalConstructor()
            ->getMock();
        $categoryMock->setData('entity_id', 1);
        $categoryMock->setData('name', 'Test Category #1');

        // Collection
        $collectionMock = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->categoryCollectionFactory->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($collectionMock);
        $collectionMock->expects($this->any())
            ->method('addAttributeToSelect')
            ->with('*')
            ->willReturn($collectionMock);
        $collectionMock->expects($this->once())
            ->method('addAttributeToFilter')
            ->with('section_one_display', 1)
            ->willReturnSelf();
        $collectionMock->expects($this->any())
            ->method('setStore')
            ->with(208)
            ->willReturnSelf();
        $collectionMock->expects($this->atLeastOnce())
            ->method('setPageSize')
            ->with($limit)
            ->willReturn($collectionMock);
        $collectionMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator([$categoryMock]));
        $categoryMock->expects($this->any())->method('getData')->willReturn($responseData);

        $this->assertEquals(
            ['success' => true, 'message' => [$responseData]],
            $this->object->getCategorySection($section)
        );
    }

    public function testGetCategorySectionDisableSection(): void
    {
        $this->helper->expects($this->once())->method('isEnabledSectionTwo')->willReturn('0');
        $this->assertEquals(
            ['success' => false, 'message' => 'second section is not enabled'],
            $this->object->getCategorySection(2)
        );
    }

    public function testGetCategorySectionDataException(): void
    {
        $section = 1;
        $limit = 2;
        $attributeCode = 'section_one_display';
        $responseData = new LocalizedException(
            __('Invalid attribute identifier for filter (%1)', $attributeCode)
        );
        $this->helper->expects($this->once())->method('isEnabledSectionOne')->willReturn('1');
        $this->helper->expects($this->once())->method('getCategorySectionOneCount')->willReturn($limit);

        // Store
        $storeMock = $this->getMockForAbstractClass(StoreInterface::class);
        $storeMock->expects($this->once())->method('getId')->willReturn(208);
        $this->storeManager->expects($this->once())
            ->method('getStore')
            ->willReturn($storeMock);

        // Collection
        $collectionMock = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->categoryCollectionFactory->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($collectionMock);
        $collectionMock->expects($this->any())
            ->method('addAttributeToSelect')
            ->with('*')
            ->willReturn($collectionMock);
        $collectionMock->expects($this->once())
            ->method('addAttributeToFilter')
            ->with($attributeCode, 1)
            ->willThrowException($responseData);
        $this->logger->expects($this->once())
            ->method('info')
            ->with($responseData->getMessage());

        $this->assertEquals(
            ['success' => false, 'message' => $responseData->getMessage()],
            $this->object->getCategorySection($section)
        );
    }
}
