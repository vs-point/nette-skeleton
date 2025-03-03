<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\VO;

use VsPoint\Test\TestCase;
use VsPoint\VO\Html\PictureSourceVO;
use VsPoint\VO\Html\PictureVO;

/**
 * @covers \VsPoint\VO\Html\PictureVO
 */
final class PictureVOTest extends TestCase
{
  /**
   * @group unit
   */
  public function testInvoke(): void
  {
    $pictureSourceVO1 = new PictureSourceVO('srcSet1', 'image');
    $pictureSourceVO2 = new PictureSourceVO('srcSet1', 'image');

    $imgUrl = 'image-url';
    $imgAlt = 'alt of image';
    $pictureVO = new PictureVO($imgUrl, $imgAlt, [$pictureSourceVO1, $pictureSourceVO2]);

    self::assertSame($pictureVO->getUrl(), $imgUrl);
    self::assertSame($pictureVO->getAlt(), $imgAlt);
    self::assertCount(2, $pictureVO->getSources());
  }
}
