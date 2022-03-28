<?php

namespace App\Repositories;

use App\Models\Address;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Class AddressRepository
 * @package App\Repositories
 * @version March 27, 2022, 1:54 pm +07
*/

class AddressRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [

    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Address::class;
    }

    public function addressBoundary($request)
    {
        try {
            $disk = 'public';
            $file = 'boundary/cambodia_boundary.geojson';
            if (!Storage::disk($disk)->exists($file)) {
                $code = $request->code ?? '';
                $subLevel = $request->sub_level ?? true;
                $addresses = $this->model
                    ->selectRaw('_code AS admin_code, boundary, center, _name_en, _name_kh, _type_en AS type')
                    ->whereNotNull('boundary');

                if ($subLevel) {
                    if (!$code) {
                        $addresses = $addresses->whereRaw("LENGTH(_code) = 2");
                    } else {
                        $addresses = $addresses->where("_code", "LIKE", $code . '__');
                    }
                } else {
                    if (!$code) {
                        throw new Exception('Error');
                    }
                    $addresses = $addresses->where('_code', $code);
                }
                // $str = str_replace(array(' ', '<', '>', '&', '{', '}', '*'), array('-'), $str);

                $geoJson = [
                    'type' => 'FeatureCollection',
                    'name' => 'cambodia_boundary',
                    'features' => []
                ];

                $addresses
                    ->take(500)
                    ->get()
                    ->each(function($v) use (&$geoJson) {
                        $boundary = (array)json_decode($v->boundary);
                        $boundary['id'] = $v->admin_code;
                        $boundary['properties']['id'] = $v->admin_code;
                        $boundary['properties']['name'] = $v->_name_en;
                        $geoJson['features'][] = $boundary;
                    });

                Storage::disk($disk)->put($file, json_encode($geoJson));
            }

            return Storage::disk($disk)->url($file);
        } catch (Exception $th) {
            Log::error(self::class. '::' . __FUNCTION__ . '() : ' . $th);
            return false;
        }
    }
}
