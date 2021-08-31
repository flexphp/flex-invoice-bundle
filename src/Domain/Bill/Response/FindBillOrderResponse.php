<?php declare(strict_types=1);

namespace FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Response;

use FlexPHP\Messages\ResponseInterface;

final class FindBillOrderResponse implements ResponseInterface
{
    public $orders;

    public function __construct(array $orders)
    {
        $this->orders = $orders;
    }
}
