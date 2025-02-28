<?php

declare(strict_types=1);

namespace Unit\VO;

use VsPoint\Test\TestCase;
use VsPoint\VO\Html\GtmVO;
use VsPoint\VO\Html\PictureSourceVO;

/**
 * @covers \VsPoint\VO\Html\HtmlVO
 */
final class PictureSourceVOTest extends TestCase
{
  /**
   * @group unit
   */
  public function testInvoke(): void
  {
    $pictureSourceVO = new PictureSourceVO(
      'srcSet',
      'image'
    );

    self::assertSame($pictureSourceVO->getSrcset(), 'srcSet');
    self::assertSame($pictureSourceVO->getMedia(), 'image');
  }
}
