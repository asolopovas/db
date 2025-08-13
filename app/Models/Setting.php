<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = [
        'name',
        'value',
        'type',
    ];

    static public function getSetting($name)
    {
        $setting = static::where('name', $name)->first(); // Fixed query
        if (!$setting) {
            abort(404, 'Page is not found');
        }

        if ($setting->type === 'file') {
            $file = Storage::disk('local')
                ->getDriver()
                ->getAdapter()
                ->applyPathPrefix($setting->value);

            return utf8_encode($file);
        }

        return $setting->value;
    }
}
