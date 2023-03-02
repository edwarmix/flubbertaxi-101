<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class VehicleType
 * @package App\Models
 * @version Aug 22, 2022, 11:06 am UTC
 *
 * @property string $name
 */
class VehicleType extends Model implements HasMedia
{
    use InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }
    use HasFactory;

    public $table = 'vehicle_types';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'name',
        'base_price',
        'base_distance',
        'additional_distance_pricing',
        'app_tax',
        'default',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'base_price' => 'decimal:2',
        'base_distance' => 'decimal:2',
        'additional_distance_pricing' => 'decimal:2',
        'app_tax' => 'decimal:2',
        'default' => 'boolean',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp|max:512',
        'name' => 'required|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    protected $appends = [
        'has_media',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('icon')
            ->width(50)
            ->height(50);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default')
            ->useFallbackUrl(asset('/images/image_default.png'))
            ->useFallbackPath(asset('/images/image_default.png'));
    }

    /**
     * to generate media url in case of fallback will
     * return the file type icon
     * @param string $conversion
     * @return string url
     */
    public function getFirstMediaUrl($collectionName = 'default', $conversion = '')
    {
        $url = $this->getFirstMediaUrlTrait($collectionName);

        $array = explode('.', $url);
        $extension = strtolower(end($array));
        return asset($this->getFirstMediaUrlTrait($collectionName, $conversion));
    }

    /**
     * Add Media to api results
     * @return bool
     */
    public function getHasMediaAttribute()
    {
        return $this->hasMedia('default') ? true : false;
    }
}
