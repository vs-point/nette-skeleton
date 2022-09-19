<?php

declare(strict_types=1);

namespace VsPoint\Infrastructure\Nette\Forms;

use Brick\DateTime\LocalDateTime;
use Brick\DateTime\TimeZone;
use Brick\DateTime\ZonedDateTime;

class ZonedDateTimeInput extends LocalDateTimeInput
{
  public static string $timezone = 'Europe/Prague';

  public function getValue()
  {
    $val = parent::getValue();
    if (!$this->isValidated) {
      return $val;
    }

    if ($val === null || $val === '') {
      return null;
    }

    assert($val instanceof LocalDateTime);

    return $val->atTimeZone(TimeZone::parse(self::$timezone));
  }

  public function setValue($value)
  {
    if ($value === null) {
      parent::setValue(null);
    } elseif ($value instanceof ZonedDateTime) {
      parent::setValue($value->getDateTime()->__toString());

      $this->validate();
    } else {
      parent::setValue($value);
    }

    return $this;
  }
}
