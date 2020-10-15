<?php

namespace App\Vendors\Spotify;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;
use Illuminate\Validation\ValidationException;

/**
 * Class SearchParam
 * @package App\Vendors\Spotify
 * @property string query
 * @property string objectType
 * @property string requestMethod
 * @property string uri
 * @property array options
 */
class SearchParam extends Fluent
{
    protected $rules = [
        'query' => 'required|string',
        'objectType' => 'required|string',
        'requestMethod' => 'required|string|in:GET,POST',
        'uri' => 'required|string',
        'options' => 'nullable|sometimes|array'
    ];

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->validate();
    }

    public function getQueryParams(): array
    {
        return array_merge(['q' => $this->query, 'type' => $this->objectType], $this->options);
    }

    private function validate()
    {
        $validator = Validator::make($this->attributes, $this->rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
