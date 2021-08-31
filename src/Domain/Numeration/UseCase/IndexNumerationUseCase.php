<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\UseCase;

use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\NumerationRepository;
use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\Request\IndexNumerationRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\Response\IndexNumerationResponse;

final class IndexNumerationUseCase
{
    private NumerationRepository $numerationRepository;

    public function __construct(NumerationRepository $numerationRepository)
    {
        $this->numerationRepository = $numerationRepository;
    }

    public function execute(IndexNumerationRequest $request): IndexNumerationResponse
    {
        $numerations = $this->numerationRepository->findBy($request);

        return new IndexNumerationResponse($numerations);
    }
}
