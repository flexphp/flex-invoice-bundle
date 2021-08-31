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
use FlexPHP\Bundle\InvoiceBundle\Domain\Provider\Request\UpdateProviderRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Provider\Response\UpdateProviderResponse;

final class UpdateProviderUseCase
{
    private ProviderRepository $providerRepository;

    public function __construct(ProviderRepository $providerRepository)
    {
        $this->providerRepository = $providerRepository;
    }

    public function execute(UpdateProviderRequest $request): UpdateProviderResponse
    {
        return new UpdateProviderResponse($this->providerRepository->change($request));
    }
}
