<?php declare(strict_types=1);

namespace LuxuryUnlimited\HomeCategorySection\Test\Unit\Helper;

use Magento\Framework\UrlInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use LuxuryUnlimited\HomeCategorySection\Helper\Data;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;

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
     * @var MockObject $helper
     */
    private MockObject $helper;

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
        $this->helper = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->store = $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new Data(
            $this->scopeConfig,
            $this->storeManager
        );
    }

    public function testIsEnableSectionOne(): void
    {
        $this->helper->expects($this->any())
            ->method('getConfigValue')
            ->with(Data::SECTION_ONE_ENABLED)
            ->willReturnOnConsecutiveCalls('0', '1');
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->any())
            ->method('getId')
            ->willReturn(115);
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->with(Data::SECTION_ONE_ENABLED)
            ->willReturnOnConsecutiveCalls('0', '1');

        $this->assertEquals('0', $this->object->isEnabledSectionOne());
        $this->assertEquals('1', $this->object->isEnabledSectionOne());
    }

    public function testGetCategorySectionOneCount(): void
    {
        $this->helper->expects($this->any())
            ->method('getConfigValue')
            ->with(Data::SECTION_ONE_ENABLED)
            ->willReturnOnConsecutiveCalls('0', '1');
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->any())
            ->method('getId')
            ->willReturn(115);
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->with(Data::SECTION_ONE_CATEGORY_COUNT)
            ->willReturnOnConsecutiveCalls('3');

        $this->assertEquals('3', $this->object->getCategorySectionOneCount());
    }

    public function testGetPlaceholderImage(): void
    {
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->any())
            ->method('getBaseUrl')
            ->with(UrlInterface::URL_TYPE_MEDIA)
            ->willReturn('https://brand-labels.com/media/');
        $this->store->expects($this->any())
            ->method('getId')
            ->willReturn(115);
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->with(Data::PLACE_HOLDER)
            ->willReturnOnConsecutiveCalls('', 'placeholder-image.jpg');

        $this->assertEquals(
            '',
            $this->object->getPlaceHolderImage()
        );
        $this->assertEquals(
            'https://brand-labels.com/media/catalog/product/placeholder/placeholder-image.jpg',
            $this->object->getPlaceHolderImage()
        );
    }

    public function testIsEnableSectionTwo(): void
    {
        $this->helper->expects($this->any())
            ->method('getConfigValue')
            ->with(Data::SECTION_TWO_ENABLED)
            ->willReturnOnConsecutiveCalls('0', '1');
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->any())
            ->method('getId')
            ->willReturn(115);
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->with(Data::SECTION_TWO_ENABLED)
            ->willReturnOnConsecutiveCalls('0', '1');

        $this->assertEquals('0', $this->object->isEnabledSectionTwo());
        $this->assertEquals('1', $this->object->isEnabledSectionTwo());
    }

    public function testGetCategorySectionTwoCount(): void
    {
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->any())
            ->method('getId')
            ->willReturn(115);
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->with(Data::SECTION_TWO_CATEGORY_COUNT)
            ->willReturnOnConsecutiveCalls('0', '3');

        $this->assertEquals('0', $this->object->getCategorySectionTwoCount());
        $this->assertEquals('3', $this->object->getCategorySectionTwoCount());
    }

    public function testHelperInstance(): void
    {
        $this->assertInstanceOf(AbstractHelper::class, $this->object);
    }
}
