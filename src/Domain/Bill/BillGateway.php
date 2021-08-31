<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\Bill;

use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillBillRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillBillStatusRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillBillTypeRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillOrderRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillProviderRequest;

interface BillGateway
{
    public function search(array $wheres, array $orders, int $page, int $limit, int $offset): array;

    public function push(Bill $bill): int;

    public function get(Bill $bill): array;

    public function shift(Bill $bill): void;

    public function pop(Bill $bill): void;

    public function filterOrders(FindBillOrderRequest $request, int $page, int $limit): array;

    public function filterProviders(FindBillProviderRequest $request, int $page, int $limit): array;

    public function filterBillStatus(FindBillBillStatusRequest $request, int $page, int $limit): array;

    public function filterBillTypes(FindBillBillTypeRequest $request, int $page, int $limit): array;

    public function filterBills(FindBillBillRequest $request, int $page, int $limit): array;
}
