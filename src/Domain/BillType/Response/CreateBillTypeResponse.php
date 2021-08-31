<?php declare(strict_types=1);

namespace FlexPHP\Bundle\InvoiceBundle\Domain\BillType\Response;

use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\BillType;
use FlexPHP\Messages\ResponseInterface;

final class CreateBillTypeResponse implements ResponseInterface
{
    public $billType;

    public function __construct(BillType $billType)
    {
        $this->billType = $billType;
    }
}
