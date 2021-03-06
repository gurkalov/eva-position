<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(schema="NewPosition", required={"name"},
 *     @OA\Property(property="beacons", format="array", type="array",
 *         @OA\Items(ref="#/components/schemas/BeaconSignal")
 *     )
 * )
 */

/**
 * @OA\Schema(schema="Position", type="object", required={"id", "user_id"},
 *    @OA\Property(property="id", format="int64", type="integer", example=1),
 *    @OA\Property(property="user_id", format="int64", type="integer", example=1),
 *    @OA\Property(property="created_at", format="string", type="string", example="2019-01-26 20:00:57"),
 *    @OA\Property(property="beacons", format="array", type="array", nullable=true,
 *        @OA\Items(ref="#/components/schemas/BeaconSignal")
 *    )
 * )
 */

class Position extends Model
{
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'beacons' => 'array',
    ];

    protected $fillable = [
        'beacons',
    ];

    protected $visible = [
        'id',
        'user_id',
        'beacons',
        'created_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if (!is_array($model->beacons)) {
                $model->beacons = [];
            }
        });
    }
}
