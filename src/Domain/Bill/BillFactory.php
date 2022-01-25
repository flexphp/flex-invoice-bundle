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

use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\BillStatusFactory;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\BillTypeFactory;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\FactoryExtendedTrait;
use Domain\Order\OrderFactory;
use FlexPHP\Bundle\NumerationBundle\Domain\Provider\ProviderFactory;
use FlexPHP\Bundle\UserBundle\Domain\User\UserFactory;

final class BillFactory
{
    use FactoryExtendedTrait;

    public function make($data): Bill
    {
        $bill = new Bill();

        if (\is_object($data)) {
            $data = (array)$data;
        }

        if (isset($data['id'])) {
            $bill->setId((int)$data['id']);
        }

        if (isset($data['prefix'])) {
            $bill->setPrefix((string)$data['prefix']);
        }

        if (isset($data['number'])) {
            $bill->setNumber((int)$data['number']);
        }

        if (isset($data['orderId'])) {
            $bill->setOrderId((int)$data['orderId']);
        }

        if (isset($data['provider'])) {
            $bill->setProvider((string)$data['provider']);
        }

        if (isset($data['status'])) {
            $bill->setStatus((string)$data['status']);
        }

        if (isset($data['type'])) {
            $bill->setType((string)$data['type']);
        }

        if (isset($data['traceId'])) {
            $bill->setTraceId((string)$data['traceId']);
        }

        if (isset($data['hash'])) {
            $bill->setHash((string)$data['hash']);
        }

        if (isset($data['hashType'])) {
            $bill->setHashType((string)$data['hashType']);
        }

        if (isset($data['message'])) {
            $bill->setMessage((string)$data['message']);
        }

        if (isset($data['pdfPath'])) {
            $bill->setPdfPath((string)$data['pdfPath']);
        }

        if (isset($data['xmlPath'])) {
            $bill->setXmlPath((string)$data['xmlPath']);
        }

        if (isset($data['parentId'])) {
            $bill->setParentId((int)$data['parentId']);
        }

        if (isset($data['downloadedAt'])) {
            $bill->setDownloadedAt(\is_string($data['downloadedAt']) ? new \DateTime($data['downloadedAt']) : $data['downloadedAt']);
        }

        if (isset($data['createdAt'])) {
            $bill->setCreatedAt(\is_string($data['createdAt']) ? new \DateTime($data['createdAt']) : $data['createdAt']);
        }

        if (isset($data['updatedAt'])) {
            $bill->setUpdatedAt(\is_string($data['updatedAt']) ? new \DateTime($data['updatedAt']) : $data['updatedAt']);
        }

        if (isset($data['createdBy'])) {
            $bill->setCreatedBy((int)$data['createdBy']);
        }

        if (isset($data['updatedBy'])) {
            $bill->setUpdatedBy((int)$data['updatedBy']);
        }

        if (isset($data['orderId.id'])) {
            $bill->setOrderIdInstance((new OrderFactory())->make($this->getFkEntity('orderId.', $data)));
        }

        if (isset($data['provider.id'])) {
            $bill->setProviderInstance((new ProviderFactory())->make($this->getFkEntity('provider.', $data)));
        }

        if (isset($data['status.id'])) {
            $bill->setStatusInstance((new BillStatusFactory())->make($this->getFkEntity('status.', $data)));
        }

        if (isset($data['type.id'])) {
            $bill->setTypeInstance((new BillTypeFactory())->make($this->getFkEntity('type.', $data)));
        }

        if (isset($data['parentId.id'])) {
            $bill->setParentIdInstance((new BillFactory())->make($this->getFkEntity('parentId.', $data)));
        }

        if (isset($data['createdBy.id'])) {
            $bill->setCreatedByInstance((new UserFactory())->make($this->getFkEntity('createdBy.', $data)));
        }

        if (isset($data['updatedBy.id'])) {
            $bill->setUpdatedByInstance((new UserFactory())->make($this->getFkEntity('updatedBy.', $data)));
        }

        return $bill;
    }
}
