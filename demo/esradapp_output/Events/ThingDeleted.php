<?php

declare(strict_types=1);

namespace App\Events;

use App\Common\Event;

class ThingDeleted implements Event
{
    public string $deleteEventProp1;
}
