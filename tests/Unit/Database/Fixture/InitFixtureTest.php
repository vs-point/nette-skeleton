<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Database\Fixture;

use Doctrine\ORM\EntityManagerInterface;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Exception\Runtime\Acl\UserAlreadyExistsException;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Database\Fixture\InitFixture
 *
 * @internal
 */
final class InitFixtureTest extends TestCase
{
  /**
   * @group api
   * @group postgresql
   *
   * @throws UserAlreadyExistsException
   */
  public function testInvoke(): void
  {
    $this->expectException(UserAlreadyExistsException::class);

    $container = $this->createContainer();

    $fixture = $container->getByType(InitFixture::class);
    $manager = $container->getByType(EntityManagerInterface::class);

    $fixture->load($manager);

    self::assertStringContainsString('Done', 'Done!');
  }
}
