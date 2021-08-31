<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\BillType\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\BillType;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\BillTypeGateway;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\DbalCriteriaHelper;

class MySQLBillTypeGateway implements BillTypeGateway
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
            'billType.Id as id',
            'billType.Name as name',
            'billType.IsActive as isActive',
        ]);
        $query->from('`BillTypes`', '`billType`');

        $query->orderBy('billType.UpdatedAt', 'DESC');

        $criteria = new DbalCriteriaHelper($query, $offset);

        foreach ($wheres as $column => $value) {
            $criteria->getCriteria('billType', $column, $value, $this->operator[$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    public function push(BillType $billType): string
    {
        $query = $this->conn->createQueryBuilder();

        $query->insert('`BillTypes`');

        $query->setValue('Id', ':id');
        $query->setValue('Name', ':name');
        $query->setValue('IsActive', ':isActive');
        $query->setValue('CreatedAt', ':createdAt');
        $query->setValue('UpdatedAt', ':updatedAt');
        $query->setValue('CreatedBy', ':createdBy');

        $query->setParameter(':id', $billType->id(), DB::STRING);
        $query->setParameter(':name', $billType->name(), DB::STRING);
        $query->setParameter(':isActive', $billType->isActive(), DB::BOOLEAN);
        $query->setParameter(':createdAt', $billType->createdAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedAt', $billType->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':createdBy', $billType->createdBy(), DB::INTEGER);

        $query->execute();

        return $billType->id();
    }

    public function get(BillType $billType): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'billType.Id as id',
            'billType.Name as name',
            'billType.IsActive as isActive',
            'billType.CreatedAt as createdAt',
            'billType.UpdatedAt as updatedAt',
            'billType.CreatedBy as createdBy',
            'billType.UpdatedBy as updatedBy',
            'createdBy.id as `createdBy.id`',
            'createdBy.name as `createdBy.name`',
            'updatedBy.id as `updatedBy.id`',
            'updatedBy.name as `updatedBy.name`',
        ]);
        $query->from('`BillTypes`', '`billType`');
        $query->leftJoin('`billType`', '`Users`', '`createdBy`', 'billType.CreatedBy = createdBy.id');
        $query->leftJoin('`billType`', '`Users`', '`updatedBy`', 'billType.UpdatedBy = updatedBy.id');
        $query->where('billType.Id = :id');
        $query->setParameter(':id', $billType->id(), DB::STRING);

        return $query->execute()->fetch() ?: [];
    }

    public function shift(BillType $billType): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->update('`BillTypes`');

        $query->set('Id', ':id');
        $query->set('Name', ':name');
        $query->set('IsActive', ':isActive');
        $query->set('UpdatedAt', ':updatedAt');
        $query->set('UpdatedBy', ':updatedBy');

        $query->setParameter(':id', $billType->id(), DB::STRING);
        $query->setParameter(':name', $billType->name(), DB::STRING);
        $query->setParameter(':isActive', $billType->isActive(), DB::BOOLEAN);
        $query->setParameter(':updatedAt', $billType->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedBy', $billType->updatedBy(), DB::INTEGER);

        $query->where('Id = :id');
        $query->setParameter(':id', $billType->id(), DB::STRING);

        $query->execute();
    }

    public function pop(BillType $billType): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->delete('`BillTypes`');

        $query->where('Id = :id');
        $query->setParameter(':id', $billType->id(), DB::STRING);

        $query->execute();
    }
}
