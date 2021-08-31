<?php declare(strict_types=1);

namespace FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Response;

use FlexPHP\Messages\ResponseInterface;

final class FindBillBillStatusResponse implements ResponseInterface
{
    public $billStatus;

    public function __construct(array $billStatus)
    {
        $this->billStatus = $billStatus;
    }
}
