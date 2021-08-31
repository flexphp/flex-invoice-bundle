<?php declare(strict_types=1);

namespace FlexPHP\Bundle\InvoiceBundle\Domain\BillType\Response;

use FlexPHP\Messages\ResponseInterface;

final class FindBillTypeUserResponse implements ResponseInterface
{
    public $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }
}
