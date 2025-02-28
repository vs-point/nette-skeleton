<?php

declare(strict_types=1);

namespace Unit\VO;

use VsPoint\Test\TestCase;
use VsPoint\VO\Html\GtmVO;
use VsPoint\VO\Html\HtmlVO;
use VsPoint\VO\Html\MetadataVO;

/**
 * @covers \VsPoint\VO\Html\HtmlVO
 */
final class HtmlVOTest extends TestCase
{
  /**
   * @group unit
   */
  public function testInvoke(): void
  {
    $htmlVOTitle = 'html title';

    $htmlVO = HtmlVO::create(
      $htmlVOTitle,
      'html description',
      ['keyword 1', 'keyword 2', 'keyword 3'],
      'test page',
      '/img/og/vs-point.png',
      ['event 1', 'event 2'],
    );

    self::assertSame($htmlVOTitle, $htmlVO->getTitle());
    self::assertInstanceOf(GtmVO::class, $htmlVO->getGtm());
    self::assertInstanceOf(MetadataVO::class, $htmlVO->getMetadata());
  }
}
