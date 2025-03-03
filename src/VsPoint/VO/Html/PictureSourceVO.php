<?php

declare(strict_types=1);

namespace VsPoint\VO\Html;

final readonly class PictureSourceVO
{
  private string $srcset;

  private string $media;

  public function __construct(string $srcset, string $media)
  {
    $this->srcset = $srcset;
    $this->media = $media;
  }

  public function getSrcset(): string
  {
    return $this->srcset;
  }

  public function getMedia(): string
  {
    return $this->media;
  }
}
