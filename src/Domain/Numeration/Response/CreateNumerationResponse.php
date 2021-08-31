<?php declare(strict_types=1);

namespace FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\Response;

use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\Numeration;
use FlexPHP\Messages\ResponseInterface;

final class CreateNumerationResponse implements ResponseInterface
{
    public $numeration;

    public function __construct(Numeration $numeration)
    {
        $this->numeration = $numeration;
    }
}
