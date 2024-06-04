<?php

namespace Belvg\AffiliateProgram\Controller\Index;


use Belvg\AffiliateProgram\Model\ResourceModel\Post as PostResource;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Belvg\AffiliateProgram\Model\Post as ModelPost;

class Post extends Action
{
    public const AV_FIELDS = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'website_url',
        'visitors',
        'views',
        'street_address_1',
        'street_address_2',
        'city',
        'country',
        'post_code'
    ];

    public PostResource $postResource;

    public Validator $validator;

    public JsonFactory $jsonFactory;

    public ModelPost $modelPost;

     /**
     *
     * @param Context $context
     * @param PostResource $postResource
     * @param Validator $validator
     * @param JsonFactory $jsonResultFactory
     * @param ModelPost $modelPost
     */
    public function __construct(
        Context $context,
        PostResource $postResource,
        Validator $validator,
        JsonFactory $jsonResultFactory,
        ModelPost $modelPost
    ) {
        parent::__construct($context);

        $this->postResource = $postResource;
        $this->validator = $validator;
        $this->jsonFactory = $jsonResultFactory;
        $this->modelPost = $modelPost;
    }

    public function execute()
    {
        $result = $this->jsonFactory->create();
        if (!$this->validator->validate($this->getRequest())) {
            return $result->setData(['error' => true, 'message' => __('Invalid form key')]);
        }

        $post = $this->getRequest()->getPostValue();
        $params = $this->normalizeParams($post);

        if (empty($params['errors'])) {
            $this->modelPost->setData($params['fields']);
            $this->postResource->save($this->modelPost);

            return $result->setData(['error' => false]);
        }

        return  $result->setData([
                'error' => true,
                'message' => implode(';', $params['errors'])
            ]
        );
    }

    /**
     *
     * @param array $post
     * @return array
     */
    public function normalizeParams(array $post): array
    {
        $out = [];
        $errors = [];
        foreach ($post as $code => $value) {
            switch ($code) {
                case 'email':
                    if (!$this->isEmail($value)) {
                        $errors[] = __('Incorrect Email');
                    }
                    break;
                case 'website_url':
                    if (!$this->isWebsiteUrl($value)) {
                        $errors[] = __('Incorrect Website Url');
                    }
                    break;
            }

            if (in_array($code, self::AV_FIELDS)) {
                $out[$code] = trim(addslashes($value));
            }
        }

        return [
            'fields' => $out,
            'errors' => $errors,
        ];
    }

    public function isEmail(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function isWebsiteUrl(string $value): bool
    {
        return preg_match("/^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([\/\w .-]*)*\/?$/", $value);
    }
}
