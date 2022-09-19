<?php

declare(strict_types=1);

namespace VsPoint\VO\Html;

final class MetadataVO
{
  private string $title;

  private string $description;

  /**
   * @var string[]
   */
  private array $keywords;

  private ?OgVO $og;

  /**
   * @param string[] $keywords
   */
  public function __construct(string $title, string $description, array $keywords, ?OgVO $og = null)
  {
    $this->title = $title;
    $this->description = $description;
    $this->keywords = $keywords;
    $this->og = $og;
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function getTitleFull(): string
  {
    return sprintf('%s - VS-point', $this->getTitle());
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  /**
   * @return string[]
   */
  public function getKeywords(): array
  {
    return $this->keywords;
  }

  public function getKeywordsString(): string
  {
    return implode(',', $this->getKeywords());
  }

  public function hasOg(): bool
  {
    return $this->og !== null;
  }

  public function getOg(): ?OgVO
  {
    return $this->og;
  }
}
