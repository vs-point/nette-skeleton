<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\VO;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use VsPoint\Test\TestCase;
use VsPoint\VO\Html\OgVO;

#[CoversClass(OgVO::class)]
final class OgVOTest extends TestCase
{
  #[Group('unit')]
  public function testInvoke(): void
  {
    $ogVoTitle = 'Og title';
    $ogVoDescription = 'Lorem ipsum';
    $ogVoImageUrl = '/img/og/vs-point.png';
    $ogVoType = 'website';

    $ogVo = new OgVO($ogVoTitle, $ogVoDescription, $ogVoImageUrl, $ogVoType);

    self::assertSame($ogVo->getTitle(), $ogVoTitle);
    self::assertSame($ogVo->getType(), $ogVoType);
    self::assertSame($ogVo->getSiteName(), 'VS-point');
    self::assertSame($ogVo->getLocale(), 'cs_CZ');
    self::assertSame($ogVo->getTitleFull(), 'Og title - VS-point');
    self::assertSame($ogVo->getDescription(), $ogVoDescription);
    self::assertSame($ogVo->getImageUrl(), $ogVoImageUrl);
  }
}
