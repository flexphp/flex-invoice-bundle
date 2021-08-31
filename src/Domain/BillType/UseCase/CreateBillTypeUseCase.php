<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\BillType\UseCase;

use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\BillTypeRepository;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\Request\CreateBillTypeRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\Response\CreateBillTypeResponse;

final class CreateBillTypeUseCase
{
    private BillTypeRepository $billTypeRepository;

    public function __construct(BillTypeRepository $billTypeRepository)
    {
        $this->billTypeRepository = $billTypeRepository;
    }

    public function execute(CreateBillTypeRequest $request): CreateBillTypeResponse
    {
        return new CreateBillTypeResponse($this->billTypeRepository->add($request));
    }
}
