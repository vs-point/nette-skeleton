<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Infrastructure\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use VsPoint\Infrastructure\Doctrine\ORM\Query\AST\Functions\RandomFunction;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Infrastructure\Doctrine\ORM\Query\AST\Functions\RandomFunction
 */
final class RandomFunctionTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  public function testParse(): void
  {
    $parser = Mockery::mock(Parser::class);
    $parser->shouldReceive('match')->with(Lexer::T_IDENTIFIER)->once();
    $parser->shouldReceive('match')->with(Lexer::T_OPEN_PARENTHESIS)->once();
    $parser->shouldReceive('match')->with(Lexer::T_CLOSE_PARENTHESIS)->once();

    $randomFunction = new RandomFunction('RANDOM');
    $randomFunction->parse($parser);
  }

  public function testGetSql(): void
  {
    $sqlWalker = Mockery::mock(SqlWalker::class);
    $randomFunction = new RandomFunction('RANDOM');

    self::assertSame('RANDOM()', $randomFunction->getSql($sqlWalker));
  }
}
