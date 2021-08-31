<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\BillType;

use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\Request\CreateBillTypeRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\Request\DeleteBillTypeRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\Request\IndexBillTypeRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\Request\ReadBillTypeRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\Request\UpdateBillTypeRequest;

final class BillTypeRepository
{
    private BillTypeGateway $gateway;

    public function __construct(BillTypeGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return array<BillType>
     */
    public function findBy(IndexBillTypeRequest $request): array
    {
        return \array_map(function (array $billType) {
            return (new BillTypeFactory())->make($billType);
        }, $this->gateway->search((array)$request, [], $request->_page, $request->_limit, $request->_offset));
    }

    public function add(CreateBillTypeRequest $request): BillType
    {
        $billType = (new BillTypeFactory())->make($request);

        $billType->setId($this->gateway->push($billType));

        return $billType;
    }

    public function getById(ReadBillTypeRequest $request): BillType
    {
        $factory = new BillTypeFactory();
        $data = $this->gateway->get($factory->make($request));

        return $factory->make($data);
    }

    public function change(UpdateBillTypeRequest $request): BillType
    {
        $billType = (new BillTypeFactory())->make($request);

        $this->gateway->shift($billType);

        return $billType;
    }

    public function remove(DeleteBillTypeRequest $request): BillType
    {
        $factory = new BillTypeFactory();
        $data = $this->gateway->get($factory->make($request));

        $billType = $factory->make($data);

        $this->gateway->pop($billType);

        return $billType;
    }
}
