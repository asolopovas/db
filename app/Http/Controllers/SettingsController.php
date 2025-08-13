<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\CrudController;
use Carbon\Carbon;

class SettingsController extends CrudController
{


    protected string $model = Setting::class;
    protected string $resource = 'Settings';
    protected array $messages = [];
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function setSettings(Request $request)
    {
        foreach ($request->all() as $key => $item) {
            $setting = Setting::where('name', $key)->first();
            if ($setting) {
                if (gettype($item) === 'object' && get_class($item) === 'Illuminate\Http\UploadedFile') {
                    Storage::disk('local')->delete($setting->value);
                    $setting->value = $item->storeAs('docs', $item->getClientOriginalName());
                } else {
                    $setting->value = $item;
                }
                $setting->save();
            }
        }
    }

    public function deleteAttachments(Request $request)
    {
        $fileName = $request->get('name');
        Storage::delete("docs/$fileName");
    }

    public function uploadAttachments(Request $request)
    {
        $disk = match ($request->file->getClientOriginalExtension()) {
            'jpg', 'png' => 'public_image',
            default      => 'local'
        };
        $path = $disk === 'local' ? 'docs' : 'img';
        Storage::disk($disk)->putFileAs($path, $request->file, $request->file->getClientOriginalName());
    }

    public function orderAttachments()
    {
        $logo = Storage::disk('public')->files('img');

        $files = array_filter(array_merge(Storage::files('docs'), $logo), function ($file) {
            if (strpos($file, 'pdf') !== false || strpos($file, 'jpg') !== false) {
                return true;
            }
        });

        return array_map(function ($file) {
            return [
                'name'         => $file,
                'size'         => round(Storage::size($file) / 1000, 2),
                'lastModified' => Storage::lastModified($file),
            ];
        }, $files);
    }


    public function globalAttachments()
    {
        return array_values(array_filter(Storage::allFiles('docs'), fn($file) => pathinfo($file, PATHINFO_EXTENSION) === 'pdf'));
    }

    public function getSettings()
    {

        // if folder contains files list them
        $data = [];

        $files         = $this->globalAttachments() ?? [];
        $data['files'] = array_map(fn($file) => [
            'name'     => str_replace("docs/", "", $file),
            'size'     => Storage::size($file),
            'modified' => \DateTime::createFromFormat("U", Storage::lastModified($file))->setTimezone(new \DateTimeZone('Europe/Kiev'))->format('d M Y H:i:s'),
        ], $files);

        $settings = Setting::all();

        foreach ($settings as $setting) {
            if ($setting->type === 'file' && Storage::disk('local')->exists($setting->value)) {
                $data[$setting->name] = [
                    'lastModified' => date('d M Y H:i:s', Storage::disk('local')->lastModified($setting->value)),
                    'size'         => Storage::size($setting->value),
                ];
            } else {
                $data[$setting->name] = $setting->value;
            }
        }

        $data['algolia_app_id'] = env('ALGOLIA_APP_ID');
        $data['algolia_secret'] = env('ALGOLIA_SEARCH_ONLY');

        return $data;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getSetting($key)
    {
        $setting = Setting::where('name', $key)->first();
        if (!$setting) {
            abort(404, 'Page is not found');
        }

        if ($setting->type === 'file') {
            $file = Storage::disk('local')->getDriver()->getAdapter()->applyPathPrefix($setting->value);

            return response()->file($file, ['Content-Type' => 'application/pdf']);
        }

        return $setting->value;
    }

    /**
     * @return array
     */
    protected function updateValidationRules()
    {
        return [
            'name'  => 'sometimes|required|min:3|max:255|unique:settings,name,except,id',
            'value' => 'sometimes|required|min:3|max:255',
        ];
    }

    /**
     * @return array
     */
    protected function storeValidationRules()
    {
        return [
            'name'  => 'required|min:3|max:255|unique:settings,name,except,id',
            'value' => 'required|min:3',
        ];
    }
}
