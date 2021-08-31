<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Response;

use FlexPHP\Messages\ResponseInterface;

final class ExportBillResponse implements ResponseInterface
{
    public string $path;

    public string $filename;

    public string $contentType;

    public function __construct(string $path, string $filename, string $contentType)
    {
        $this->path = $path;
        $this->filename = $filename;
        $this->contentType = $contentType;
    }
}
