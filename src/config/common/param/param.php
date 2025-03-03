<?php

declare(strict_types=1);

/** @var string $doctrineProxyAutogenerate */
$doctrineProxyAutogenerate = getenv('DOCTRINE_PROXY_AUTOGENERATE');

$dbUrl = getenv('DATABASE_URL');

$redisUri = getenv('REDIS_DSN');
$redisSentinel = (bool) getenv('REDIS_SENTINEL');
$redisPass = getenv('REDIS_PASS');

$redisUri = explode(',', $redisUri === false ? '' : $redisUri);
if (count($redisUri) === 1) {
  $redisUri = reset($redisUri);
}

$redisOptions = [];
$redisOptions['parameters'] = [];

if ($redisSentinel) {
  $redisOptions['replication'] = 'sentinel';
  $redisOptions['service'] = 'mymaster';
}

if ($redisPass !== false) {
  $redisOptions['parameters']['password'] = $redisPass;
}

return [
  'parameters' => [
    'redis' => [
      'uri' => $redisUri,
      'options' => $redisOptions,
    ],
    'database' => [
      'url' => $dbUrl,
    ],
    'orm' => [
      'proxy' => [
        'autogenerate' => (int) $doctrineProxyAutogenerate,
      ],
    ],
  ],
];
