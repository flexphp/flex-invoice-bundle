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
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Request\ReadBillStatusRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Response\ReadBillStatusResponse;

final class ReadBillStatusUseCase
{
    private BillStatusRepository $billStatusRepository;

    public function __construct(BillStatusRepository $billStatusRepository)
    {
        $this->billStatusRepository = $billStatusRepository;
    }

    public function execute(ReadBillStatusRequest $request): ReadBillStatusResponse
    {
        $billStatus = $this->billStatusRepository->getById($request);

        return new ReadBillStatusResponse($billStatus);
    }
}
