<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\DTO;

use VsPoint\DTO\Common\SearchFormDTO;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\DTO\Common\SearchFormDTO
 */
final class SearchFormDTOTest extends TestCase
{
  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    $query = 'search';

    $dto = new SearchFormDTO($query);

    self::assertSame($query, $dto->query);
  }
}
