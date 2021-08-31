<?php declare(strict_types=1);

namespace FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Response;

use FlexPHP\Messages\ResponseInterface;

final class FindBillStatusUserResponse implements ResponseInterface
{
    public $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }
}
