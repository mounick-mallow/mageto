<?php declare(strict_types=1);

namespace LuxuryUnlimited\HomeProductSection\Test\Unit\Helper;

use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use LuxuryUnlimited\HomeProductSection\Helper\Data;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Session\SessionManager;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Wishlist\Model\Wishlist;
use Magento\Catalog\Helper\Image;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class DataTest extends TestCase
{
    /**
     * @var Data $object
     */
    private Data $object;

    /**
     * @var MockObject $scopeConfig
     */
    private MockObject $scopeConfig;

    /**
     * @var MockObject $storeManager
     */
    private MockObject $storeManager;

    /**
     * @var MockObject $categoryRepository
     */
    private MockObject $categoryRepository;

    /**
     * @var MockObject $productModel
     */
    private MockObject $productModel;

    /**
     * @var MockObject $priceHelper
     */
    private MockObject $priceHelper;

    /**
     * @var MockObject $customerSession
     */
    private MockObject $customerSession;

    /**
     * @var MockObject $sessionManager
     */
    private MockObject $sessionManager;

    /**
     * @var MockObject $sessionManagerInterface
     */
    private MockObject $sessionManagerInterface;

    /**
     * @var MockObject $wishList
     */
    private MockObject $wishList;

    /**
     * @var MockObject $image
     */
    private MockObject $image;

    /**
     * @var MockObject $store
     */
    private MockObject $store;

    protected function setUp(): void
    {
         $this->scopeConfig = $this->getMockForAbstractClass(
             ScopeConfigInterface::class,
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
             []
         );
         $this->categoryRepository = $this->getMockBuilder(CategoryRepository::class)
             ->disableOriginalConstructor()
             ->getMock();
         $this->productModel = $this->getMockBuilder(ProductFactory::class)
             ->disableOriginalConstructor()
             ->getMock();
         $this->priceHelper = $this->getMockBuilder(PriceHelper::class)
             ->disableOriginalConstructor()
             ->getMock();
         $this->customerSession = $this->getMockBuilder(CustomerSession::class)
             ->disableOriginalConstructor()
             ->getMock();
         $this->sessionManager = $this->getMockBuilder(SessionManager::class)
             ->disableOriginalConstructor()
             ->getMock();
         $this->sessionManagerInterface = $this->getMockForAbstractClass(
             SessionManagerInterface::class,
             [],
             '',
             false,
             false,
             true,
             []
         );
         $this->wishList = $this->getMockBuilder(Wishlist::class)
             ->disableOriginalConstructor()
             ->addMethods(['getAddParams'])
             ->onlyMethods(['loadByCustomerId', 'getItemCollection'])
             ->getMock();
        $this->image = $this->getMockBuilder(Image::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->store = $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->addMethods(['getStoreId'])
            ->onlyMethods(['getBaseUrl'])
            ->getMock();

        $this->object = new Data(
            $this->scopeConfig,
            $this->storeManager,
            $this->categoryRepository,
            $this->productModel,
            $this->priceHelper,
            $this->customerSession,
            $this->sessionManager,
            $this->sessionManagerInterface,
            $this->wishList,
            $this->image
        );
    }

    public function testGetSectionOneCategory(): void
    {
        $expectedResult = '12';
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->any())
            ->method('getStoreId')
            ->willReturn(115);
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->with(Data::SECTION_ONE_CATEGORY, ScopeInterface::SCOPE_STORE, 115)
            ->willReturnOnConsecutiveCalls($expectedResult, '');

        $this->assertEquals($expectedResult, $this->object->getSectionOneCategory());
        $this->assertEmpty($this->object->getSectionOneCategory());
    }

    public function testGetSectionTwoCategory(): void
    {
        $expectedResult = '12';
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->any())
            ->method('getStoreId')
            ->willReturn(115);
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->with(Data::SECTION_TWO_CATEGORY, ScopeInterface::SCOPE_STORE, 115)
            ->willReturnOnConsecutiveCalls($expectedResult, '');

        $this->assertEquals($expectedResult, $this->object->getSectionTwoCategory());
        $this->assertEmpty($this->object->getSectionTwoCategory());
    }

    public function testGetSectionThreeCategory(): void
    {
        $expectedResult = '12';
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->any())
            ->method('getStoreId')
            ->willReturn(115);
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->with(Data::SECTION_THREE_CATEGORY, ScopeInterface::SCOPE_STORE, 115)
            ->willReturnOnConsecutiveCalls($expectedResult, '');

        $this->assertEquals($expectedResult, $this->object->getSectionThreeCategory());
        $this->assertEmpty($this->object->getSectionThreeCategory());
    }

    public function testIsEnabledSectionOne(): void
    {
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->any())
            ->method('getStoreId')
            ->willReturn(115);
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->with(Data::SECTION_ONE_ENABLED, ScopeInterface::SCOPE_STORE, 115)
            ->willReturnOnConsecutiveCalls('1', '0', '');

        $this->assertEquals('1', $this->object->isEnabledSectionOne());
        $this->assertEquals('0', $this->object->isEnabledSectionOne());
        $this->assertEmpty($this->object->isEnabledSectionOne());
    }

    public function testIsEnabledSectionTwo(): void
    {
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->any())
            ->method('getStoreId')
            ->willReturn(115);
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->with(Data::SECTION_TWO_ENABLED, ScopeInterface::SCOPE_STORE, 115)
            ->willReturnOnConsecutiveCalls('1', '0', '');

        $this->assertEquals('1', $this->object->isEnabledSectionTwo());
        $this->assertEquals('0', $this->object->isEnabledSectionTwo());
        $this->assertEmpty($this->object->isEnabledSectionTwo());
    }

    public function testIsEnabledSectionThree(): void
    {
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->any())
            ->method('getStoreId')
            ->willReturn(115);
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->with(Data::SECTION_THREE_ENABLED, ScopeInterface::SCOPE_STORE, 115)
            ->willReturnOnConsecutiveCalls('1', '0', '');

        $this->assertEquals('1', $this->object->isEnabledSectionThree());
        $this->assertEquals('0', $this->object->isEnabledSectionThree());
        $this->assertEmpty($this->object->isEnabledSectionThree());
    }

    public function testGetSectionOneProductCount(): void
    {
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->any())
            ->method('getStoreId')
            ->willReturn(115);
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->with(Data::SECTION_ONE_PRODUCT_COUNT, ScopeInterface::SCOPE_STORE, 115)
            ->willReturnOnConsecutiveCalls('3', Data::SECTION_ONE_PRODUCT_COUNT);

        $this->assertEquals('3', $this->object->getSectionOneProductCount());
        $this->assertEquals(Data::SECTION_ONE_PRODUCT_COUNT, $this->object->getSectionOneProductCount());
    }

    public function testGetSectionTwoProductCount(): void
    {
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->any())
            ->method('getStoreId')
            ->willReturn(115);
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->with(Data::SECTION_TWO_PRODUCT_COUNT, ScopeInterface::SCOPE_STORE, 115)
            ->willReturnOnConsecutiveCalls('3', Data::SECTION_TWO_PRODUCT_COUNT);

        $this->assertEquals('3', $this->object->getSectionTwoProductCount());
        $this->assertEquals(Data::SECTION_TWO_PRODUCT_COUNT, $this->object->getSectionTwoProductCount());
    }

    public function testGetSectionThreeProductCount(): void
    {
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->any())
            ->method('getStoreId')
            ->willReturn(115);
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->with(Data::SECTION_THREE_PRODUCT_COUNT, ScopeInterface::SCOPE_STORE, 115)
            ->willReturnOnConsecutiveCalls('3', Data::SECTION_THREE_PRODUCT_COUNT);

        $this->assertEquals('3', $this->object->getSectionThreeProductCount());
        $this->assertEquals(Data::SECTION_THREE_PRODUCT_COUNT, $this->object->getSectionThreeProductCount());
    }

    public function testGetPlaceholderImage(): void
    {
        $mediaUrl = 'https://brand-labels.com/media/';
        $expectedResult = $mediaUrl . 'catalog/product/placeholder/placeholder.jpg';
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->any())
            ->method('getBaseUrl')
            ->with(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
            ->willReturn($mediaUrl);
        $this->store->expects($this->any())
            ->method('getStoreId')
            ->willReturn(115);
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->with(Data::PLACE_HOLDER, ScopeInterface::SCOPE_STORE, 115)
            ->willReturnOnConsecutiveCalls('placeholder.jpg', '');

        $this->assertEquals($expectedResult, $this->object->getPlaceHolderImage());
        $this->assertEmpty($this->object->getPlaceHolderImage());
    }

    public function testGetAddWishlistParams(): void
    {
        $productUrl = 'https://brand-labels.com/some-product';
        $expectedResult = json_encode($productUrl);
        $productMock = $this->getMockBuilder(\Magento\Catalog\Model\Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->wishList->expects($this->any())
            ->method('getAddParams')
            ->willReturn($expectedResult);

        $this->assertEquals($expectedResult, $this->object->getAddToWishlistParams($productMock));
    }

    public function testGetProductImageUrl(): void
    {
        $expectedResult = 'https://brand-labels.com/media/catalog/product/a/c/some-image.jpg';
        $expectedPlaceholderResult = 'https://brand-labels.com/media/catalog/product/placeholder.jpg';
        $productMock = $this->getMockBuilder(\Magento\Catalog\Model\Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->image->expects($this->any())
            ->method('init')
            ->with($productMock, 'product_base_image')
            ->willReturnSelf();
        $this->image->expects($this->any())
            ->method('constrainOnly')
            ->with(true)
            ->willReturnSelf();
        $this->image->expects($this->any())
            ->method('keepAspectRatio')
            ->with(true)
            ->willReturnSelf();
        $this->image->expects($this->any())
            ->method('keepFrame')
            ->with(false)
            ->willReturnSelf();
        $this->image->expects($this->any())
            ->method('getUrl')
            ->willReturnOnConsecutiveCalls($expectedResult, $expectedPlaceholderResult);

        $this->assertEquals($expectedResult, $this->object->getProductImageUrl($productMock));
        $this->assertEquals($expectedPlaceholderResult, $this->object->getProductImageUrl($productMock));
    }

    public function testGetProductCollectionByCategories(): void
    {
        $pageSize = 10;
        $categoryMock = $this->getMockBuilder(\Magento\Catalog\Model\Category::class)
            ->disableOriginalConstructor()
            ->getMock();
        $productCollectionMock = $this->getMockBuilder(\Magento\Catalog\Model\ResourceModel\Product\Collection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $categoryMock->expects($this->any())
            ->method('getProductCollection')
            ->willReturn($productCollectionMock);
        $productCollectionMock->expects($this->any())
            ->method('addAttributeToSelect')
            ->with('*')
            ->willReturnSelf();
        $productCollectionMock->expects($this->any())
            ->method('setCurPage')
            ->with(1)
            ->willReturnSelf();
        $productCollectionMock->expects($this->any())
            ->method('setPageSize')
            ->with($pageSize)
            ->willReturnSelf();

        $this->assertEquals(
            $productCollectionMock,
            $this->object->getProductCollectionByCategories($categoryMock, $pageSize)
        );
    }

    public function testGetCategoryById(): void
    {
        $categoryMock = $this->getMockBuilder(\Magento\Catalog\Model\Category::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->any())
            ->method('getStoreId')
            ->willReturn(115);
        $this->categoryRepository->expects($this->any())
            ->method('get')
            ->with(15, 115)
            ->willReturn($categoryMock);

        $this->assertEquals($categoryMock, $this->object->getCategoryById(15));
    }

    public function testGetCategoryByIdException(): void
    {
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->any())
            ->method('getStoreId')
            ->willReturn(115);
        $this->categoryRepository->expects($this->any())
            ->method('get')
            ->with(15, 115)
            ->willThrowException(new NoSuchEntityException(__('No such entity with id = 15')));

        $this->expectException(NoSuchEntityException::class);
        $this->expectExceptionMessage('No such entity with id = 15');
        $this->object->getCategoryById(15);
    }

    public function testGetProductUrl(): void
    {
        $expectedResult = 'https://brand-labels.com/gb-en/some-product';
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->any())
            ->method('getStoreId')
            ->willReturn(115);
        $productMock = $this->getMockBuilder(\Magento\Catalog\Model\Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->productModel->expects($this->any())
            ->method('create')
            ->willReturn($productMock);
        $productMock->expects($this->any())
            ->method('load')
            ->with('123')
            ->willReturnSelf();
        $productMock->expects($this->any())
            ->method('setStoreId')
            ->with(115)
            ->willReturnSelf();
        $productMock->expects($this->any())
            ->method('getProductUrl')
            ->willReturn($expectedResult);

        $this->assertEquals($expectedResult, $this->object->getProductUrl('123'));
    }

    public function testGetFormattedPrice(): void
    {
        $this->priceHelper->expects($this->once())
            ->method('currency')
            ->with('15.323', true, false)
            ->willReturn('15.32');
        $this->assertEquals('15.32', $this->object->getFormattedPrice('15.323'));
    }

    public function testGetMediaGalleryImageUrl(): void
    {
        $expectedResult = '/a/c/some-image-url.jpg';
        $productMock = $this->getMockBuilder(\Magento\Catalog\Model\Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        $productMock->expects($this->once())
            ->method('getImage')
            ->willReturn($expectedResult);

        $this->assertEquals($expectedResult, $this->object->getMediaGalleryImageUrl($productMock));
    }

    public function testIsCustomerLoggedIn(): void
    {
        $expectedResult = [
            'visitor_data' => [
                'do_customer_login' => 1
            ]
        ];
        $this->sessionManager->expects($this->any())
            ->method('getData')
            ->willReturn($expectedResult);

        $this->assertIsBool($this->object->isCustomerLoggedIn());
    }

    public function testGetCustomer(): void
    {
        $expectedResult = [
            'visitor_data' => [
                'do_customer_login' => 1
            ]
        ];
        $this->sessionManager->expects($this->any())
            ->method('getData')
            ->willReturnOnConsecutiveCalls($expectedResult, []);

        $this->assertEquals($expectedResult, $this->object->getCustomer());
        $this->assertEquals([], $this->object->getCustomer());
    }

    public function testGetCustomerId(): void
    {
        $expectedResult = [
            'visitor_data' => [
                'do_customer_login' => 1,
                'customer_id' => 89
            ]
        ];
        $this->sessionManager->expects($this->any())
            ->method('getData')
            ->willReturnOnConsecutiveCalls($expectedResult, $expectedResult, $expectedResult, [], []);

        $this->assertEquals('89', $this->object->getCustomerId());
        $this->assertEquals('', $this->object->getCustomerId());
        $this->assertIsBool($this->object->getCustomerId());
    }

    public function testGetWishListCollectionIds(): void
    {
        $customerData = [
            'visitor_data' => [
                'do_customer_login' => 1,
                'customer_id' => 89
            ]
        ];
        $this->sessionManager->expects($this->any())
            ->method('getData')
            ->willReturn($customerData, $customerData);
        $this->wishList->expects($this->any())
            ->method('loadByCustomerId')
            ->with('89')
            ->willReturnSelf();
        $wishListItemMock = $this->getMockBuilder(\Magento\Wishlist\Model\Item::class)
            ->disableOriginalConstructor()
            ->addMethods(['getProductId'])
            ->getMock();
        $wishListCollectionMock = $this->getMockBuilder(\Magento\Wishlist\Model\ResourceModel\Item\Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->wishList->expects($this->any())
            ->method('getItemCollection')
            ->willReturn($wishListCollectionMock);
        $wishListCollectionMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator([$wishListItemMock]));
        $wishListItemMock->expects($this->any())
            ->method('getProductId')
            ->willReturn(23);

        $this->assertEquals([23], $this->object->getWishListCollectionIds());
    }

    public function testGetCustomerData(): void
    {
        $customerMock = $this->getMockBuilder(\Magento\Customer\Model\Customer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->customerSession->expects($this->any())
            ->method('getCustomer')
            ->willReturnOnConsecutiveCalls($customerMock, $customerMock);
        $this->assertEquals($customerMock, $this->object->getCustomerData());
    }

    public function testGetStoreData(): void
    {
        $this->storeManager->expects($this->once())
            ->method('getStore')
            ->willReturn($this->store);
        $this->assertEquals($this->store, $this->object->getStoreData());
    }
}
