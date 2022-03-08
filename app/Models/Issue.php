<?php

namespace App\Models;

use Eloquent as Model;
use App\Models\Merchant;
use App\Traits\ActionByTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @SWG\Definition(
 *      definition="Issue",
 *      required={""},
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Issue extends Model
{
    use SoftDeletes;
    use HasFactory;
    use ActionByTrait;

    public $table = 'issues';
    protected $dates = ['deleted_at'];
    public $fillable = [
        'name',
        'issue_type',
        'status',
        'api_type',
        'resolution',
        'description',
        'issue_at',
        'resolved_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'merchant_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'issue_type' => 'string',
        'status' => 'string',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'merchant_id' => 'integer',
        'issue_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|min:4|max:50',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

}
