<?php

declare(strict_types=1);

namespace VsPoint\VO\Html;

final readonly class OgVO
{
  private string $title;

  private string $description;

  private string $imageUrl;

  private string $type;

  private string $siteName;

  private string $locale;

  public function __construct(
    string $title,
    string $description,
    ?string $imageUrl = null,
    string $type = 'website',
    string $siteName = 'VS-point',
    string $locale = 'cs_CZ',
  ) {
    $this->title = $title;
    $this->description = $description;
    $this->imageUrl = $imageUrl ?? '/img/og/vs-point.png';
    $this->type = $type;
    $this->siteName = $siteName;
    $this->locale = $locale;
  }

  public function getType(): string
  {
    return $this->type;
  }

  public function getSiteName(): string
  {
    return $this->siteName;
  }

  public function getLocale(): string
  {
    return $this->locale;
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

  public function getImageUrl(): string
  {
    return $this->imageUrl;
  }
}
