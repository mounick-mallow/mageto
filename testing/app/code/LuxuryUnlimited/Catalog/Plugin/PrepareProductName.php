<?php declare(strict_types=1);

namespace LuxuryUnlimited\Catalog\Plugin;

use Magento\Catalog\Model\Product;

class PrepareProductName
{
    /**
     * Transform product name to lowercase
     *
     * @param Product $subject
     * @param ?string $result
     * @return mixed|string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetName(
        Product $subject,
        ?string $result
    ) {
        if (empty($result)) {
            return $result;
        }

        return ucwords(strtolower($result));
    }
}
