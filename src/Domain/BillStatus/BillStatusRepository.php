<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus;

use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Request\CreateBillStatusRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Request\DeleteBillStatusRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Request\IndexBillStatusRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Request\ReadBillStatusRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Request\UpdateBillStatusRequest;

final class BillStatusRepository
{
    private BillStatusGateway $gateway;

    public function __construct(BillStatusGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return array<BillStatus>
     */
    public function findBy(IndexBillStatusRequest $request): array
    {
        return \array_map(function (array $billStatus) {
            return (new BillStatusFactory())->make($billStatus);
        }, $this->gateway->search((array)$request, [], $request->_page, $request->_limit, $request->_offset));
    }

    public function add(CreateBillStatusRequest $request): BillStatus
    {
        $billStatus = (new BillStatusFactory())->make($request);

        $billStatus->setId($this->gateway->push($billStatus));

        return $billStatus;
    }

    public function getById(ReadBillStatusRequest $request): BillStatus
    {
        $factory = new BillStatusFactory();
        $data = $this->gateway->get($factory->make($request));

        return $factory->make($data);
    }

    public function change(UpdateBillStatusRequest $request): BillStatus
    {
        $billStatus = (new BillStatusFactory())->make($request);

        $this->gateway->shift($billStatus);

        return $billStatus;
    }

    public function remove(DeleteBillStatusRequest $request): BillStatus
    {
        $factory = new BillStatusFactory();
        $data = $this->gateway->get($factory->make($request));

        $billStatus = $factory->make($data);

        $this->gateway->pop($billStatus);

        return $billStatus;
    }
}
