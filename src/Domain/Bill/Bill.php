<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\Bill;

use Domain\Order\Order;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\ToArrayTrait;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\BillStatus;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\BillType;
use FlexPHP\Bundle\InvoiceBundle\Domain\Provider\Provider;
use FlexPHP\Bundle\UserBundle\Domain\User\User;

final class Bill
{
    use ToArrayTrait;

    private $id;

    private $prefix;

    private $number;

    private $orderId;

    private $provider;

    private $status;

    private $type;

    private $traceId;

    private $hash;

    private $hashType;

    private $message;

    private $pdfPath;

    private $xmlPath;

    private $parentId;

    private $downloadedAt;

    private $createdAt;

    private $updatedAt;

    private $createdBy;

    private $updatedBy;

    private $orderIdInstance;

    private $providerInstance;

    private $statusInstance;

    private $typeInstance;

    private $parentIdInstance;

    private $createdByInstance;

    private $updatedByInstance;

    public function id(): ?int
    {
        return $this->id;
    }

    public function prefix(): ?string
    {
        return $this->prefix;
    }

    public function number(): ?int
    {
        return $this->number;
    }

    public function orderId(): ?int
    {
        return $this->orderId;
    }

    public function provider(): ?string
    {
        return $this->provider;
    }

    public function status(): ?string
    {
        return $this->status;
    }

    public function type(): ?string
    {
        return $this->type;
    }

    public function traceId(): ?string
    {
        return $this->traceId;
    }

    public function hash(): ?string
    {
        return $this->hash;
    }

    public function hashType(): ?string
    {
        return $this->hashType;
    }

    public function message(): ?string
    {
        return $this->message;
    }

    public function pdfPath(): ?string
    {
        return $this->pdfPath;
    }

    public function xmlPath(): ?string
    {
        return $this->xmlPath;
    }

    public function parentId(): ?int
    {
        return $this->parentId;
    }

    public function downloadedAt(): ?\DateTime
    {
        return $this->downloadedAt;
    }

    public function createdAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function createdBy(): ?int
    {
        return $this->createdBy;
    }

    public function updatedBy(): ?int
    {
        return $this->updatedBy;
    }

    public function orderIdInstance(): Order
    {
        return $this->orderIdInstance ?: new Order;
    }

    public function providerInstance(): Provider
    {
        return $this->providerInstance ?: new Provider;
    }

    public function statusInstance(): BillStatus
    {
        return $this->statusInstance ?: new BillStatus;
    }

    public function typeInstance(): BillType
    {
        return $this->typeInstance ?: new BillType;
    }

    public function parentIdInstance(): ?self
    {
        return $this->parentIdInstance;
    }

    public function createdByInstance(): ?User
    {
        return $this->createdByInstance;
    }

    public function updatedByInstance(): ?User
    {
        return $this->updatedByInstance;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function setProvider(string $provider): void
    {
        $this->provider = $provider;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function setTraceId(?string $traceId): void
    {
        $this->traceId = $traceId;
    }

    public function setHash(?string $hash): void
    {
        $this->hash = $hash;
    }

    public function setHashType(?string $hashType): void
    {
        $this->hashType = $hashType;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function setPdfPath(?string $pdfPath): void
    {
        $this->pdfPath = $pdfPath;
    }

    public function setXmlPath(?string $xmlPath): void
    {
        $this->xmlPath = $xmlPath;
    }

    public function setParentId(?int $parentId): void
    {
        $this->parentId = $parentId;
    }

    public function setDownloadedAt(?\DateTime $downloadedAt): void
    {
        $this->downloadedAt = $downloadedAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setCreatedBy(?int $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function setUpdatedBy(?int $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
    }

    public function setOrderIdInstance(Order $order): void
    {
        $this->orderIdInstance = $order;
    }

    public function setProviderInstance(Provider $provider): void
    {
        $this->providerInstance = $provider;
    }

    public function setStatusInstance(BillStatus $billStatus): void
    {
        $this->statusInstance = $billStatus;
    }

    public function setTypeInstance(BillType $billType): void
    {
        $this->typeInstance = $billType;
    }

    public function setParentIdInstance(?self $bill): void
    {
        $this->parentIdInstance = $bill;
    }

    public function setCreatedByInstance(?User $user): void
    {
        $this->createdByInstance = $user;
    }

    public function setUpdatedByInstance(?User $user): void
    {
        $this->updatedByInstance = $user;
    }

    public function getNumeration(): string
    {
        return $this->prefix() . $this->number();
    }
}
