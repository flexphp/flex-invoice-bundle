<?php declare(strict_types=1);

namespace FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Response;

use FlexPHP\Messages\ResponseInterface;

final class FindBillProviderResponse implements ResponseInterface
{
    public $providers;

    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }
}
