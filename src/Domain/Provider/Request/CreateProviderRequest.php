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

final class CreateProviderRequest implements RequestInterface
{
    public $id;

    public $name;

    public $username;

    public $password;

    public $url;

    public $isActive;

    public $createdBy;

    public function __construct(array $data, int $createdBy)
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->username = $data['username'] ?? null;
        $this->password = $data['password'] ?? null;
        $this->url = $data['url'] ?? null;
        $this->isActive = $data['isActive'] ?? null;
        $this->createdBy = $createdBy;
    }
}
