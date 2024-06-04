<?php

namespace Belvg\AffiliateProgram\Model\ResourceModel\Post;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Belvg\AffiliateProgram\Model\Post;
use Belvg\AffiliateProgram\Model\ResourceModel\Post as PostResource;

class Collection extends AbstractCollection
{
    public function _construct(): void
    {
        $this->_init(Post::class, PostResource::class);
    }
}
