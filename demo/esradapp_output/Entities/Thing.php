<?php

declare(strict_types=1);

namespace App\Entities;

use App\Common\Entity;

class Thing implements Entity
{
    public $createEventProp1;
    public $createEventProp2;
    public $updateEventProp1;
    public $deleteEventProp1;
}
