<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request;

use FlexPHP\Messages\RequestInterface;

final class UpdateBillRequest implements RequestInterface
{
    public $id;

    public $prefix;

    public $number;

    public $orderId;

    public $provider;

    public $status;

    public $type;

    public $traceId;

    public $hash;

    public $hashType;

    public $message;

    public $pdfPath;

    public $xmlPath;

    public $parentId;

    public $downloadedAt;

    public $updatedBy;

    public function __construct(int $id, array $data, int $updatedBy)
    {
        $this->id = $id;
        $this->prefix = $data['prefix'] ?? null;
        $this->number = $data['number'] ?? null;
        $this->orderId = $data['orderId'] ?? null;
        $this->provider = $data['provider'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->type = $data['type'] ?? null;
        $this->traceId = $data['traceId'] ?? null;
        $this->hash = $data['hash'] ?? null;
        $this->hashType = $data['hashType'] ?? null;
        $this->message = $data['message'] ?? null;
        $this->pdfPath = $data['pdfPath'] ?? null;
        $this->xmlPath = $data['xmlPath'] ?? null;
        $this->parentId = $data['parentId'] ?? null;
        $this->downloadedAt = $data['downloadedAt'] ?? null;
        $this->updatedBy = $updatedBy;
    }
}
