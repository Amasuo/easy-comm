<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Http\Requests\LanguageRequest;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->class = Language::class;
        $this->translationName = 'language';
    }

    public function store(LanguageRequest $request)
    {
        $input = $request->validated();
        $item = new $this->class();
        $item->fill($input);
        $item->save();

        return $this->success(__('app.' . $this->translationName . '.created'), $item);
    }

    public function update(LanguageRequest $request)
    {
        $this->validateId();
        $item = $this->class::findOrFail($this->modelId);
        $input = $request->validated();
        if (array_key_exists('name', $input)) {
            if (GeneralHelper::valueTakenForClassAttribute($this->class, 'name', $input['name'], $this->modelId)) {
                return $this->failure(__('app.' . $this->translationName . '.name-taken'));
            }
        }
        if (array_key_exists('short_form', $input)) {
            if (GeneralHelper::valueTakenForClassAttribute($this->class, 'short_form', $input['short_form'], $this->modelId)) {
                return $this->failure(__('app.' . $this->translationName . '.short_form-taken'));
            }
        }
        $item->fill($input);
        $item->save();
        return $this->success(__('app.' . $this->translationName . '.updated'), $item);
    }
}
