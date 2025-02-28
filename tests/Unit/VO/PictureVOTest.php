<?php

declare(strict_types=1);

namespace Unit\VO;

use VsPoint\Test\TestCase;
use VsPoint\VO\Html\GtmVO;
use VsPoint\VO\Html\PictureSourceVO;
use VsPoint\VO\Html\PictureVO;

/**
 * @covers \VsPoint\VO\Html\HtmlVO
 */
final class PictureVOTest extends TestCase
{
  /**
   * @group unit
   */
  public function testInvoke(): void
  {
    $pictureSourceVO1 = new PictureSourceVO(
      'srcSet1',
      'image'
    );
    $pictureSourceVO2 = new PictureSourceVO(
      'srcSet1',
      'image'
    );

    $pictureVO = new PictureVO(
      'image-url',
      'alt of image',
      [$pictureSourceVO1, $pictureSourceVO2]
    );

    self::assertSame($pictureVO->getUrl(), 'srcSet');
    self::assertSame($pictureVO->getAlt(), 'image');
    self::assertIsArray($pictureVO->getSources());
  }
}
