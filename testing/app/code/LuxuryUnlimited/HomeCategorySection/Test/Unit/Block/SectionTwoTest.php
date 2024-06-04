<?php declare(strict_types=1);

namespace LuxuryUnlimited\HomeCategorySection\Test\Unit\Block;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\View\Element\Template\Context;
use LuxuryUnlimited\HomeCategorySection\Helper\Data;
use LuxuryUnlimited\HomeCategorySection\Block\SectionTwo;

class SectionTwoTest extends TestCase
{
    /**
     * @var SectionTwo $object
     */
    private SectionTwo $object;

    /**
     * @var MockObject $context
     */
    private MockObject $context;

    /**
     * @var MockObject $categoryCollectionFactory
     */
    private MockObject $categoryCollectionFactory;

    /**
     * @var MockObject $helper
     */
    private MockObject $helper;

    protected function setUp(): void
    {
        $this->context = $this->createMock(Context::class);
        $this->categoryCollectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->helper = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Store
        $storeMock = $this->getMockForAbstractClass(StoreInterface::class);
        $storeManagerMock = $this->getMockForAbstractClass(StoreManagerInterface::class);
        $storeManagerMock->expects($this->any())->method('getStore')->willReturn($storeMock);
        $this->context->expects($this->once())->method('getStoreManager')->willReturn($storeManagerMock);

        $this->object = new SectionTwo(
            $this->context,
            $this->categoryCollectionFactory,
            $this->helper
        );
    }

    public function testGetSectionTwoCategories(): void
    {
        // Initial data
        $image = '/media/catalog/category/unsplash_6OGml3UomZw2.png';
        $placeholderImage = 'https://brand-labels.com/media/catalog/product/placeholder/placeholder-image.jpg';
        $categoryCount = 3;
        $fields = ['name','section_two_image'];
        $responseData = [
            'name' => 'Test Name #2',
            'image' => $image,
            'url' => 'category-2.html'
        ];
        $responseDataPlaceholder = [
            'name' => 'Test Name #2',
            'image' => $placeholderImage,
            'url' => 'category-2.html'
        ];

        // Categories Count
        $this->helper->expects($this->any())
            ->method('getCategorySectionTwoCount')
            ->willReturn($categoryCount);

        // Category
        $categoryMock = $this->getMockBuilder(Category::class)
            ->addMethods(['getSectionTwoImage'])
            ->disableOriginalConstructor()
            ->getMock();

        // Category Data
        $categoryMock->setName('Test Name #2');
        $categoryMock->setUrl('category-2.html');

        // Store
        $storeMock = $this->getMockForAbstractClass(StoreInterface::class);

        // Collection
        $collectionMock = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->categoryCollectionFactory->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($collectionMock);
        $collectionMock->expects($this->any())
            ->method('addFieldToSelect')
            ->with($fields)
            ->willReturnSelf();
        $collectionMock->expects($this->any())
            ->method('addFieldToFilter')
            ->with('section_two_display', 1)
            ->willReturnSelf();
        $collectionMock->expects($this->any())
            ->method('setStore')
            ->with($storeMock)
            ->willReturnSelf();
        $collectionMock->expects($this->any())
            ->method('setCurPage')
            ->with(1)
            ->willReturnSelf();
        $collectionMock->expects($this->any())
            ->method('setPageSize')
            ->with($categoryCount)
            ->willReturnSelf();
        $collectionMock->expects($this->any())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator([$categoryMock]));

        // Category Image
        $categoryMock->expects($this->any())
            ->method('getSectionTwoImage')
            ->willReturnOnConsecutiveCalls($image, $image, '');
        $this->helper->expects($this->any())
            ->method('getPlaceHolderImage')
            ->willReturn($placeholderImage);

        $this->assertEquals(
            [$responseData],
            $this->object->getSectionTwoCategories()
        );
        $this->assertEquals(
            [$responseDataPlaceholder],
            $this->object->getSectionTwoCategories()
        );
    }

    public function testIsEnabledSectionTwo(): void
    {
        $this->helper->expects($this->any())
            ->method('isEnabledSectionTwo')
            ->willReturnOnConsecutiveCalls('0', '1');

        $this->assertEquals('0', $this->object->isEnabledSectionTwo());
        $this->assertEquals('1', $this->object->isEnabledSectionTwo());
    }
}
