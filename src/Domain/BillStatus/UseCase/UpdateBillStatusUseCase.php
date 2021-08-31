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
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Request\UpdateBillStatusRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Response\UpdateBillStatusResponse;

final class UpdateBillStatusUseCase
{
    private BillStatusRepository $billStatusRepository;

    public function __construct(BillStatusRepository $billStatusRepository)
    {
        $this->billStatusRepository = $billStatusRepository;
    }

    public function execute(UpdateBillStatusRequest $request): UpdateBillStatusResponse
    {
        return new UpdateBillStatusResponse($this->billStatusRepository->change($request));
    }
}
