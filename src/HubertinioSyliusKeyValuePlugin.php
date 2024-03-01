<?php

declare(strict_types=1);

namespace Hubertinio\SyliusKeyValuePlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class HubertinioSyliusKeyValuePlugin extends Bundle
{
    use SyliusPluginTrait;

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
