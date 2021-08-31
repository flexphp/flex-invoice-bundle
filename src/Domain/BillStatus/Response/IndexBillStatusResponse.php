<?php declare(strict_types=1);

namespace FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Response;

use FlexPHP\Messages\ResponseInterface;

final class IndexBillStatusResponse implements ResponseInterface
{
    public $billStatus;

    public function __construct(array $billStatus)
    {
        $this->billStatus = $billStatus;
    }
}
