<?php declare(strict_types=1);

namespace FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\Response;

use FlexPHP\Messages\ResponseInterface;

final class IndexNumerationResponse implements ResponseInterface
{
    public $numerations;

    public function __construct(array $numerations)
    {
        $this->numerations = $numerations;
    }
}
