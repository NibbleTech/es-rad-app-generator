<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Thing;
use Ramsey\Uuid\UuidInterface;

class ThingRepository
{
    public function find(UuidInterface $id): Thing
    {
    }

    public function delete(Thing $entity): void
    {
    }

    public function persist(Thing $entity): Thing
    {
    }

    public function findBy(array $properties): ?Thing
    {
    }
}
