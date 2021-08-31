<?php declare(strict_types=1);

namespace FlexPHP\Bundle\InvoiceBundle\Domain\BillType\Response;

use FlexPHP\Messages\ResponseInterface;

final class IndexBillTypeResponse implements ResponseInterface
{
    public $billTypes;

    public function __construct(array $billTypes)
    {
        $this->billTypes = $billTypes;
    }
}
