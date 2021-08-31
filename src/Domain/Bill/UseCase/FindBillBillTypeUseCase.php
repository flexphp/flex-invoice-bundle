<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\Bill\UseCase;

use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\BillRepository;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillBillTypeRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Response\FindBillBillTypeResponse;

final class FindBillBillTypeUseCase
{
    private BillRepository $billRepository;

    public function __construct(BillRepository $billRepository)
    {
        $this->billRepository = $billRepository;
    }

    public function execute(FindBillBillTypeRequest $request): FindBillBillTypeResponse
    {
        $billTypes = $this->billRepository->findBillTypesBy($request);

        return new FindBillBillTypeResponse($billTypes);
    }
}
