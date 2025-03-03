<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\EloquentStatusEnum;

class Eloquent  extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];
	
	protected $casts = [

        'status' => EloquentStatusEnum::class

    ];
}
