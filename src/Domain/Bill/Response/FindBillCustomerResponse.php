<?php declare(strict_types=1);

namespace FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Response;

use FlexPHP\Messages\ResponseInterface;

final class FindBillCustomerResponse implements ResponseInterface
{
    public $customers;

    public function __construct(array $customers)
    {
        $this->customers = $customers;
    }
}
