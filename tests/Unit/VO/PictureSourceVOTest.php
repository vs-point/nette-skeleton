<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\VO;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use VsPoint\Test\TestCase;
use VsPoint\VO\Html\PictureSourceVO;

#[CoversClass(PictureSourceVO::class)]
final class PictureSourceVOTest extends TestCase
{
  #[Group('unit')]
  public function testInvoke(): void
  {
    $pictureSourceVO = new PictureSourceVO('srcSet', 'image');

    self::assertSame($pictureSourceVO->getSrcset(), 'srcSet');
    self::assertSame($pictureSourceVO->getMedia(), 'image');
  }
}
