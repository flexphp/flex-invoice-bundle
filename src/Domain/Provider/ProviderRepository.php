<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\Provider;

use FlexPHP\Bundle\InvoiceBundle\Domain\Provider\Request\CreateProviderRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Provider\Request\DeleteProviderRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Provider\Request\IndexProviderRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Provider\Request\ReadProviderRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Provider\Request\UpdateProviderRequest;

final class ProviderRepository
{
    private ProviderGateway $gateway;

    public function __construct(ProviderGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return array<Provider>
     */
    public function findBy(IndexProviderRequest $request): array
    {
        return \array_map(function (array $provider) {
            return (new ProviderFactory())->make($provider);
        }, $this->gateway->search((array)$request, [], $request->_page, $request->_limit, $request->_offset));
    }

    public function add(CreateProviderRequest $request): Provider
    {
        $provider = (new ProviderFactory())->make($request);

        $provider->setId($this->gateway->push($provider));

        return $provider;
    }

    public function getById(ReadProviderRequest $request): Provider
    {
        $factory = new ProviderFactory();
        $data = $this->gateway->get($factory->make($request));

        return $factory->make($data);
    }

    public function change(UpdateProviderRequest $request): Provider
    {
        $factory = new ProviderFactory();
        $provider = $factory->make($request);

        if (!empty($request->_patch)) {
            $data = $this->gateway->get($provider);

            $provider = $factory->patch($request, $data);
        }

        $this->gateway->shift($provider);

        return $provider;
    }

    public function remove(DeleteProviderRequest $request): Provider
    {
        $factory = new ProviderFactory();
        $data = $this->gateway->get($factory->make($request));

        $provider = $factory->make($data);

        $this->gateway->pop($provider);

        return $provider;
    }
}
