<?php declare(strict_types=1);

namespace FlexPHP\Bundle\InvoiceBundle\Domain\Provider\Response;

use FlexPHP\Bundle\InvoiceBundle\Domain\Provider\Provider;
use FlexPHP\Messages\ResponseInterface;

final class ReadProviderResponse implements ResponseInterface
{
    public $provider;

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }
}
