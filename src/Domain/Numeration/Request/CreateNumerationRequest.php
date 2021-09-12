<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\Request;

use FlexPHP\Messages\RequestInterface;

final class CreateNumerationRequest implements RequestInterface
{
    public $resolution;

    public $startAt;

    public $finishAt;

    public $prefix;

    public $fromNumber;

    public $toNumber;

    public $currentNumber;

    public $isActive;

    public $createdBy;

    public function __construct(array $data, int $createdBy)
    {
        $this->type = $data['type'] ?? null;
        $this->resolution = $data['resolution'] ?? null;
        $this->startAt = $data['startAt'] ?? null;
        $this->finishAt = $data['finishAt'] ?? null;
        $this->prefix = $data['prefix'] ?? null;
        $this->fromNumber = $data['fromNumber'] ?? null;
        $this->toNumber = $data['toNumber'] ?? null;
        $this->currentNumber = $data['currentNumber'] ?? null;
        $this->isActive = $data['isActive'] ?? null;
        $this->createdBy = $createdBy;
    }
}
