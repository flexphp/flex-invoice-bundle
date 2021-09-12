<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\DbalCriteriaHelper;
use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\Numeration;
use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\NumerationGateway;

class MySQLNumerationGateway implements NumerationGateway
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
            'numeration.Id as id',
            'numeration.Type as type',
            'numeration.Resolution as resolution',
            'numeration.StartAt as startAt',
            'numeration.FinishAt as finishAt',
            'numeration.Prefix as prefix',
            'numeration.FromNumber as fromNumber',
            'numeration.ToNumber as toNumber',
            'numeration.CurrentNumber as currentNumber',
            'numeration.IsActive as isActive',
        ]);
        $query->from('`Numerations`', '`numeration`');

        $query->orderBy('numeration.UpdatedAt', 'DESC');

        $criteria = new DbalCriteriaHelper($query, $offset);

        foreach ($wheres as $column => $value) {
            $criteria->getCriteria('numeration', $column, $value, $this->operator[$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    public function push(Numeration $numeration): int
    {
        $query = $this->conn->createQueryBuilder();

        $query->insert('`Numerations`');

        $query->setValue('Type', ':type');
        $query->setValue('Resolution', ':resolution');
        $query->setValue('StartAt', ':startAt');
        $query->setValue('FinishAt', ':finishAt');
        $query->setValue('Prefix', ':prefix');
        $query->setValue('FromNumber', ':fromNumber');
        $query->setValue('ToNumber', ':toNumber');
        $query->setValue('CurrentNumber', ':currentNumber');
        $query->setValue('IsActive', ':isActive');
        $query->setValue('CreatedAt', ':createdAt');
        $query->setValue('UpdatedAt', ':updatedAt');
        $query->setValue('CreatedBy', ':createdBy');

        $query->setParameter(':type', $numeration->type(), DB::STRING);
        $query->setParameter(':resolution', $numeration->resolution(), DB::STRING);
        $query->setParameter(':startAt', $numeration->startAt(), DB::DATE_MUTABLE);
        $query->setParameter(':finishAt', $numeration->finishAt(), DB::DATE_MUTABLE);
        $query->setParameter(':prefix', $numeration->prefix(), DB::STRING);
        $query->setParameter(':fromNumber', $numeration->fromNumber(), DB::INTEGER);
        $query->setParameter(':toNumber', $numeration->toNumber(), DB::INTEGER);
        $query->setParameter(':currentNumber', $numeration->currentNumber(), DB::INTEGER);
        $query->setParameter(':isActive', $numeration->isActive(), DB::BOOLEAN);
        $query->setParameter(':createdAt', $numeration->createdAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedAt', $numeration->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':createdBy', $numeration->createdBy(), DB::INTEGER);

        $query->execute();

        return (int)$query->getConnection()->lastInsertId();
    }

    public function get(Numeration $numeration): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'numeration.Id as id',
            'numeration.Type as type',
            'numeration.Resolution as resolution',
            'numeration.StartAt as startAt',
            'numeration.FinishAt as finishAt',
            'numeration.Prefix as prefix',
            'numeration.FromNumber as fromNumber',
            'numeration.ToNumber as toNumber',
            'numeration.CurrentNumber as currentNumber',
            'numeration.IsActive as isActive',
            'numeration.CreatedAt as createdAt',
            'numeration.UpdatedAt as updatedAt',
            'numeration.CreatedBy as createdBy',
            'numeration.UpdatedBy as updatedBy',
            'createdBy.id as `createdBy.id`',
            'createdBy.name as `createdBy.name`',
            'updatedBy.id as `updatedBy.id`',
            'updatedBy.name as `updatedBy.name`',
        ]);
        $query->from('`Numerations`', '`numeration`');
        $query->leftJoin('`numeration`', '`Users`', '`createdBy`', 'numeration.CreatedBy = createdBy.id');
        $query->leftJoin('`numeration`', '`Users`', '`updatedBy`', 'numeration.UpdatedBy = updatedBy.id');
        $query->where('numeration.Id = :id');
        $query->setParameter(':id', $numeration->id(), DB::INTEGER);

        return $query->execute()->fetch() ?: [];
    }

    public function shift(Numeration $numeration): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->update('`Numerations`');

        $query->set('Type', ':type');
        $query->set('Resolution', ':resolution');
        $query->set('StartAt', ':startAt');
        $query->set('FinishAt', ':finishAt');
        $query->set('Prefix', ':prefix');
        $query->set('FromNumber', ':fromNumber');
        $query->set('ToNumber', ':toNumber');
        $query->set('CurrentNumber', ':currentNumber');
        $query->set('IsActive', ':isActive');
        $query->set('UpdatedAt', ':updatedAt');
        $query->set('UpdatedBy', ':updatedBy');

        $query->setParameter(':type', $numeration->type(), DB::STRING);
        $query->setParameter(':resolution', $numeration->resolution(), DB::STRING);
        $query->setParameter(':startAt', $numeration->startAt(), DB::DATE_MUTABLE);
        $query->setParameter(':finishAt', $numeration->finishAt(), DB::DATE_MUTABLE);
        $query->setParameter(':prefix', $numeration->prefix(), DB::STRING);
        $query->setParameter(':fromNumber', $numeration->fromNumber(), DB::INTEGER);
        $query->setParameter(':toNumber', $numeration->toNumber(), DB::INTEGER);
        $query->setParameter(':currentNumber', $numeration->currentNumber(), DB::INTEGER);
        $query->setParameter(':isActive', $numeration->isActive(), DB::BOOLEAN);
        $query->setParameter(':updatedAt', $numeration->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedBy', $numeration->updatedBy(), DB::INTEGER);

        $query->where('Id = :id');
        $query->setParameter(':id', $numeration->id(), DB::INTEGER);

        $query->execute();
    }

    public function pop(Numeration $numeration): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->delete('`Numerations`');

        $query->where('Id = :id');
        $query->setParameter(':id', $numeration->id(), DB::INTEGER);

        $query->execute();
    }
}
