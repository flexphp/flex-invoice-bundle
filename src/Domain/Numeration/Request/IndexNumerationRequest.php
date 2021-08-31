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

use FlexPHP\Bundle\HelperBundle\Domain\Helper\DateTimeTrait;
use FlexPHP\Messages\RequestInterface;

final class IndexNumerationRequest implements RequestInterface
{
    use DateTimeTrait;

    public $id;

    public $resolution;

    public $startAt;

    public $finishAt;

    public $prefix;

    public $fromNumber;

    public $toNumber;

    public $currentNumber;

    public $isActive;

    public $createdAt = [];

    public $updatedAt;

    public $createdBy;

    public $updatedBy;

    public $_page;

    public $_limit;

    public $_offset;

    public function __construct(array $data, int $_page, int $_limit = 50, ?string $timezone = null)
    {
        $this->id = $data['id'] ?? null;
        $this->resolution = $data['resolution'] ?? null;
        $this->startAt = $data['startAt'] ?? null;
        $this->finishAt = $data['finishAt'] ?? null;
        $this->prefix = $data['prefix'] ?? null;
        $this->fromNumber = $data['fromNumber'] ?? null;
        $this->toNumber = $data['toNumber'] ?? null;
        $this->currentNumber = $data['currentNumber'] ?? null;
        $this->isActive = $data['isActive'] ?? null;
        $this->createdAt[] = $data['createdAt_START'] ?? null;
        $this->createdAt[] = $data['createdAt_END'] ?? null;
        $this->updatedAt = $data['updatedAt'] ?? null;
        $this->createdBy = $data['createdBy'] ?? null;
        $this->updatedBy = $data['updatedBy'] ?? null;
        $this->_page = $_page;
        $this->_limit = $_limit;
        $this->_offset = $this->getOffset($this->getTimezone($timezone));
    }
}
