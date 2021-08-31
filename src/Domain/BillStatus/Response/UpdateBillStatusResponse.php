<?php declare(strict_types=1);

namespace FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Response;

use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\BillStatus;
use FlexPHP\Messages\ResponseInterface;

final class UpdateBillStatusResponse implements ResponseInterface
{
    public $billStatus;

    public function __construct(BillStatus $billStatus)
    {
        $this->billStatus = $billStatus;
    }
}
