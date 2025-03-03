<?php

declare(strict_types=1);

namespace VsPoint;

use Dotenv\Dotenv;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use Laminas\Stratigility\Middleware\ErrorResponseGenerator;
use Nette\Application\Application;
use Nette\Bootstrap\Configurator;
use Psr\Http\Message\ResponseInterface;
use Relay\Relay;
use Solcik\Doctrine\DBAL\Type\ZonedDateTimeType;
use Solcik\Nette\Forms\Controls\ZonedDateTimeInput;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Throwable;

use function getenv;

final class Bootstrap
{
  public const string TIMEZONE = 'Europe/Prague';

  public static function start(): void
  {
    $request = SymfonyRequest::createFromGlobals();
    $uri = $request->getRequestUri();

    if (\str_starts_with($uri, '/api')) {
      self::startApi();

      return;
    }

    self::startWeb();
  }

  public static function startWeb(): void
  {
    $configurator = self::bootForWeb();
    $container = $configurator->createContainer();
    $app = $container->getByType(Application::class);
    $app->run();
  }

  public static function startApi(): void
  {
    $configurator = self::bootForApi();
    $container = $configurator->createContainer();

    $relay = $container->getByType(Relay::class);
    $emitter = $container->getByType(EmitterInterface::class);

    $serverRequestFactory = ServerRequestFactory::fromGlobals(...);

    $errorResponseGenerator = static function (Throwable $e): ResponseInterface {
      $generator = new ErrorResponseGenerator();

      return $generator($e, new ServerRequest(), new Response());
    };

    $runner = new RequestHandlerRunner($relay, $emitter, $serverRequestFactory, $errorResponseGenerator);
    $runner->run();
  }

  public static function bootForApi(): Configurator
  {
    $configurator = self::prepareConfigurator(enableTracy: false);
    $configurator->addConfig(dirname(__DIR__) . '/config/api/index.neon');

    return self::filterDefaultExtensionsFromConfigurator(
      $configurator,
      ['cache', 'constants', 'decorator', 'di', 'extensions', 'inject', 'latte', 'php', 'search', 'tracy']
    );
  }

  public static function bootForWeb(bool $enableTracy = true): Configurator
  {
    $configurator = self::prepareConfigurator($enableTracy);
    $configurator->addConfig(dirname(__DIR__) . '/config/web/index.neon');

    return $configurator;
  }

  public static function bootForCli(): Configurator
  {
    $configurator = self::prepareConfigurator(enableTracy: false);
    $configurator->addConfig(dirname(__DIR__) . '/config/console/index.neon');

    return self::filterDefaultExtensionsFromConfigurator(
      $configurator,
      ['cache', 'constants', 'decorator', 'di', 'extensions', 'inject', 'latte', 'php', 'search', 'tracy']
    );
  }

  private static function prepareConfigurator(bool $enableTracy = true): Configurator
  {
    $env = Dotenv::createUnsafeImmutable(dirname(__DIR__, 2));
    $env->safeLoad();

    $configurator = new Configurator();
    $configurator->addStaticParameters(
      [
        'rootDir' => dirname(__DIR__, 2),
        'wwwDir' => dirname(__DIR__, 2) . '/public',
        'logDir' => dirname(__DIR__, 2) . '/storage/logs',
      ]
    );
    $configurator->addDynamicParameters([
      'env' => getenv(),
    ]);

    $configurator->setDebugMode(true);
    // $configurator->setDebugMode(false);

    if ($enableTracy) {
      $configurator->enableTracy(dirname(__DIR__, 2) . '/storage/logs');
    }

    $configurator->setTempDirectory(dirname(__DIR__, 2) . '/storage/temp');
    $configurator->setTimeZone(self::TIMEZONE);

    ZonedDateTimeType::$timezone = self::TIMEZONE;
    ZonedDateTimeInput::$timezone = self::TIMEZONE;

    return $configurator;
  }

  /**
   * @param string[] $extensions
   */
  private static function filterDefaultExtensionsFromConfigurator(
    Configurator $configurator,
    array $extensions,
  ): Configurator {
    $configurator->defaultExtensions = array_filter(
      $configurator->defaultExtensions,
      static fn (string $key): bool => in_array($key, $extensions, true),
      ARRAY_FILTER_USE_KEY
    );

    return $configurator;
  }
}
