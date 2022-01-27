<?php

declare(strict_types=1);

namespace App\Events;

use App\Common\Event;

class ThingSent implements Event
{
    public string $createEventProp1;
    public int $createEventProp2;
}
