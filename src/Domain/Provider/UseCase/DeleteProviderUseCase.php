<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\Provider\UseCase;

use FlexPHP\Bundle\InvoiceBundle\Domain\Provider\ProviderRepository;
use FlexPHP\Bundle\InvoiceBundle\Domain\Provider\Request\DeleteProviderRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Provider\Response\DeleteProviderResponse;

final class DeleteProviderUseCase
{
    private ProviderRepository $providerRepository;

    public function __construct(ProviderRepository $providerRepository)
    {
        $this->providerRepository = $providerRepository;
    }

    public function execute(DeleteProviderRequest $request): DeleteProviderResponse
    {
        return new DeleteProviderResponse($this->providerRepository->remove($request));
    }
}
