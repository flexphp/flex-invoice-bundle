<?php declare(strict_types=1);

namespace FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Response;

use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Bill;
use FlexPHP\Messages\ResponseInterface;

final class DeleteBillResponse implements ResponseInterface
{
    public $bill;

    public function __construct(Bill $bill)
    {
        $this->bill = $bill;
    }
}
