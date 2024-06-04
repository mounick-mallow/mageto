<?php
declare(strict_types=1);

namespace LuxuryUnlimited\HomeProductSection\Test\Unit\Block;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use LuxuryUnlimited\HomeProductSection\Block\SectionTwo;
use Magento\Catalog\Block\Product\Context;
use LuxuryUnlimited\HomeProductSection\Helper\Data;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\ResourceModel\Product\Collection;

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

        $this->object = new SectionTwo(
            $this->context,
            $this->postDataHelper,
            $this->layerResolver,
            $this->categoryRepository,
            $this->urlHelper,
            $this->helper
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

    public function testGetSectionTwoProductCollectionEmpty(): void
    {
        $this->helper->expects($this->once())
            ->method('getSectionTwoCategory')
            ->willReturn('');
        $this->assertEmpty($this->object->getSectionTwoProductCollection());
    }

    public function testGetSectionTwoProductCollection(): void
    {
        $categoryId = 57;
        $pageSize = 5;

        // Category ID
        $this->helper->expects($this->once())
            ->method('getSectionTwoCategory')
            ->willReturn($categoryId);

        // Page Size
        $this->helper->expects($this->once())
            ->method('getSectionTwoProductCount')
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

        $this->assertEquals($collectionMock, $this->object->getSectionTwoProductCollection());
    }

    public function testGetCategory(): void
    {
        // Category Id
        $this->helper->expects($this->any())
            ->method('getSectionTwoCategory')
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

    public function testGetSimpleData(): void
    {
        $price = '125.00';
        $specialPrice = '109.00';
        $mediaUrl = 'https://brands-labels.com/media/';
        $imageUrl = 'image.jpg';
        $placeholder = 'product-placeholder.jpg';

        $image = [
            'image' => $mediaUrl . 'catalog/product' . $imageUrl,
            'price' => $price,
            'special_price' => $specialPrice
        ];

        $imagePlaceholder = [
            'image' => $placeholder,
            'price' => $price,
            'special_price' => ''
        ];

        // Media Url
        $this->helper->expects($this->any())
            ->method('getMediaUrl')
            ->willReturn($mediaUrl);

        // Product
        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Image Url
        $this->helper->expects($this->any())
            ->method('getMediaGalleryImageUrl')
            ->with($productMock)
            ->willReturnOnConsecutiveCalls($imageUrl, null);

        // Placeholder
        $this->helper->expects($this->any())
            ->method('getPlaceHolderImage')
            ->willReturn($placeholder);

        // Product Price
        $productMock->expects($this->any())
            ->method('getPrice')
            ->willReturn($price);
        $productMock->expects($this->any())
            ->method('getSpecialPrice')
            ->willReturnOnConsecutiveCalls($specialPrice, '');
        $this->helper->expects($this->any())
            ->method('getFormattedPrice')
            ->withConsecutive(
                [$price],
                [$specialPrice],
                [$price]
            )
            ->willReturnOnConsecutiveCalls($price, $specialPrice, $price);

        $this->assertEquals($image, $this->object->getSimpleData($productMock));
        $this->assertEquals($imagePlaceholder, $this->object->getSimpleData($productMock));
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

    public function testSectionTwoInstance(): void
    {
        $this->assertInstanceOf(ListProduct::class, $this->object);
    }
}
