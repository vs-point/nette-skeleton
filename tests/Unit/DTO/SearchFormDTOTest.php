<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\DTO;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use VsPoint\DTO\Common\SearchFormDTO;
use VsPoint\Test\TestCase;

#[CoversClass(SearchFormDTO::class)]
final class SearchFormDTOTest extends TestCase
{
  #[Group('unit')]
  public function testConstructor(): void
  {
    $query = 'search';

    $dto = new SearchFormDTO($query);

    self::assertSame($query, $dto->query);
  }
}
