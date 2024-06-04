<?php
namespace Smartwave\Porto\Controller\Index;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Result\PageFactory;
use Smartwave\Filterproducts\Block\Home\LatestList;

/**
 * Class controller Showcategoryproducts
 */
class Showcategoryproducts implements ActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var Http
     */
    private Http $response;

    /**
     * @var RedirectFactory
     */
    private RedirectFactory $resultRedirectFactory;

    /**
     * @var Json
     */
    private Json $json;

    /**
     * Construct
     *
     * @param PageFactory $resultPageFactory
     * @param RequestInterface $request
     * @param Http $response
     * @param Json $json
     * @param RedirectFactory $resultRedirectFactory
     */
    public function __construct(
        PageFactory $resultPageFactory,
        RequestInterface $request,
        Http $response,
        Json $json,
        RedirectFactory $resultRedirectFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->request = $request;
        $this->response = $response;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->json = $json;
    }

    /**
     * Function execute()
     *
     * @return ResponseInterface|Redirect|ResultInterface|void
     */
    public function execute()
    {
        if (!$this->request->isAjax()) {
            $redirect = $this->resultRedirectFactory->create();
            $redirect->setPath('/');
            return $redirect;
        }

        $params = $this->request->getParams();
        $resultPage = $this->resultPageFactory->create();
        $block = $resultPage->getLayout()
            ->createBlock(LatestList::class) /* @phpstan-ignore-line */
            ->setTemplate('Smartwave_Porto::ajaxproducts/grid.phtml')
            ->setData('category_id', $params['category_id'])
            ->setData('product_count', $params['product_count'])
            ->setData('aspect_ratio', $params['aspect_ratio'])
            ->setData('image_width', $params['image_width'])
            ->setData('image_height', $params['image_height'])
            ->setData('column_count', $params['columns'])
            ->toHtml();
        $jsonData = $this->json->serialize(['result' => $block]);
        $this->response->setHeader('Content-type', 'application/json');
        $this->response->setBody($jsonData);
    }
}
