<?php

declare(strict_types=1);

namespace App\Events;

use App\Common\Event;

class ThingUpdated implements Event
{
    public string $updateEventProp1;
}
