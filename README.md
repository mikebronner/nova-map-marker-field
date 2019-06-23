# Map Marker Field for Laravel Nova
[![GitHub (pre-)release](https://img.shields.io/github/release/GeneaLabs/nova-map-marker-field/all.svg)](https://github.com/GeneaLabs/nova-map-marker-field)
[![Packagist](https://img.shields.io/packagist/dt/GeneaLabs/nova-map-marker-field.svg)](https://packagist.org/packages/genealabs/nova-map-marker-field)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/GeneaLabs/nova-map-marker-field/master/LICENSE)

![laravel-nova-map-marker-field-header](https://user-images.githubusercontent.com/1791050/59978378-86beef00-9590-11e9-9a10-dee8f77f0037.png)

## Supporting This Package
This is an MIT-licensed open source project with its ongoing development made possible by the support of the community. If you'd like to support this, and our other packages, please consider [becoming a backer or sponsor on Patreon](https://www.patreon.com/mikebronner).

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

## Usage
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

## Screenshots
### Create / Edit Field
<img width="1010" alt="Screen Shot 2019-06-23 at 8 16 52 AM" src="https://user-images.githubusercontent.com/1791050/59978398-b241d980-9590-11e9-9adc-23e5bd9688e0.png">

### Detail Field
<img width="1007" alt="Screen Shot 2019-06-23 at 8 17 43 AM" src="https://user-images.githubusercontent.com/1791050/59978392-a5bd8100-9590-11e9-9ab7-576e7a935dff.png">

### Index Field


## Commitment to Quality
During package development I try as best as possible to embrace good design and development practices, to help ensure that this package is as good as it can
be. My checklist for package development includes:

-   ✅ Achieve as close to 100% code coverage as possible using unit tests.
-   ✅ Eliminate any issues identified by SensioLabs Insight and Scrutinizer.
-   ✅ Be fully PSR1, PSR2, and PSR4 compliant.
-   ✅ Include comprehensive documentation in README.md.
-   ✅ Provide an up-to-date CHANGELOG.md which adheres to the format outlined
    at <http://keepachangelog.com>.
-   ✅ Have no PHPMD or PHPCS warnings throughout all code.

## Contributing
Please observe and respect all aspects of the included Code of Conduct <https://github.com/GeneaLabs/nova-map-marker-field/blob/master/CODE_OF_CONDUCT.md>.

### Reporting Issues
When reporting issues, please fill out the included template as completely as
possible. Incomplete issues may be ignored or closed if there is not enough
information included to be actionable.

### Submitting Pull Requests
Please review the Contribution Guidelines <https://github.com/GeneaLabs/nova-map-marker-field/blob/master/CONTRIBUTING.md>. Only PRs that meet all criterium will be accepted.

## If you ❤️ open-source software, give the repos you use a ⭐️.
We have included the awesome `symfony/thanks` composer package as a dev dependency. Let your OS package maintainers know you appreciate them by starring the packages you use. Simply run composer thanks after installing this package. (And not to worry, since it's a dev-dependency it won't be installed in your live environment.)
