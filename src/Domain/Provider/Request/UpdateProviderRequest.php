<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\Provider\Request;

use FlexPHP\Messages\RequestInterface;

final class UpdateProviderRequest implements RequestInterface
{
    public $id;

    public $name;

    public $username;

    public $password;

    public $url;

    public $isActive;

    public $updatedBy;

    public $_patch;

    public function __construct(string $id, array $data, int $updatedBy, bool $_patch = false)
    {
        $this->id = $id;
        $this->name = $data['name'] ?? null;
        $this->username = $data['username'] ?? null;
        $this->password = $data['password'] ?? null;
        $this->url = $data['url'] ?? null;
        $this->isActive = $data['isActive'] ?? null;
        $this->updatedBy = $updatedBy;
        $this->_patch = $_patch;
    }
}
