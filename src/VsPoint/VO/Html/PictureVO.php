<?php

declare(strict_types=1);

namespace VsPoint\VO\Html;

final class PictureVO
{
  private string $url;

  private string $alt;

  /**
   * @var PictureSourceVO[]
   */
  private array $sources;

  public function __construct(string $url, string $alt, array $sources = [])
  {
    $this->url = $url;
    $this->alt = $alt;
    $this->sources = $sources;
  }

  public function getUrl(): string
  {
    return $this->url;
  }

  public function getAlt(): string
  {
    return $this->alt;
  }

  /**
   * @return PictureSourceVO[]
   */
  public function getSources(): array
  {
    return $this->sources;
  }
}
