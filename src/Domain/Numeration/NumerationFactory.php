<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\Numeration;

use FlexPHP\Bundle\HelperBundle\Domain\Helper\FactoryExtendedTrait;
use FlexPHP\Bundle\UserBundle\Domain\User\UserFactory;

final class NumerationFactory
{
    use FactoryExtendedTrait;

    public function make($data): Numeration
    {
        $numeration = new Numeration();

        if (\is_object($data)) {
            $data = (array)$data;
        }

        if (isset($data['id'])) {
            $numeration->setId((int)$data['id']);
        }

        if (isset($data['resolution'])) {
            $numeration->setResolution((string)$data['resolution']);
        }

        if (isset($data['startAt'])) {
            $numeration->setStartAt(\is_string($data['startAt']) ? new \DateTime($data['startAt']) : $data['startAt']);
        }

        if (isset($data['finishAt'])) {
            $numeration->setFinishAt(\is_string($data['finishAt']) ? new \DateTime($data['finishAt']) : $data['finishAt']);
        }

        if (isset($data['prefix'])) {
            $numeration->setPrefix((string)$data['prefix']);
        }

        if (isset($data['fromNumber'])) {
            $numeration->setFromNumber((int)$data['fromNumber']);
        }

        if (isset($data['toNumber'])) {
            $numeration->setToNumber((int)$data['toNumber']);
        }

        if (isset($data['currentNumber'])) {
            $numeration->setCurrentNumber((int)$data['currentNumber']);
        }

        if (isset($data['isActive'])) {
            $numeration->setIsActive((bool)$data['isActive']);
        }

        if (isset($data['createdAt'])) {
            $numeration->setCreatedAt(\is_string($data['createdAt']) ? new \DateTime($data['createdAt']) : $data['createdAt']);
        }

        if (isset($data['updatedAt'])) {
            $numeration->setUpdatedAt(\is_string($data['updatedAt']) ? new \DateTime($data['updatedAt']) : $data['updatedAt']);
        }

        if (isset($data['createdBy'])) {
            $numeration->setCreatedBy((int)$data['createdBy']);
        }

        if (isset($data['updatedBy'])) {
            $numeration->setUpdatedBy((int)$data['updatedBy']);
        }

        if (isset($data['createdBy.id'])) {
            $numeration->setCreatedByInstance((new UserFactory())->make($this->getFkEntity('createdBy.', $data)));
        }

        if (isset($data['updatedBy.id'])) {
            $numeration->setUpdatedByInstance((new UserFactory())->make($this->getFkEntity('updatedBy.', $data)));
        }

        return $numeration;
    }
}
