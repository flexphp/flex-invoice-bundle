<?php declare(strict_types=1);

namespace FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request;

use FlexPHP\Messages\RequestInterface;

final class FindBillCustomerRequest implements RequestInterface
{
    public $term;
    public $page;

    public function __construct(array $data)
    {
        $this->term = $data['term'] ?? '';
        $this->page = $data['page'] ?? 1;
    }
}
