<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $setting_key
 * @property string $setting_value
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class SystemSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'setting_key',
        'setting_value',
        'description',
    ];

    /**
     * Get a system setting value by key
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('setting_key', $key)->first();
        return $setting ? $setting->setting_value : $default;
    }

    /**
     * Set a system setting value
     */
    public static function setValue(string $key, $value, string $description = null): void
    {
        static::updateOrCreate(
            ['setting_key' => $key],
            [
                'setting_value' => $value,
                'description' => $description,
            ]
        );
    }
}
