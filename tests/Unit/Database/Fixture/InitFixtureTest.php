<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Database\Fixture;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Exception\Runtime\Acl\UserAlreadyExistsException;
use VsPoint\Test\TestCase;

#[CoversClass(InitFixture::class)]
final class InitFixtureTest extends TestCase
{
  /**
   * @throws UserAlreadyExistsException
   */
  #[Group('api')]
  #[Group('postgresql')]
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
