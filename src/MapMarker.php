<?php namespace GeneaLabs\NovaMapMarkerField;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class MapMarker extends Field
{
    public $component = 'nova-map-marker-field';

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $result = json_decode($request->{$requestAttribute}, false);

            $model->{$result->latitude_field} = $this->isNullValue($result->latitude)
                ? null
                : $result->latitude;
            $model->{$result->longitude_field} = $this->isNullValue($result->longitude)
                ? null
                : $result->longitude;
        }
    }

    public function getRules(NovaRequest $request)
    {
        return [
            $this->attribute => is_callable($this->rules)
                ? call_user_func($this->rules, $request)
                : $this->rules,
        ];
    }

    public function getCreationRules(NovaRequest $request)
    {
        $rules = [
            $this->attribute => is_callable($this->creationRules)
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
            $this->attribute => is_callable($this->updateRules)
                ? call_user_func($this->updateRules, $request)
                : $this->updateRules,
        ];

        return array_merge_recursive(
            $this->getRules($request),
            $rules
        );
    }

    public function centerCircle(int $radius = 0, string $color = 'gray', int $border = 0, float $opacity = 0.2)
    {
        return $this->withMeta([__FUNCTION__ => [
            'radius' => $radius,
            'color' => $color,
            'border' => $border,
            'opacity' => $opacity,
        ]]);
    }

    public function polygons(array $polygons)
    {
        $polygons = array_map(function ($polygon) {
            return Collection::make($polygon)->mapWithKeys(function ($value, $key) {
                return [Str::camel($key) => $value];
            })->toArray();
        }, $polygons);

        return $this->withMeta([__FUNCTION__ => $polygons]);
    }

    public function defaultLatitude($field)
    {
        return $this->withMeta([__FUNCTION__ => $field]);
    }

    public function defaultLongitude($field)
    {
        return $this->withMeta([__FUNCTION__ => $field]);
    }

    public function defaultZoom($field)
    {
        return $this->withMeta([__FUNCTION__ => $field]);
    }

    public function latitude($field)
    {
        return $this->withMeta([__FUNCTION__ => $field]);
    }

    public function longitude($field)
    {
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

    public function tileSubdomains(array $arr = ['a','b','c'])
    {
        return $this->withMeta(['subdomains' => $arr ]);
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
        $latitudeField = $this->meta["latitude"] ?? "latitude";
        $longitudeField = $this->meta["longitude"] ?? "longitude";
        $this->value = json_encode([
            "latitude_field" => $latitudeField,
            "longitude_field" => $longitudeField,
            "latitude" => (float) $resource->{$latitudeField},
            "longitude" => (float) $resource->{$longitudeField},
        ]);
    }

    public function isRequired(NovaRequest $request)
    {
        return with($this->requiredCallback, function ($callback) use ($request) {
            if ($callback === true || (is_callable($callback) && call_user_func($callback, $request))) {
                return true;
            }

            if (is_null($callback) && $request->isCreateOrAttachRequest()) {
                return in_array('required', $this->getCreationRules($request));
            }

            if (is_null($callback) && $request->isUpdateOrUpdateAttachedRequest()) {
                return in_array('required', $this->getUpdateRules($request));
            }

            return false;
        });
    }

    public function searchLabel(string $label)
    {
        return $this->withMeta([__FUNCTION__ => $label]);
    }

    public function listenToEventName(string $eventName)
    {
        return $this->withMeta([__FUNCTION__ => $eventName]);
    }

    public function iconRetinaUrl(string $url)
    {
        return $this->withMeta([__FUNCTION__ => $url]);
    }

    public function iconUrl(string $url)
    {
        return $this->withMeta([__FUNCTION__ => $url]);
    }

    public function shadowUrl(string $url)
    {
        return $this->withMeta([__FUNCTION__ => $url]);
    }
}
