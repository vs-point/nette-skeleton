<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\VO;

use VsPoint\Test\TestCase;
use VsPoint\VO\Html\GtmVO;

/**
 * @covers \VsPoint\VO\Html\GtmVO
 */
final class GtmVOTest extends TestCase
{
  /**
   * @group unit
   */
  public function testInvoke(): void
  {
    $gtmVoPage = 'test page';
    $gtmVo = new GtmVO($gtmVoPage, ['event 1', 'event 2'],);

    self::assertSame($gtmVo->getPage(), $gtmVoPage);
    self::assertTrue($gtmVo->hasEvents());
    self::assertCount(2, $gtmVo->getEvents());
  }
}
