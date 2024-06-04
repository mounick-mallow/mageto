<?php declare(strict_types=1);

namespace LuxuryUnlimited\HomeApi\Test\Unit\Model\Api;

use Magento\Catalog\Model\Category;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use LuxuryUnlimited\HomeApi\Model\Api\ProductSection;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use LuxuryUnlimited\HomeProductSection\Helper\Data;
use LuxuryUnlimited\MostPopular\Helper\Data as MostHelper;
use LuxuryUnlimited\SaleProducts\Helper\Data as SalesHelper;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ProductSectionTest extends TestCase
{
    /**
     * @var ProductSection $object
     */
    private ProductSection $object;

    /**
     * @var MockObject $logger
     */
    private MockObject $logger;

    /**
     * @var MockObject $productCollectionFactory
     */
    private MockObject $productCollectionFactory;

    /**
     * @var MockObject $storeManager
     */
    private MockObject $storeManager;

    /**
     * @var MockObject $helper
     */
    private MockObject $helper;

    /**
     * @var MockObject $productStatus
     */
    private MockObject $productStatus;

    /**
     * @var MockObject $mostHelper
     */
    private MockObject $mostHelper;

    /**
     * @var MockObject $salesHelper
     */
    private MockObject $salesHelper;

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
        $this->productCollectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->helper = $this->getMockBuilder(Data::class)
           ->disableOriginalConstructor()
           ->getMock();
        $this->mostHelper = $this->getMockBuilder(MostHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->salesHelper = $this->getMockBuilder(SalesHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->productStatus = $this->getMockBuilder(Status::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new ProductSection(
            $this->logger,
            $this->storeManager,
            $this->productCollectionFactory,
            $this->helper,
            $this->mostHelper,
            $this->salesHelper,
            $this->productStatus
        );
    }

    public function testGetProductSectionInvalidSectionType(): void
    {
        $this->assertEquals(
            ['success' => false, 'message' => "invalid section type or category not selected in config"],
            $this->object->getProductSection('')
        );
    }

    public function testGetProductSectionDisableSection(): void
    {
        $this->helper->expects($this->once())->method('isEnabledSectionOne')->willReturn('0');
        $this->assertEquals(
            ['success' => false, 'message' => 'first section is not enabled'],
            $this->object->getProductSection(1)
        );
    }

    public function testGetProductSection(): void
    {
        // Initial data
        $section = $limit = 3;
        $categoryId = 15;
        $responseData = [
            'entity_id' => 1,
            'sku' => 'sku#1'
        ];

        // Is enabled section
        $this->helper->expects($this->once())
            ->method('isEnabledSectionThree')
            ->willReturn('1');

        // Get section item limit
        $this->helper->expects($this->once())
            ->method('getSectionThreeProductCount')
            ->willReturn($limit);

        // Get section category
        $this->helper->expects($this->once())
            ->method('getSectionThreeCategory')
            ->willReturn($categoryId);

        // Category
        $categoryMock = $this->getMockBuilder(Category::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->helper->expects($this->once())
            ->method('getCategoryById')
            ->with($categoryId)
            ->willReturn($categoryMock);

        // Product Collection
        $productCollectionMock = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->helper->expects($this->once())
            ->method('getProductCollectionByCategories')
            ->with($categoryMock, $limit)
            ->willReturn($productCollectionMock);

        // Product
        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        $productMock->setData('entity_id', 1);
        $productMock->setData('sku', 'sku#1');

        $productCollectionMock->expects($this->once())->method('getIterator')->willReturn(
            new \ArrayIterator([$productMock])
        );
        $productMock->expects($this->any())->method('getData')->willReturn($responseData);

        // Execute
        $this->assertEquals(
            ['success' => true, 'message' => [$responseData]],
            $this->object->getProductSection($section)
        );
    }

    public function testGetProductSectionMissingCategoryId(): void
    {
        // Initial data
        $section = $limit = 2;
        $responseData = [
            'success' => false,
            'message' => 'invalid section type or category not selected in config'
        ];

        // Is enabled section
        $this->helper->expects($this->once())
            ->method('isEnabledSectionTwo')
            ->willReturn('1');

        // Get section item limit
        $this->helper->expects($this->once())
            ->method('getSectionTwoProductCount')
            ->willReturn($limit);

        // Get section category
        $this->helper->expects($this->once())
            ->method('getSectionTwoCategory')
            ->willReturn('');

        $this->assertEquals(
            $responseData,
            $this->object->getProductSection($section)
        );
    }

    public function testGetProductSectionDataException(): void
    {
        // Initial data
        $section = $limit = 1;
        $responseData = new NoSuchEntityException(new Phrase(
            'No such entity with %fieldName = %fieldValue',
            [
                'fieldName' => 'id',
                'fieldValue' => 18
            ]
        ));

        // Is enabled section
        $this->helper->expects($this->once())
            ->method('isEnabledSectionOne')
            ->willReturn('1');

        // Get section item limit
        $this->helper->expects($this->once())
            ->method('getSectionOneProductCount')
            ->willReturn($limit);

        // Get section category
        $this->helper->expects($this->once())
            ->method('getSectionOneCategory')
            ->willThrowException($responseData);
        $this->logger->expects($this->once())
            ->method('info')
            ->with($responseData->getMessage());

        // Execute
        $this->assertEquals(
            ['success' => false, 'message' => $responseData->getMessage()],
            $this->object->getProductSection($section)
        );
    }

    public function testGetPromoProductsInvalidPromoType(): void
    {
        $this->assertEquals(
            ['success' => false, 'message' => 'invalid promo type or category not selected in config'],
            $this->object->getPromoProducts('')
        );
    }

    public function testGetPromoProductsDisableMostPopularProduct(): void
    {
        // Initial data
        $promoType = 'most';
        $this->mostHelper->expects($this->once())
            ->method('isEnabledMostPopular')
            ->willReturn('0');

        // Execute
        $this->assertEquals(
            ['success' => false, 'message' => 'Most popular is not enabled'],
            $this->object->getPromoProducts($promoType)
        );
    }

    public function testGetPromoProductsDisableSalesProduct(): void
    {
        // Initial data
        $promoType = 'sales';
        $this->salesHelper->expects($this->once())
            ->method('isEnabledSaleProducts')
            ->willReturn('0');

        // Execute
        $this->assertEquals(
            ['success' => false, 'message' => 'Sales Products is not enabled'],
            $this->object->getPromoProducts($promoType)
        );
    }

    public function testGetPromoProductsMostPopular(): void
    {
        // Initial data
        $promoType = 'most';
        $categoryId = 7;
        $limit = 3;
        $responseData = [
            'entity_id' => 1,
            'sku' => 'sku#1'
        ];

        // Is enabled most popular products
        $this->mostHelper->expects($this->once())
            ->method('isEnabledMostPopular')
            ->willReturn('1');

        // Limit
        $this->mostHelper->expects($this->once())
            ->method('getMostPopularProductCount')
            ->willReturn($limit);

        // Category
        $this->mostHelper->expects($this->once())
            ->method('getMostPopularCategory')
            ->willReturn($categoryId);
        $categoryMock = $this->getMockBuilder(Category::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mostHelper->expects($this->once())
            ->method('getCategoryById')
            ->with($categoryId)
            ->willReturn($categoryMock);

        // Product Collection
        $productCollectionMock = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mostHelper->expects($this->once())
            ->method('getProductCollectionByCategories')
            ->with($categoryMock, $limit)
            ->willReturn($productCollectionMock);

        // Product
        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        $productMock->setData('entity_id', 1);
        $productMock->setData('sku', 'sku#1');

        $productCollectionMock->expects($this->once())->method('getIterator')->willReturn(
            new \ArrayIterator([$productMock])
        );
        $productMock->expects($this->any())->method('getData')->willReturn($responseData);

        // Execute
        $this->assertEquals(
            ['success' => true, 'message' => [$responseData]],
            $this->object->getPromoProducts($promoType)
        );
    }

    public function testGetPromoProductsSalesProduct(): void
    {
        // Initial data
        $promoType = 'sales';
        $categoryId = 7;
        $limit = 3;
        $responseData = [
            'entity_id' => 2,
            'sku' => 'sku#2'
        ];

        // Is enabled most popular products
        $this->salesHelper->expects($this->once())
            ->method('isEnabledSaleProducts')
            ->willReturn('1');

        // Limit
        $this->salesHelper->expects($this->once())
            ->method('getSaleProductsProductCount')
            ->willReturn($limit);

        // Category
        $this->salesHelper->expects($this->once())
            ->method('getSaleProductsCategory')
            ->willReturn($categoryId);
        $categoryMock = $this->getMockBuilder(Category::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->salesHelper->expects($this->once())
            ->method('getCategoryById')
            ->with($categoryId)
            ->willReturn($categoryMock);

        // Product Collection
        $productCollectionMock = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->salesHelper->expects($this->once())
            ->method('getProductCollectionByCategories')
            ->with($categoryMock, $limit)
            ->willReturn($productCollectionMock);

        // Product
        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        $productMock->setData('entity_id', 2);
        $productMock->setData('sku', 'sku#2');

        $productCollectionMock->expects($this->once())->method('getIterator')->willReturn(
            new \ArrayIterator([$productMock])
        );
        $productMock->expects($this->any())->method('getData')->willReturn($responseData);

        // Execute
        $this->assertEquals(
            ['success' => true, 'message' => [$responseData]],
            $this->object->getPromoProducts($promoType)
        );
    }

    public function testGetPromoProductsMissingCategoryId(): void
    {
        // Initial data
        $promoType = 'sales';
        $limit = 2;
        $responseData = new NoSuchEntityException(new Phrase(
            'No such entity with %fieldName = %fieldValue',
            [
                'fieldName' => 'id',
                'fieldValue' => 15
            ]
        ));

        // Is enabled section
        $this->salesHelper->expects($this->once())
            ->method('isEnabledSaleProducts')
            ->willReturn('1');

        // Get section item limit
        $this->salesHelper->expects($this->once())
            ->method('getSaleProductsProductCount')
            ->willReturn($limit);

        // Get section category
        $this->salesHelper->expects($this->once())
            ->method('getSaleProductsCategory')
            ->willReturn('');
        $this->salesHelper->expects($this->once())
            ->method('getCategoryById')
            ->with('')
            ->willThrowException($responseData);

        // Execute
        $this->assertEquals(
            ['success' => false, 'message' => $responseData->getMessage()],
            $this->object->getPromoProducts($promoType)
        );
    }
}
