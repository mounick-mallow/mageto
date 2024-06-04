<?php
declare(strict_types=1);

namespace LuxuryUnlimited\HomeProductSection\Test\Unit\Block;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use LuxuryUnlimited\HomeProductSection\Block\SectionThree;
use Magento\Catalog\Block\Product\Context;
use LuxuryUnlimited\HomeProductSection\Helper\Data;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\ResourceModel\Product\Collection;

class SectionThreeTest extends TestCase
{
    /**
     * @var SectionThree $object
     */
    private SectionThree $object;

    /**
     * @var MockObject $context
     */
    private MockObject $context;

    /**
     * @var MockObject $postDataHelper
     */
    private MockObject $postDataHelper;

    /**
     * @var MockObject $layerResolver
     */
    private MockObject $layerResolver;

    /**
     * @var MockObject $categoryRepository
     */
    private MockObject $categoryRepository;

    /**
     * @var MockObject $urlHelper
     */
    private MockObject $urlHelper;

    /**
     * @var MockObject $helper
     */
    private MockObject $helper;

    protected function setUp(): void
    {
        $this->context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->postDataHelper = $this->getMockBuilder(PostHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->layerResolver = $this->getMockBuilder(Resolver::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->categoryRepository = $this->getMockForAbstractClass(
            CategoryRepositoryInterface::class,
            [],
            '',
            false,
            false,
            true,
            []
        );
        $this->urlHelper = $this->getMockBuilder(UrlHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->helper = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new SectionThree(
            $this->context,
            $this->postDataHelper,
            $this->layerResolver,
            $this->categoryRepository,
            $this->urlHelper,
            $this->helper
        );
    }

    public function testIsEnabledSectionThree(): void
    {
        $this->helper->expects($this->any())
            ->method('isEnabledSectionThree')
            ->willReturnOnConsecutiveCalls('0', '1');

        $this->assertEquals('0', $this->object->isEnabledSectionThree());
        $this->assertEquals('1', $this->object->isEnabledSectionThree());
    }

    public function testGetSectionThreeProductCollectionEmpty(): void
    {
        $this->helper->expects($this->once())
            ->method('getSectionThreeCategory')
            ->willReturn('');
        $this->assertEmpty($this->object->getSectionthreeProductCollection());
    }

    public function testGetSectionThreeProductCollection(): void
    {
        $categoryId = 57;
        $pageSize = 5;

        // Category ID
        $this->helper->expects($this->once())
            ->method('getSectionThreeCategory')
            ->willReturn($categoryId);

        // Page Size
        $this->helper->expects($this->once())
            ->method('getSectionThreeProductCount')
            ->willReturn($pageSize);

        // Category
        $categoryMock = $this->getMockBuilder(Category::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->helper->expects($this->once())
            ->method('getCategoryById')
            ->with($categoryId)
            ->willReturn($categoryMock);

        // Product Collection
        $collectionMock = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->helper->expects($this->once())
            ->method('getProductCollectionByCategories')
            ->with($categoryMock, $pageSize)
            ->willReturn($collectionMock);

        $this->assertEquals($collectionMock, $this->object->getSectionThreeProductCollection());
    }

    public function testGetCategory(): void
    {
        // Category Id
        $this->helper->expects($this->any())
            ->method('getSectionThreeCategory')
            ->willReturnOnConsecutiveCalls('', '115');

        // Category
        $categoryMock = $this->getMockBuilder(Category::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->helper->expects($this->once())
            ->method('getCategoryById')
            ->with('115')
            ->willReturn($categoryMock);

        $this->assertNull($this->object->getCategory());
        $this->assertEquals($categoryMock, $this->object->getCategory());
    }

    public function testGetProductImageUrl(): void
    {
        $imageUrl = 'https://brand-labels.com/media/catalog/product/placeholder/placeholder-image.jpg';

        //Product
        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->helper->expects($this->once())
            ->method('getProductImageUrl')
            ->with($productMock)
            ->willReturn($imageUrl);

        $this->assertEquals($imageUrl, $this->object->getProductImageUrl($productMock));
    }

    public function testGetWishListItems(): void
    {
        $wishListItemIds = [1, 2, 3, 4, 5];
        $this->helper->expects($this->any())
            ->method('getCustomerId')
            ->willReturnOnConsecutiveCalls(false, '13');
        $this->helper->expects($this->any())
            ->method('getWishListCollectionIds')
            ->willReturn($wishListItemIds);

        $this->assertEmpty($this->object->getWishListItems());
        $this->assertEquals($wishListItemIds, $this->object->getWishListItems());
    }

    public function testGetProductPrice(): void
    {
        $price = '163.33';

        //Product
        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        $productMock->expects($this->once())
            ->method('getPrice')
            ->willReturn($price);

        $this->helper->expects($this->once())
            ->method('getFormattedPrice')
            ->with($price)
            ->willReturn($price);

        $this->assertEquals($price, $this->object->getProductPrice($productMock));
    }

    public function testSectionThreeInstance(): void
    {
        $this->assertInstanceOf(ListProduct::class, $this->object);
    }
}
