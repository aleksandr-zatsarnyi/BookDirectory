<?php

namespace App\Shared\Service;

use Symfony\Component\Uid\Ulid;

class UlidService {

    public static function generate(): string {
        return Ulid::generate();
    }
}