---
layout: default
title: Documentation
nav_order: 2
---
#Documentation
## Installation
1. Install the package:
  ```sh
  composer require genealabs/nova-map-marker-field
  ```

2. Publish the marker icon assets (this is not necessary if you are specifying
  your own):
  ```sh
  php artisan vendor:publish --provider="GeneaLabs\NovaMapMarkerField\Providers\Service"
  ```

## Implementation
To create the map marker field, all that is necessary is the form label, and the
remaining options will have defaults applied:
```php
MapMarker::make("Location"),
```

### Model Fields
By default the field will look for `latitude` and `longitude` fields on the
model. However, if your model uses different names, you may customize them with
the `->latitude('lat')` and `->longitude('long')` methods:
```php
MapMarker::make("Location")
    ->latitude('lat')
    ->longitude('long'),
```

### Search Provider
The underlying search capabilities are provided by
[leaflet-geosearch](https://github.com/smeijer/leaflet-geosearch). Please refer
to their documentation for provider configuration. By default we use the
ESRI search provider.
```php
MapMarker::make("Location")
    ->searchProvider('google')
    ->searchProviderKey('xxxxxxxxxxxxxxxxxxxxxxxxxxx'),
```

### Tile Layer
You are free to use any tile provider that is compatible with
[Leaflet](https://leafletjs.com/reference-1.5.0.html#tilelayer). Please refer to
their documentation on tile layer URLs. By default we use tiles provided by
OpenStreetMap:
```php
MapMarker::make("Location")
    ->tileProvider('http://{s}.somedomain.com/{foo}/{z}/{x}/{y}.png'),
```

## Usage
When creating or editing you can search for an address or city to get the map to the general area you wish to get coordinates for. Then you can precisely position the marker by dragging the map -- the marker will always stay positioned in the middle, while you move the map under it.

When viewing the map in on the detail page, the map and marker are not interactive, and there is no search functionality. However, the user is free to zoom in and out.

## Screenshots
### Create / Edit Field
<img width="1010" alt="Screen Shot 2019-06-23 at 8 16 52 AM" src="https://user-images.githubusercontent.com/1791050/59978398-b241d980-9590-11e9-9adc-23e5bd9688e0.png">

### Detail Field
<img width="1007" alt="Screen Shot 2019-06-23 at 8 17 43 AM" src="https://user-images.githubusercontent.com/1791050/59978392-a5bd8100-9590-11e9-9ab7-576e7a935dff.png">

### Index Field
<img width="1098" alt="Screen Shot 2019-06-23 at 8 32 01 AM" src="https://user-images.githubusercontent.com/1791050/59978478-778c7100-9591-11e9-9610-3c9a59ca12c4.png">
