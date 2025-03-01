<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\VO;

use VsPoint\Test\TestCase;
use VsPoint\VO\Html\MetadataVO;
use VsPoint\VO\Html\OgVO;

/**
 * @covers \VsPoint\VO\Html\MetadataVO
 */
final class MetadataVOTest extends TestCase
{
  /**
   * @group unit
   */
  public function testInvoke(): void
  {
    $ogVo = new OgVO('Og title', 'Lorem ipsum', '/img/og/vs-point.png', 'website',);

    $metadataVoTitle = 'metadata title';
    $metadataVo = new MetadataVO(
      $metadataVoTitle,
      'metadata long description',
      ['keyword 1', 'keyword 2', 'keyword 3'],
      $ogVo
    );

    self::assertSame($metadataVo->getTitle(), $metadataVoTitle);
    self::assertSame($metadataVo->getTitleFull(), 'metadata title - VS-point');
    self::assertSame($metadataVo->getDescription(), 'metadata long description');
    self::assertSame($metadataVo->getKeywordsString(), 'keyword 1,keyword 2,keyword 3');
    self::assertCount(3, $metadataVo->getKeywords());
    self::assertTrue($metadataVo->hasOg());
    self::assertInstanceOf(OgVO::class, $metadataVo->getOg());
  }
}
