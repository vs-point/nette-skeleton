<?php

declare(strict_types=1);

$proxies = getenv('TRUSTED_PROXY_IPS');

if ($proxies === '' || $proxies === false) {
  $proxies = [];
}

if (is_string($proxies)) {
  $proxies = explode(',', $proxies);
}

return [
  'parameters' => [
    'http' => [
      'proxy' => $proxies,
    ],
  ],
];
