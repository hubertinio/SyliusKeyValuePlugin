<?php

namespace Hubertinio\SyliusKeyValuePlugin\Helper;

interface CacheKeyGeneratorInterface
{
    public static function generate(string $prefix, string $key, array $context = [], int $maxLength = 250): string;
}