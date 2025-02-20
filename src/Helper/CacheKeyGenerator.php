<?php

namespace Hubertinio\SyliusKeyValuePlugin\Helper;

class CacheKeyGenerator implements CacheKeyGeneratorInterface
{
    public static function generate(string $prefix, string $key, array $context = [], int $maxLength = 250): string
    {
        $safePrefix = preg_replace('/[^a-zA-Z0-9_\-]/', '_', strtolower($prefix));
        $safeKey = preg_replace('/[^a-zA-Z0-9_\-]/', '_', strtolower($key));

        $contextHash = '';

        if ($context) {
            $contextHash = '_' . substr(hash('sha256', json_encode($context)), 0, 16);
        }

        $cacheKey = "{$safePrefix}_{$safeKey}{$contextHash}";

        if (strlen($cacheKey) > $maxLength) {
            $cacheKey = substr($cacheKey, 0, $maxLength - 8) . '_' . substr(md5($cacheKey), 0, 8);
        }

        return $cacheKey;
    }
}