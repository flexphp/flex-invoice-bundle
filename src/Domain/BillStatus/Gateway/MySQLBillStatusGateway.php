<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\BillStatus;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\BillStatusGateway;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\DbalCriteriaHelper;

class MySQLBillStatusGateway implements BillStatusGateway
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
            'billStatus.Id as id',
            'billStatus.Name as name',
        ]);
        $query->from('`BillStatus`', '`billStatus`');

        $query->orderBy('billStatus.UpdatedAt', 'DESC');

        $criteria = new DbalCriteriaHelper($query, $offset);

        foreach ($wheres as $column => $value) {
            $criteria->getCriteria('billStatus', $column, $value, $this->operator[$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    public function push(BillStatus $billStatus): string
    {
        $query = $this->conn->createQueryBuilder();

        $query->insert('`BillStatus`');

        $query->setValue('Id', ':id');
        $query->setValue('Name', ':name');
        $query->setValue('CreatedAt', ':createdAt');
        $query->setValue('UpdatedAt', ':updatedAt');
        $query->setValue('CreatedBy', ':createdBy');

        $query->setParameter(':id', $billStatus->id(), DB::STRING);
        $query->setParameter(':name', $billStatus->name(), DB::STRING);
        $query->setParameter(':createdAt', $billStatus->createdAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedAt', $billStatus->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':createdBy', $billStatus->createdBy(), DB::INTEGER);

        $query->execute();

        return $billStatus->id();
    }

    public function get(BillStatus $billStatus): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'billStatus.Id as id',
            'billStatus.Name as name',
            'billStatus.CreatedAt as createdAt',
            'billStatus.UpdatedAt as updatedAt',
            'billStatus.CreatedBy as createdBy',
            'billStatus.UpdatedBy as updatedBy',
            'createdBy.id as `createdBy.id`',
            'createdBy.name as `createdBy.name`',
            'updatedBy.id as `updatedBy.id`',
            'updatedBy.name as `updatedBy.name`',
        ]);
        $query->from('`BillStatus`', '`billStatus`');
        $query->leftJoin('`billStatus`', '`Users`', '`createdBy`', 'billStatus.CreatedBy = createdBy.id');
        $query->leftJoin('`billStatus`', '`Users`', '`updatedBy`', 'billStatus.UpdatedBy = updatedBy.id');
        $query->where('billStatus.Id = :id');
        $query->setParameter(':id', $billStatus->id(), DB::STRING);

        return $query->execute()->fetch() ?: [];
    }

    public function shift(BillStatus $billStatus): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->update('`BillStatus`');

        $query->set('Id', ':id');
        $query->set('Name', ':name');
        $query->set('UpdatedAt', ':updatedAt');
        $query->set('UpdatedBy', ':updatedBy');

        $query->setParameter(':id', $billStatus->id(), DB::STRING);
        $query->setParameter(':name', $billStatus->name(), DB::STRING);
        $query->setParameter(':updatedAt', $billStatus->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedBy', $billStatus->updatedBy(), DB::INTEGER);

        $query->where('Id = :id');
        $query->setParameter(':id', $billStatus->id(), DB::STRING);

        $query->execute();
    }

    public function pop(BillStatus $billStatus): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->delete('`BillStatus`');

        $query->where('Id = :id');
        $query->setParameter(':id', $billStatus->id(), DB::STRING);

        $query->execute();
    }
}
