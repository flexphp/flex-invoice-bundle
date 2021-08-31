<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\Provider\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\DbalCriteriaHelper;
use FlexPHP\Bundle\InvoiceBundle\Domain\Provider\Provider;
use FlexPHP\Bundle\InvoiceBundle\Domain\Provider\ProviderGateway;

class MySQLProviderGateway implements ProviderGateway
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
            'provider.Id as id',
            'provider.Name as name',
            'provider.Username as username',
            'provider.Password as password',
            'provider.Url as url',
            'provider.IsActive as isActive',
        ]);
        $query->from('`Providers`', '`provider`');

        $query->orderBy('provider.UpdatedAt', 'DESC');

        $criteria = new DbalCriteriaHelper($query, $offset);

        foreach ($wheres as $column => $value) {
            $criteria->getCriteria('provider', $column, $value, $this->operator[$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    public function push(Provider $provider): string
    {
        $query = $this->conn->createQueryBuilder();

        $query->insert('`Providers`');

        $query->setValue('Id', ':id');
        $query->setValue('Name', ':name');
        $query->setValue('Username', ':username');
        $query->setValue('Password', ':password');
        $query->setValue('Url', ':url');
        $query->setValue('IsActive', ':isActive');
        $query->setValue('CreatedAt', ':createdAt');
        $query->setValue('UpdatedAt', ':updatedAt');
        $query->setValue('CreatedBy', ':createdBy');

        $query->setParameter(':id', $provider->id(), DB::STRING);
        $query->setParameter(':name', $provider->name(), DB::STRING);
        $query->setParameter(':username', $provider->username(), DB::STRING);
        $query->setParameter(':password', $provider->password(), DB::STRING);
        $query->setParameter(':url', $provider->url(), DB::STRING);
        $query->setParameter(':isActive', $provider->isActive(), DB::BOOLEAN);
        $query->setParameter(':createdAt', $provider->createdAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedAt', $provider->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':createdBy', $provider->createdBy(), DB::INTEGER);

        $query->execute();

        return $provider->id();
    }

    public function get(Provider $provider): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'provider.Id as id',
            'provider.Name as name',
            'provider.Username as username',
            'provider.Password as password',
            'provider.Url as url',
            'provider.IsActive as isActive',
            'provider.CreatedAt as createdAt',
            'provider.UpdatedAt as updatedAt',
            'provider.CreatedBy as createdBy',
            'provider.UpdatedBy as updatedBy',
            'createdBy.id as `createdBy.id`',
            'createdBy.name as `createdBy.name`',
            'updatedBy.id as `updatedBy.id`',
            'updatedBy.name as `updatedBy.name`',
        ]);
        $query->from('`Providers`', '`provider`');
        $query->leftJoin('`provider`', '`Users`', '`createdBy`', 'provider.CreatedBy = createdBy.id');
        $query->leftJoin('`provider`', '`Users`', '`updatedBy`', 'provider.UpdatedBy = updatedBy.id');
        $query->where('provider.Id = :id');
        $query->setParameter(':id', $provider->id(), DB::STRING);

        return $query->execute()->fetch() ?: [];
    }

    public function shift(Provider $provider): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->update('`Providers`');

        $query->set('Id', ':id');
        $query->set('Name', ':name');
        $query->set('Username', ':username');
        $query->set('Password', ':password');
        $query->set('Url', ':url');
        $query->set('IsActive', ':isActive');
        $query->set('UpdatedAt', ':updatedAt');
        $query->set('UpdatedBy', ':updatedBy');

        $query->setParameter(':id', $provider->id(), DB::STRING);
        $query->setParameter(':name', $provider->name(), DB::STRING);
        $query->setParameter(':username', $provider->username(), DB::STRING);
        $query->setParameter(':password', $provider->password(), DB::STRING);
        $query->setParameter(':url', $provider->url(), DB::STRING);
        $query->setParameter(':isActive', $provider->isActive(), DB::BOOLEAN);
        $query->setParameter(':updatedAt', $provider->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedBy', $provider->updatedBy(), DB::INTEGER);

        $query->where('Id = :id');
        $query->setParameter(':id', $provider->id(), DB::STRING);

        $query->execute();
    }

    public function pop(Provider $provider): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->delete('`Providers`');

        $query->where('Id = :id');
        $query->setParameter(':id', $provider->id(), DB::STRING);

        $query->execute();
    }
}
