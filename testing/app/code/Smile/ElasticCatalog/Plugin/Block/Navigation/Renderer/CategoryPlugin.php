<?php

declare(strict_types=1);

namespace Smile\ElasticCatalog\Plugin\Block\Navigation\Renderer;

use Smile\ElasticsuiteCatalog\Block\Navigation\Renderer\Category;

/**
 * CategoryPlugin
 */
class CategoryPlugin
{
    /**
     * Is multiple select enabled
     *
     * @param Category $category
     * @param bool $result
     * @return true
     */
    public function afterIsMultipleSelectEnabled(Category $category, bool $result): bool
    {
        return true;
    }
}
