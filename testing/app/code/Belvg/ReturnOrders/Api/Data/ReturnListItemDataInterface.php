<?php

namespace Belvg\ReturnOrders\Api\Data;


interface ReturnListItemDataInterface
{
    /**
     *
     * @return int
     */
    public function getOrderreturnId(): int;

    /**
     * @param int $id
     * @return void
     */
    public function setOrderreturnId(int $id): void;

    /**
     *
     * @return string|null
     */
    public function getOrderId(): ?string;

    /**
     *
     * @param string|null $id
     * @return void
     */
    public function setOrderId(?string $id): void;

    /**
     *
     * @return string|null
     */
    public function getProductSku(): ?string;

    /**
     * @param string|null $sku
     * @return void
     */
    public function setProductSku(?string $sku): void;

    /**
     *
     * @return string|null
     */
    public function getCustomerEmail(): ?string;

    /**
     * @param string|null $email
     * @return void
     */
    public function setCustomerEmail(?string $email): void;

    /**
     *
     * @return string|null
     */
    public function getType(): ?string;

    /**
     *
     * @param string|null $type
     * @return void
     */
    public function setType(?string $type): void;

    /**
     *
     * @return string|null
     */
    public function getReason(): ?string;

    /**
     *
     * @param string|null $reason
     * @return void
     */
    public function setReason(?string $reason): void;

    /**
     *
     * @return string|null
     */
    public function getLangCode(): ?string;

    /**
     *
     * @param string|null $langCode
     * @return void
     */
    public function setLangCode(?string $langCode): void;

    /**
     *
     * @return string|null
     */
    public function getWebsite(): ?string;

    /**
     *
     * @param string|null $website
     * @return void
     */
    public function setWebsite(?string $website): void;

    /**
     *
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     *
     * @param int|null $status
     * @return void
     */
    public function setStatus(?int $status): void;

    /**
     *
     * @return string|null
     */
    public function getErpStatus(): ?string;

    /**
     *
     * @param string|null $status
     * @return void
     */
    public function setErpStatus(?string $status): void;

    /**
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     *
     * @param string|null $date
     * @return void
     */
    public function setCreatedAt(?string $date): void;

    /**
     *
     * @return string|null
     */
    public function getErpReturnStatus(): ?string;

    /**
     * @param string|null $erpStatus
     * @return void
     */
    public function setErpReturnStatus(?string $erpStatus): void;
}
