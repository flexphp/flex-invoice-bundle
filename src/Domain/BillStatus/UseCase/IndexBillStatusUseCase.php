<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\UseCase;

use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\BillStatusRepository;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Request\IndexBillStatusRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Response\IndexBillStatusResponse;

final class IndexBillStatusUseCase
{
    private BillStatusRepository $billStatusRepository;

    public function __construct(BillStatusRepository $billStatusRepository)
    {
        $this->billStatusRepository = $billStatusRepository;
    }

    public function execute(IndexBillStatusRequest $request): IndexBillStatusResponse
    {
        $billStatus = $this->billStatusRepository->findBy($request);

        return new IndexBillStatusResponse($billStatus);
    }
}
