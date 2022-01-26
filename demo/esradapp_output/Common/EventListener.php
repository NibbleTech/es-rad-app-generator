<?php

declare(strict_types=1);

namespace App\Common;

interface EventListener
{
    function handle(Event $event): void;
}
