<?php declare(strict_types=1);

namespace LuxuryUnlimited\HomeCategorySection\Test\Unit\Controller\Adminhtml\Category\Image;

use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\TestCase;
use LuxuryUnlimited\HomeCategorySection\Controller\Adminhtml\Category\Image\UploadOne;
use PHPUnit\Framework\MockObject\MockObject;
use Magento\Catalog\Model\ImageUploader;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Psr\Log\LoggerInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Filesystem;
use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Json;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UploadOneTest extends TestCase
{
    /**
     * @var UploadOne $object
     */
    private UploadOne $object;

    /**
     * @var MockObject $context
     */
    private MockObject $context;

    /**
     * @var MockObject $imageUploader
     */
    private MockObject $imageUploader;

    /**
     * @var MockObject $uploaderFactory
     */
    private MockObject $uploaderFactory;

    /**
     * @var MockObject $filesystem
     */
    private MockObject $filesystem;

    /**
     * @var MockObject $storeManager
     */
    private MockObject $storeManager;

    /**
     * @var MockObject $coreFileStorageDatabase
     */
    private MockObject $coreFileStorageDatabase;

    /**
     * @var MockObject $logger
     */
    private MockObject $logger;

    /**
     * @var MockObject $result
     */
    private MockObject $result;

    protected function setUp(): void
    {
        $this->context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->imageUploader = $this->getMockBuilder(ImageUploader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->uploaderFactory = $this->getMockBuilder(UploaderFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->filesystem = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->storeManager = $this->getMockForAbstractClass(
            StoreManagerInterface::class,
            [],
            '',
            false,
            false,
            true,
            []
        );
        $this->coreFileStorageDatabase = $this->getMockBuilder(Database::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->logger = $this->getMockForAbstractClass(
            LoggerInterface::class,
            [],
            '',
            false,
            false,
            true,
            []
        );

        // Session
        $sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();
        $sessionMock->expects($this->any())
            ->method('getName')
            ->willReturn('some-cookie-name');
        $sessionMock->expects($this->any())
            ->method('getSessionId')
            ->willReturn('PHPSID=123');
        $sessionMock->expects($this->any())
            ->method('getCookieLifetime')
            ->willReturn(2000);
        $sessionMock->expects($this->any())
            ->method('getCookiePath')
            ->willReturn('some-cookie-path');
        $sessionMock->expects($this->any())
            ->method('getCookieDomain')
            ->willReturn('http://some-cookie-domain/');

        // Result Json Factory
        $this->result = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->getMock();
        $resultJsonFactory = $this->getMockBuilder(ResultFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $resultJsonFactory->expects($this->any())
            ->method('create')
            ->with(ResultFactory::TYPE_JSON)
            ->willReturn($this->result);

        // Context
        $this->context->expects($this->any())
            ->method('getSession')
            ->willReturn($sessionMock);
        $this->context->expects($this->any())
            ->method('getResultFactory')
            ->willReturn($resultJsonFactory);

        $this->object = new UploadOne(
            $this->context,
            $this->imageUploader,
            $this->uploaderFactory,
            $this->filesystem,
            $this->storeManager,
            $this->coreFileStorageDatabase,
            $this->logger
        );
    }

    public function testExecuteActionInstance(): void
    {
        $this->assertInstanceOf(Action::class, $this->object);
    }

    public function testExecute(): void
    {
        $responseData = [
            'cookie' => [
                'name' => 'some-cookie-name',
                'value' => 'PHPSID=123',
                'lifetime' => 2000,
                'path' => 'some-cookie-path',
                'domain' => 'http://some-cookie-domain/',
            ]
        ];
        $this->imageUploader->expects($this->once())
            ->method('setBaseTmpPath')
            ->with('catalog/tmp/category');
        $this->imageUploader->expects($this->once())
            ->method('saveFileToTmpDir')
            ->with('section_one_image')
            ->willReturn([]);
        $this->result->expects($this->once())
            ->method('setData')
            ->with($responseData)
            ->willReturnSelf();

        $this->assertEquals($this->result, $this->object->execute());
    }

    public function testExecuteException(): void
    {
        $responseData = [
            'error' => 'File can not be saved to the destination folder.',
            'errorcode' => 0
        ];
        $this->imageUploader->expects($this->once())
            ->method('setBaseTmpPath')
            ->with('catalog/tmp/category');
        $this->imageUploader->expects($this->once())
            ->method('saveFileToTmpDir')
            ->with('section_one_image')
            ->willThrowException(new LocalizedException(__('File can not be saved to the destination folder.')));
        $this->result->expects($this->once())
            ->method('setData')
            ->with($responseData)
            ->willReturnSelf();

        $this->assertEquals($this->result, $this->object->execute());
    }
}
