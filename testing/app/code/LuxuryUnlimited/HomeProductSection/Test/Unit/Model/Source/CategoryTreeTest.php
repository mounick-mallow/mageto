<?php declare(strict_types=1);

namespace LuxuryUnlimited\HomeProductSection\Test\Unit\Model\Source;

use Magento\Store\Model\Store;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use LuxuryUnlimited\HomeProductSection\Model\Source\CategoryTree;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\CategoryManagement;
use Magento\Catalog\Api\Data\CategoryTreeInterface;

class CategoryTreeTest extends TestCase
{
    /**
     * @var CategoryTree $object
     */
    private CategoryTree $object;

    /**
     * @var MockObject $storeManager
     */
    private MockObject $storeManager;

    /**
     * @var MockObject $categoryManagement
     */
    private MockObject $categoryManagement;

    protected function setUp(): void
    {
        $this->storeManager = $this->getMockForAbstractClass(
            StoreManagerInterface::class,
            [],
            '',
            false,
            false,
            true,
            []
        );
        $this->categoryManagement = $this->getMockBuilder(CategoryManagement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new CategoryTree(
            $this->storeManager,
            $this->categoryManagement
        );
    }

    public function testCategoryTestInstance(): void
    {
        $this->assertInstanceOf(OptionSourceInterface::class, $this->object);
    }

    public function testToOptionArray(): void
    {
        $expectedResult = [
            [
                'label' => '&#9656;Test Category',
                'value' => 12
            ],
            [
                'label' => ' &nbsp; &nbsp; &nbsp; &#9656; Sub Category',
                'value' => 18
            ],
        ];
        $storeMock = $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($storeMock);
        $storeMock->expects($this->any())
            ->method('getRootCategoryId')
            ->willReturn(2);

        $categoryTreeMock = $this->getMockForAbstractClass(
            CategoryTreeInterface::class,
            [],
            '',
            false,
            false,
            true,
            []
        );
        $this->categoryManagement->expects($this->any())
            ->method('getTree')
            ->willReturn($categoryTreeMock);
        $categoryTreeMock->expects($this->any())
            ->method('getChildrenData')
            ->willReturnOnConsecutiveCalls(
                [$categoryTreeMock],
                [$categoryTreeMock],
                [$categoryTreeMock],
                [$categoryTreeMock],
                [$categoryTreeMock]
            );
        $categoryTreeMock->expects($this->any())
            ->method('getName')
            ->willReturnOnConsecutiveCalls('Root Category', 'Test Category', 'Sub Category', 'Sub Category');
        $categoryTreeMock->expects($this->any())
            ->method('getLevel')
            ->willReturnOnConsecutiveCalls(1, 1, 3, 3);
        $categoryTreeMock->expects($this->any())
            ->method('getId')
            ->willReturnOnConsecutiveCalls(2, 12, 18);

        $this->assertEquals($expectedResult, $this->object->toOptionArray());
    }
}
