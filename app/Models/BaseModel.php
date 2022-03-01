<?php

namespace App\Models;

use App\Traits\ActionByTrait;
use Eloquent as Model;

class BaseModel extends Model
{
    use ActionByTrait;
}
