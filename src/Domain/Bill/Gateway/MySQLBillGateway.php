<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Bill;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\BillGateway;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillBillRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillBillStatusRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillBillTypeRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillOrderRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillProviderRequest;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\DbalCriteriaHelper;

class MySQLBillGateway implements BillGateway
{
    private $conn;

    private $operator = [
        //
    ];

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function search(array $wheres, array $orders, int $page, int $limit, int $offset): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'bill.Id as id',
            'bill.Number as number',
            'bill.OrderId as orderId',
            'bill.Provider as provider',
            'bill.Status as status',
            'bill.Type as type',
            'bill.TraceId as traceId',
            'bill.Hash as hash',
            'bill.HashType as hashType',
            'bill.Message as message',
            'bill.PdfPath as pdfPath',
            'bill.XmlPath as xmlPath',
            'bill.ParentId as parentId',
            'bill.DownloadedAt as downloadedAt',
            'bill.CreatedAt as createdAt',
            'orderId.id as `orderId.id`',
            'orderId.id as `orderId.id`',
            'provider.id as `provider.id`',
            'provider.name as `provider.name`',
            'status.id as `status.id`',
            'status.name as `status.name`',
            'type.id as `type.id`',
            'type.name as `type.name`',
            'parentId.id as `parentId.id`',
            'parentId.number as `parentId.number`',
        ]);
        $query->from('`Bills`', '`bill`');
        $query->join('`bill`', '`Orders`', '`orderId`', 'bill.OrderId = orderId.id');
        $query->join('`bill`', '`Providers`', '`provider`', 'bill.Provider = provider.id');
        $query->join('`bill`', '`BillStatus`', '`status`', 'bill.Status = status.id');
        $query->join('`bill`', '`BillTypes`', '`type`', 'bill.Type = type.id');
        $query->leftJoin('`bill`', '`Bills`', '`parentId`', 'bill.ParentId = parentId.id');

        $query->orderBy('bill.UpdatedAt', 'DESC');

        $criteria = new DbalCriteriaHelper($query, $offset);

        foreach ($wheres as $column => $value) {
            $criteria->getCriteria('bill', $column, $value, $this->operator[$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    public function push(Bill $bill): int
    {
        $query = $this->conn->createQueryBuilder();

        $query->insert('`Bills`');

        $query->setValue('Number', ':number');
        $query->setValue('OrderId', ':orderId');
        $query->setValue('Provider', ':provider');
        $query->setValue('Status', ':status');
        $query->setValue('Type', ':type');
        $query->setValue('TraceId', ':traceId');
        $query->setValue('Hash', ':hash');
        $query->setValue('HashType', ':hashType');
        $query->setValue('Message', ':message');
        $query->setValue('PdfPath', ':pdfPath');
        $query->setValue('XmlPath', ':xmlPath');
        $query->setValue('ParentId', ':parentId');
        $query->setValue('DownloadedAt', ':downloadedAt');
        $query->setValue('CreatedAt', ':createdAt');
        $query->setValue('UpdatedAt', ':updatedAt');
        $query->setValue('CreatedBy', ':createdBy');

        $query->setParameter(':number', $bill->number(), DB::STRING);
        $query->setParameter(':orderId', $bill->orderId(), DB::INTEGER);
        $query->setParameter(':provider', $bill->provider(), DB::STRING);
        $query->setParameter(':status', $bill->status(), DB::STRING);
        $query->setParameter(':type', $bill->type(), DB::STRING);
        $query->setParameter(':traceId', $bill->traceId(), DB::STRING);
        $query->setParameter(':hash', $bill->hash(), DB::STRING);
        $query->setParameter(':hashType', $bill->hashType(), DB::STRING);
        $query->setParameter(':message', $bill->message(), DB::STRING);
        $query->setParameter(':pdfPath', $bill->pdfPath(), DB::STRING);
        $query->setParameter(':xmlPath', $bill->xmlPath(), DB::STRING);
        $query->setParameter(':parentId', $bill->parentId(), DB::INTEGER);
        $query->setParameter(':downloadedAt', $bill->downloadedAt(), DB::DATETIME_MUTABLE);
        $query->setParameter(':createdAt', $bill->createdAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedAt', $bill->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':createdBy', $bill->createdBy(), DB::INTEGER);

        $query->execute();

        return (int)$query->getConnection()->lastInsertId();
    }

    public function get(Bill $bill): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'bill.Id as id',
            'bill.Number as number',
            'bill.OrderId as orderId',
            'bill.Provider as provider',
            'bill.Status as status',
            'bill.Type as type',
            'bill.TraceId as traceId',
            'bill.Hash as hash',
            'bill.HashType as hashType',
            'bill.Message as message',
            'bill.PdfPath as pdfPath',
            'bill.XmlPath as xmlPath',
            'bill.ParentId as parentId',
            'bill.DownloadedAt as downloadedAt',
            'bill.CreatedAt as createdAt',
            'bill.UpdatedAt as updatedAt',
            'bill.CreatedBy as createdBy',
            'bill.UpdatedBy as updatedBy',
            'orderId.id as `orderId.id`',
            'orderId.id as `orderId.id`',
            'provider.id as `provider.id`',
            'provider.name as `provider.name`',
            'status.id as `status.id`',
            'status.name as `status.name`',
            'type.id as `type.id`',
            'type.name as `type.name`',
            'parentId.id as `parentId.id`',
            'parentId.number as `parentId.number`',
            'createdBy.id as `createdBy.id`',
            'createdBy.name as `createdBy.name`',
            'updatedBy.id as `updatedBy.id`',
            'updatedBy.name as `updatedBy.name`',
        ]);
        $query->from('`Bills`', '`bill`');
        $query->join('`bill`', '`Orders`', '`orderId`', 'bill.OrderId = orderId.id');
        $query->join('`bill`', '`Providers`', '`provider`', 'bill.Provider = provider.id');
        $query->join('`bill`', '`BillStatus`', '`status`', 'bill.Status = status.id');
        $query->join('`bill`', '`BillTypes`', '`type`', 'bill.Type = type.id');
        $query->leftJoin('`bill`', '`Bills`', '`parentId`', 'bill.ParentId = parentId.id');
        $query->leftJoin('`bill`', '`Users`', '`createdBy`', 'bill.CreatedBy = createdBy.id');
        $query->leftJoin('`bill`', '`Users`', '`updatedBy`', 'bill.UpdatedBy = updatedBy.id');
        $query->where('bill.Id = :id');
        $query->setParameter(':id', $bill->id(), DB::INTEGER);

        return $query->execute()->fetch() ?: [];
    }

    public function shift(Bill $bill): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->update('`Bills`');

        $query->set('Number', ':number');
        $query->set('OrderId', ':orderId');
        $query->set('Provider', ':provider');
        $query->set('Status', ':status');
        $query->set('Type', ':type');
        $query->set('TraceId', ':traceId');
        $query->set('Hash', ':hash');
        $query->set('HashType', ':hashType');
        $query->set('Message', ':message');
        $query->set('PdfPath', ':pdfPath');
        $query->set('XmlPath', ':xmlPath');
        $query->set('ParentId', ':parentId');
        $query->set('DownloadedAt', ':downloadedAt');
        $query->set('UpdatedAt', ':updatedAt');
        $query->set('UpdatedBy', ':updatedBy');

        $query->setParameter(':number', $bill->number(), DB::STRING);
        $query->setParameter(':orderId', $bill->orderId(), DB::INTEGER);
        $query->setParameter(':provider', $bill->provider(), DB::STRING);
        $query->setParameter(':status', $bill->status(), DB::STRING);
        $query->setParameter(':type', $bill->type(), DB::STRING);
        $query->setParameter(':traceId', $bill->traceId(), DB::STRING);
        $query->setParameter(':hash', $bill->hash(), DB::STRING);
        $query->setParameter(':hashType', $bill->hashType(), DB::STRING);
        $query->setParameter(':message', $bill->message(), DB::STRING);
        $query->setParameter(':pdfPath', $bill->pdfPath(), DB::STRING);
        $query->setParameter(':xmlPath', $bill->xmlPath(), DB::STRING);
        $query->setParameter(':parentId', $bill->parentId(), DB::INTEGER);
        $query->setParameter(':downloadedAt', $bill->downloadedAt(), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedAt', $bill->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedBy', $bill->updatedBy(), DB::INTEGER);

        $query->where('Id = :id');
        $query->setParameter(':id', $bill->id(), DB::INTEGER);

        $query->execute();
    }

    public function pop(Bill $bill): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->delete('`Bills`');

        $query->where('Id = :id');
        $query->setParameter(':id', $bill->id(), DB::INTEGER);

        $query->execute();
    }

    public function filterOrders(FindBillOrderRequest $request, int $page, int $limit): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'order.id as id',
            'order.id as text',
        ]);
        $query->from('`Orders`', '`order`');

        $query->where('order.id like :order_id');
        $query->setParameter(':order_id', "%{$request->term}%");

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    public function filterProviders(FindBillProviderRequest $request, int $page, int $limit): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'provider.id as id',
            'provider.name as text',
        ]);
        $query->from('`Providers`', '`provider`');

        $query->where('provider.name like :provider_name');
        $query->setParameter(':provider_name', "%{$request->term}%");

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    public function filterBillStatus(FindBillBillStatusRequest $request, int $page, int $limit): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'billStatus.id as id',
            'billStatus.name as text',
        ]);
        $query->from('`BillStatus`', '`billStatus`');

        $query->where('billStatus.name like :billStatus_name');
        $query->setParameter(':billStatus_name', "%{$request->term}%");

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    public function filterBillTypes(FindBillBillTypeRequest $request, int $page, int $limit): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'billType.id as id',
            'billType.name as text',
        ]);
        $query->from('`BillTypes`', '`billType`');

        $query->where('billType.name like :billType_name');
        $query->setParameter(':billType_name', "%{$request->term}%");

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    public function filterBills(FindBillBillRequest $request, int $page, int $limit): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'bill.id as id',
            'bill.number as text',
        ]);
        $query->from('`Bills`', '`bill`');

        $query->where('bill.number like :bill_number');
        $query->setParameter(':bill_number', "%{$request->term}%");

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }
}
