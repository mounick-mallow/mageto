<?php

namespace Belvg\AffiliateProgram\Model;

use Magento\Framework\Model\AbstractModel;
use Belvg\AffiliateProgram\Model\ResourceModel\Post as PostResource;

class Post extends AbstractModel
{
    public function _construct(): void
    {
        $this->_init(PostResource::class);
    }
}
