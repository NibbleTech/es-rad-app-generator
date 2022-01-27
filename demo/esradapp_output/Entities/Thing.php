<?php

declare(strict_types=1);

namespace App\Entities;

use App\Common\Entity;

class Thing implements Entity
{
    public string $createEntityProp1;
    public int $createEntityProp2;
    public string $updateEntityProp1;
    public string $deleteEntityProp1;
}
