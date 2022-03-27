<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Address
 * @package App\Models
 * @version March 27, 2022, 1:54 pm +07
 *
 */
class Address extends Model
{
    use HasFactory;

    public $table = 'addresses';
    protected $primaryKey = '_code';

    protected $fillable = [
        '_code',
        '_name_kh',
        '_name_en',
        '_path_kh',
        '_path_en',
        '_type_kh',
        '_type_en',
        '_offical_note',
        '_note',
        'boundary',
        'center',
        'image'
    ];

    protected $casts = [
        '_code' => 'string',
        '_name_kh' => 'string',
        '_name_en' => 'string',
        '_path_en' => 'string',
        '_path_kh' => 'string',
        '_type_en' => 'string',
        '_type_kh' => 'string',
        'boundary' => 'array',
        'center' => 'array',
        'image' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

}
