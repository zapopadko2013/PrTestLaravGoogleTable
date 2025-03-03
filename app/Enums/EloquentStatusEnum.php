<?php

  

namespace App\Enums;

 

enum EloquentStatusEnum:string {

    case Allowed = 'Allowed';

    case Prohibited = 'Prohibited';

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }

}