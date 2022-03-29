<?php

namespace App\Repositories;

use Exception;
use App\Models\Address;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Repositories\BaseRepository;
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

    public function replaceSigns(string $str)
    {
        return str_replace([' ', '<', '>', '&', '{', '}', '*'], ['_'], strtolower($str));
    }

    public function addressBoundary($request)
    {
        try {
            $model = $this->model;
            $code = $request->code ?? '';
            $subLevel = $request->sub_level ?? true;

            $disk = 'public';
            $fileName = 'cambodia';

            if ($code) {
                $codePlace = $model
                    ->selectRaw('_code AS admin_code, _name_en AS name')
                    ->where('_code', $code)
                    ->first();

                $fileName = $this->replaceSigns((string)$codePlace->admin_code . '_' . (string)$codePlace->name);
            }

            $boundaryFile = "boundary/{$fileName}_boundary.geojson";
            $pointFile = "point/{$fileName}_point.geojson";

            // if (!Storage::disk($disk)->exists($boundaryFile)) {
            $addresses = $model
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

            $geoJson = [
                'type' => 'FeatureCollection',
                'name' => "{$fileName}_boundary",
                'features' => []
            ];

            $pointGeoJson = [
                'type' => 'FeatureCollection',
                'name' => "{$fileName}_point",
                'features' => []
            ];

            $addresses
                ->take(500)
                ->get()
                ->each(function($v) use ($model, &$geoJson, &$pointGeoJson) {
                    collect([
                        'geoJson' => (array)json_decode($v->boundary),
                        'pointGeoJson' => (array)json_decode($v->center),
                    ])->each(function($vv, $kk) use ($v, $model, &$geoJson, &$pointGeoJson) {
                        $vv['id'] = $v->admin_code;
                        $type = 'district';
                        if (strlen($v->admin_code) == 4) {
                            $type = 'commune';
                        } elseif (strlen($v->admin_code) == 6) {
                            $type = 'village';
                        }
                        $vv['properties']['id'] = $v->admin_code;
                        $vv['properties']['name'] = $v->_name_en;
                        $countSub = $model->where("_code", "LIKE", $v->admin_code . '__')->count();
                        $type = $countSub > 1 ? Str::plural($type) : $type;
                        $vv['properties']['description'] = "
                            <div>{$v->_name_en}</div>
                            <div>
                                {$countSub}
                                {$type}
                            </div>
                        ";
                        ${$kk}['features'][] = $vv;
                    });
                });

            collect([
                $boundaryFile => $geoJson,
                $pointFile => $pointGeoJson,
            ])->each(function ($v, $k) use ($disk) {
                Storage::disk($disk)->put($k, json_encode($v));
            });

            return [
                'boundary' => $boundaryFile,
                'point' => $pointFile,
            ];
        } catch (Exception $th) {
            Log::error(self::class. '::' . __FUNCTION__ . '() : ' . $th);
            return false;
        }
    }
}
