<?php namespace GeneaLabs\NovaMapMarkerField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class MapMarker extends Field
{
    public $component = 'nova-map-marker-field';

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        $attribute = [
            "latitude" => "latitude",
            "longitude" => "longitude",
        ];

        parent::__construct($name, $attribute, $resolveCallback);
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        foreach ($requestAttribute as $field => $modelField) {
            if (in_array($field, ["latitude", "longitude"])
                && $request->exists($modelField)
            ) {
                $model->{$modelField} = json_decode($request[$modelField], true);
            }
        }
    }

    public function getRules(NovaRequest $request)
    {

        return [
            $this->attribute["latitude"] => is_callable($this->rules)
                ? call_user_func($this->rules, $request)
                : $this->rules,
            $this->attribute["longitude"] => is_callable($this->rules)
                ? call_user_func($this->rules, $request)
                : $this->rules,
        ];
    }

    public function getCreationRules(NovaRequest $request)
    {
        $rules = [
            $this->attribute["latitude"] => is_callable($this->creationRules)
                ? call_user_func($this->creationRules, $request)
                : $this->creationRules,
            $this->attribute["longitude"] => is_callable($this->creationRules)
                ? call_user_func($this->creationRules, $request)
                : $this->creationRules,
        ];

        return array_merge_recursive(
            $this->getRules($request),
            $rules
        );
    }

    public function getUpdateRules(NovaRequest $request)
    {
        $rules = [
            $this->attribute["latitude"] => is_callable($this->updateRules)
                ? call_user_func($this->updateRules, $request)
                : $this->updateRules,
            $this->attribute["longitude"] => is_callable($this->updateRules)
                ? call_user_func($this->updateRules, $request)
                : $this->updateRules,
        ];

        return array_merge_recursive(
            $this->getRules($request),
            $rules
        );
    }

    public function latitude($field)
    {
        if (! is_array($this->attribute)) {
            $this->attribute = [];
        }

        $this->attribute["latitude"] = $field;

        return $this->withMeta([__FUNCTION__ => $field]);
    }

    public function longitude($field)
    {
        if (! is_array($this->attribute)) {
            $this->attribute = [];
        }

        $this->attribute["longitude"] = $field;

        return $this->withMeta([__FUNCTION__ => $field]);
    }

    public function defaultLatitude($field)
    {
        if (! is_array($this->attribute)) {
            $this->attribute = [];
        }

        $this->attribute["default_latitude"] = $field;

        return $this->withMeta([__FUNCTION__ => $field]);
    }

    public function defaultLongitude($field)
    {
        if (! is_array($this->attribute)) {
            $this->attribute = [];
        }

        $this->attribute["default_longitude"] = $field;

        return $this->withMeta([__FUNCTION__ => $field]);
    }

    public function defaultZoom($field)
    {
        if (! is_array($this->attribute)) {
            $this->attribute = [];
        }

        $this->attribute["default_zoom"] = $field;

        return $this->withMeta([__FUNCTION__ => $field]);
    }

    // public function markerIcon(string $url)
    // {
    //     return $this->withMeta([__FUNCTION__ => $url]);
    // }

    // public function markerIconShadow(string $url)
    // {
    //     return $this->withMeta([__FUNCTION__ => $url]);
    // }

    public function searchProvider(string $provider)
    {
        return $this->withMeta([__FUNCTION__ => $provider]);
    }

    public function searchProviderKey(string $key)
    {
        return $this->withMeta([__FUNCTION__ => $key]);
    }

    public function tileProvider(string $url)
    {
        return $this->withMeta([__FUNCTION__ => $url]);
    }

    public function resolve($resource, $attribute = null)
    {
        $attribute = $attribute ?? $this->attribute;

        if (is_array($attribute)) {
            if (! is_array($this->value)) {
                $this->value = [];
            }

            foreach ($attribute as $field) {
                $this->value[$field] = $this->resolveAttribute($resource, $field);
            }

            return;
        }

        parent::resolve($resource, $attribute);
    }
}
