<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\Numeration;

use FlexPHP\Bundle\HelperBundle\Domain\Helper\ToArrayTrait;
use FlexPHP\Bundle\UserBundle\Domain\User\User;

final class Numeration
{
    use ToArrayTrait;

    private $id;

    private $resolution;

    private $startAt;

    private $finishAt;

    private $prefix;

    private $fromNumber;

    private $toNumber;

    private $currentNumber;

    private $isActive;

    private $createdAt;

    private $updatedAt;

    private $createdBy;

    private $updatedBy;

    private $createdByInstance;

    private $updatedByInstance;

    public function id(): ?int
    {
        return $this->id;
    }

    public function resolution(): ?string
    {
        return $this->resolution;
    }

    public function startAt(): ?\DateTime
    {
        return $this->startAt;
    }

    public function finishAt(): ?\DateTime
    {
        return $this->finishAt;
    }

    public function prefix(): ?string
    {
        return $this->prefix;
    }

    public function fromNumber(): ?int
    {
        return $this->fromNumber;
    }

    public function toNumber(): ?int
    {
        return $this->toNumber;
    }

    public function currentNumber(): ?int
    {
        return $this->currentNumber;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
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

    public function setResolution(string $resolution): void
    {
        $this->resolution = $resolution;
    }

    public function setStartAt(\DateTime $startAt): void
    {
        $this->startAt = $startAt;
    }

    public function setFinishAt(\DateTime $finishAt): void
    {
        $this->finishAt = $finishAt;
    }

    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function setFromNumber(int $fromNumber): void
    {
        $this->fromNumber = $fromNumber;
    }

    public function setToNumber(int $toNumber): void
    {
        $this->toNumber = $toNumber;
    }

    public function setCurrentNumber(int $currentNumber): void
    {
        $this->currentNumber = $currentNumber;
    }

    public function setIsActive(?bool $isActive): void
    {
        $this->isActive = $isActive;
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

    public function setCreatedByInstance(?User $user): void
    {
        $this->createdByInstance = $user;
    }

    public function setUpdatedByInstance(?User $user): void
    {
        $this->updatedByInstance = $user;
    }
}
