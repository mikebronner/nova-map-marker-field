<?php namespace GeneaLabs\NovaMapMarkerField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class MapMarkerAction extends Field
{
    public $component = 'nova-map-marker-field';

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = $request[$requestAttribute];
        }
    }

    /**
     * @inheritdoc
     */
    protected function resolveAttribute($resource, $attribute)
    {
        $attribute = [
            "latitude" => "latitude",
            "longitude" => "longitude",
        ];

        return $attribute;
    }

    public function centerCircle(int $radius = 0, string $color = 'gray', int $border = 0, float $opacity = 0.2)
    {
        if (!is_array($this->attribute)) {
            $this->attribute = [];
        }

        $this->attribute["circle_radius"] = $radius;
        $this->attribute["circle_color"] = $color;
        $this->attribute["circle_border"] = $border;
        $this->attribute["circle_opacity"] = $border;

        return $this->withMeta([__FUNCTION__ => [
            'radius' => $radius,
            'color' => $color,
            'border' => $border,
            'opacity' => $opacity,
        ]]);
    }

    public function defaultLatitude($field)
    {
        if (!is_array($this->attribute)) {
            $this->attribute = [];
        }

        $this->attribute["default_latitude"] = $field;

        return $this->withMeta([__FUNCTION__ => $field]);
    }

    public function defaultLongitude($field)
    {
        if (!is_array($this->attribute)) {
            $this->attribute = [];
        }

        $this->attribute["default_longitude"] = $field;

        return $this->withMeta([__FUNCTION__ => $field]);
    }

    public function defaultZoom($field)
    {
        if (!is_array($this->attribute)) {
            $this->attribute = [];
        }

        $this->attribute["default_zoom"] = $field;

        return $this->withMeta([__FUNCTION__ => $field]);
    }

    public function latitude($field)
    {
        if (!is_array($this->attribute)) {
            $this->attribute = [];
        }

        $this->attribute["latitude"] = $field;

        return $this->withMeta([__FUNCTION__ => $field]);
    }

    public function longitude($field)
    {
        if (!is_array($this->attribute)) {
            $this->attribute = [];
        }

        $this->attribute["longitude"] = $field;

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
            if (!is_array($this->value)) {
                $this->value = [];
            }

            foreach ($attribute as $field) {
                $this->value[$field] = $this->resolveAttribute($resource, $field);
            }

            return;
        }

        parent::resolve($resource, $attribute);
    }

    public function isRequired(NovaRequest $request)
    {
        return with($this->requiredCallback, function ($callback) use ($request) {
            if ($callback === true || (is_callable($callback) && call_user_func($callback, $request))) {
                return true;
            }

            if (is_null($callback) && $request->isCreateOrAttachRequest()) {
                return in_array('required', $this->getCreationRules($request)["latitude"])
                    || in_array('required', $this->getCreationRules($request)["longitude"]);
            }

            if (is_null($callback) && $request->isUpdateOrUpdateAttachedRequest()) {
                return in_array('required', $this->getUpdateRules($request)["latitude"])
                    || in_array('required', $this->getUpdateRules($request)["longitude"]);
            }

            return false;
        });
    }

    /**
     * Set the seperator for the field's dates
     *
     * @param $seperator
     * @return $this
     */
    public function seperator($seperator)
    {
        $this->seperator = $seperator;
        return $this->withMeta(['seperator' => $seperator]);
    }

    /**
     * Parse the attribute name to retrieve the affected model attributes
     *
     * @param $attribute
     * @return array
     */
    protected function parseAttribute($attribute)
    {
        return explode('-', $attribute);
    }

    /**
     * Parse the response to retrieve the raw values
     *
     * @param $attribute
     * @return array
     */
    protected function parseResponse($attribute)
    {
        if ($attribute === null) {
            return [null, null];
        }

        return array_pad(explode(" $this->seperator ", $attribute), 2, null);
    }
}
