<?php

namespace App\Models;

use App\Traits\ActionByTrait;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Attendance
 * @package App\Models
 * @version June 9, 2022, 10:14 am +07
 *
 */
class Attendance extends Model
{
    use SoftDeletes;
    use HasFactory;
    use ActionByTrait;

    public $table = 'attendances';
    protected $dates = ['deleted_at'];
    public $fillable = [
        'clock_in',
        'clock_out',
        'working_hours',
        'reason',
        'location',
        'is_late',
        'user_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];


}
