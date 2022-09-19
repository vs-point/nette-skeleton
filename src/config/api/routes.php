<?php

declare(strict_types=1);

use VsPoint\Http\Api\Ping;
use VsPoint\Http\Api\Pong;
use VsPoint\Infrastructure\Http\Route;

return [
  'parameters' => [
    'http' => [
      'api' => [
        'routes' => [new Route('GET', '/api/ping', Ping::class), new Route('POST', '/api/pong', Pong::class)],
      ],
    ],
  ],
];
