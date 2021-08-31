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

use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\Request\CreateNumerationRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\Request\DeleteNumerationRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\Request\IndexNumerationRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\Request\ReadNumerationRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\Request\UpdateNumerationRequest;

final class NumerationRepository
{
    private NumerationGateway $gateway;

    public function __construct(NumerationGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return array<Numeration>
     */
    public function findBy(IndexNumerationRequest $request): array
    {
        return \array_map(function (array $numeration) {
            return (new NumerationFactory())->make($numeration);
        }, $this->gateway->search((array)$request, [], $request->_page, $request->_limit, $request->_offset));
    }

    public function add(CreateNumerationRequest $request): Numeration
    {
        $numeration = (new NumerationFactory())->make($request);

        $numeration->setId($this->gateway->push($numeration));

        return $numeration;
    }

    public function getById(ReadNumerationRequest $request): Numeration
    {
        $factory = new NumerationFactory();
        $data = $this->gateway->get($factory->make($request));

        return $factory->make($data);
    }

    public function change(UpdateNumerationRequest $request): Numeration
    {
        $factory = new NumerationFactory();
        $numeration = $factory->make($request);

        if (!empty($request->_patch)) {
            $data = $this->gateway->get($numeration);

            $numeration = $factory->patch($request, $data);
        }

        $this->gateway->shift($numeration);

        return $numeration;
    }

    public function remove(DeleteNumerationRequest $request): Numeration
    {
        $factory = new NumerationFactory();
        $data = $this->gateway->get($factory->make($request));

        $numeration = $factory->make($data);

        $this->gateway->pop($numeration);

        return $numeration;
    }
}
