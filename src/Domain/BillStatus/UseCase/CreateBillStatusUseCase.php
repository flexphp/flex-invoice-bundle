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
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Request\CreateBillStatusRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Response\CreateBillStatusResponse;

final class CreateBillStatusUseCase
{
    private BillStatusRepository $billStatusRepository;

    public function __construct(BillStatusRepository $billStatusRepository)
    {
        $this->billStatusRepository = $billStatusRepository;
    }

    public function execute(CreateBillStatusRequest $request): CreateBillStatusResponse
    {
        return new CreateBillStatusResponse($this->billStatusRepository->add($request));
    }
}
