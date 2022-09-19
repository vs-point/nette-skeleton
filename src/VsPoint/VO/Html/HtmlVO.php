<?php

declare(strict_types=1);

namespace VsPoint\VO\Html;

final class HtmlVO
{
  private string $title;

  private GtmVO $gtm;

  private MetadataVO $metadata;

  public function __construct(string $title, GtmVO $gtm, MetadataVO $metadata)
  {
    $this->title = $title;
    $this->gtm = $gtm;
    $this->metadata = $metadata;
  }

  public static function create(
    string $title,
    string $description,
    array $keywords,
    string $gtmPage,
    ?string $ogImageUrl = null,
    array $gtmEvents = [],
  ): self {
    return new self(
      $title,
      new GtmVO($gtmPage, $gtmEvents),
      new MetadataVO($title, $description, $keywords, new OgVO($title, $description, $ogImageUrl))
    );
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function getGtm(): GtmVO
  {
    return $this->gtm;
  }

  public function getMetadata(): MetadataVO
  {
    return $this->metadata;
  }
}
