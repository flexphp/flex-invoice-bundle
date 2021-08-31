<?php declare(strict_types=1);

namespace FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Response;

use FlexPHP\Messages\ResponseInterface;

final class FindBillBillResponse implements ResponseInterface
{
    public $bills;

    public function __construct(array $bills)
    {
        $this->bills = $bills;
    }
}
