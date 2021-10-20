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

use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\CreateBillRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\DeleteBillRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillBillRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillBillStatusRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillBillTypeRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillOrderRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillProviderRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\IndexBillRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\ReadBillRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\UpdateBillRequest;

final class BillRepository
{
    private BillGateway $gateway;

    public function __construct(BillGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return array<Bill>
     */
    public function findBy(IndexBillRequest $request): array
    {
        return \array_map(function (array $bill) use ($request) {
            $bill = (new BillFactory())->make($bill);
            // $bill->withLastDebit($this->gateway, $request->_offset);

            return $bill;
        }, $this->gateway->search((array)$request, [], $request->_page, $request->_limit, $request->_offset));
    }

    public function add(CreateBillRequest $request): Bill
    {
        $bill = (new BillFactory())->make($request);

        $bill->setId($this->gateway->push($bill));

        return $bill;
    }

    public function getById(ReadBillRequest $request): Bill
    {
        $factory = new BillFactory();
        $data = $this->gateway->get($factory->make($request));

        $bill = $factory->make($data);
        // $bill->withLastDebit($this->gateway, 0);

        return $bill;
    }

    public function change(UpdateBillRequest $request): Bill
    {
        $bill = (new BillFactory())->make($request);

        $this->gateway->shift($bill);

        return $bill;
    }

    public function remove(DeleteBillRequest $request): Bill
    {
        $factory = new BillFactory();
        $data = $this->gateway->get($factory->make($request));

        $bill = $factory->make($data);

        $this->gateway->pop($bill);

        return $bill;
    }

    public function findOrdersBy(FindBillOrderRequest $request): array
    {
        return $this->gateway->filterOrders($request, $request->_page, $request->_limit);
    }

    public function findProvidersBy(FindBillProviderRequest $request): array
    {
        return $this->gateway->filterProviders($request, $request->_page, $request->_limit);
    }

    public function findBillStatusBy(FindBillBillStatusRequest $request): array
    {
        return $this->gateway->filterBillStatus($request, $request->_page, $request->_limit);
    }

    public function findBillTypesBy(FindBillBillTypeRequest $request): array
    {
        return $this->gateway->filterBillTypes($request, $request->_page, $request->_limit);
    }

    public function findBillsBy(FindBillBillRequest $request): array
    {
        return $this->gateway->filterBills($request, $request->_page, $request->_limit);
    }

    public function gateway(): BillGateway
    {
        return $this->gateway;
    }
}
