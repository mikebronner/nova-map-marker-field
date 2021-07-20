<script>
    import { FormField, HandlesValidationErrors } from 'laravel-nova';
    import L from "leaflet";
    import { LCircle, LPolygon, LMap, LTileLayer, LMarker, LIcon } from 'vue2-leaflet';
    import { BingProvider, EsriProvider, GoogleProvider, LocationIQProvider, OpenCageProvider, OpenStreetMapProvider } from 'leaflet-geosearch';
    import VGeosearch from 'vue2-leaflet-geosearch';

    export default {
        components: {
            LCircle,
            LPolygon,
            LMap,
            LMarker,
            LTileLayer,
            VGeosearch,
        },

        mixins: [FormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],

        data: function () {
            return {
                iconRetina: this.field.iconRetinaUrl
                    || '/vendor/leaflet/dist/images/marker-icon-2x.png',
                icon: this.field.iconUrl
                    || '/vendor/leaflet/dist/images/marker-icon.png',
                shadow: this.field.shadowUrl
                    || '/vendor/leaflet/dist/images/marker-shadow.png',
                defaultLatitude: this.field.defaultLatitude
                    || 0,
                defaultLongitude: this.field.defaultLongitude
                    || 0,
                defaultZoom: this.field.defaultZoom
                    || 12,
                tileUrl: 'https://{s}.tile.osm.org/{z}/{x}/{y}.png',
                geosearchOptions: {
                    provider: new EsriProvider(),
                    showMarker: false,
                    style: "bar",
                    searchLabel: this.field.searchLabel
                        || "Enter address",
                },
                mapOptions: {
                    doubleClickZoom: 'center',
                    scrollWheelZoom: 'center',
                    touchZoom: 'center',
                },
                markerOptions: {
                    interactive: false,
                },
            };
        },

        mounted: function () {
            this.$nextTick(() => {
                this.map = this.$refs.map.mapObject;
                this.setInitialValue();
            });
        },

        created: function () {
            delete L.Icon.Default.prototype._getIconUrl;

            L.Icon.Default.mergeOptions({
                iconRetinaUrl: this.iconRetina,
                iconUrl: this.icon,
                shadowUrl: this.shadow,
            });

            const providerOptions = {};

            if (typeof this.field.searchProviderKey !== 'undefined') {
                providerOptions.params.key = this.field.searchProviderKey;
            }

            switch (this.field.searchProvider) {
                case "bing":
                    this.geosearchOptions.provider = new BingProvider(providerOptions);
                    break;
                case "google":
                    this.geosearchOptions.provider = new GoogleProvider(providerOptions);
                    break;
                case "locationiq":
                    this.geosearchOptions.provider = new LocationIQProvider(providerOptions);
                    break;
                case "opencage":
                    this.geosearchOptions.provider = new OpenCageProvider(providerOptions);
                    break;
                case "openstreetmap":
                    this.geosearchOptions.provider = new OpenStreetMapProvider(providerOptions);
                    break;
            }

            if (this.field.tileProvider !== undefined) {
                this.tileUrl = this.field.tileProvider;
            }

            Nova.$on(this.listenToEventName, this.mapNewCenter)
        },

        computed: {
            circleColor: function () {
                return ((this.field.centerCircle || {}).color || 'gray');
            },

            circleHasRadius: function () {
                return this.circleRadius > 0;
            },

            circleHasStroke: function () {
                return this.circleStroke > 0;
            },

            circleOpacity: function () {
                return ((this.field.centerCircle || {}).opacity || 0.2);
            },

            circleRadius: function () {
                return ((this.field.centerCircle || {}).radius || 0);
            },

            circleStroke: function () {
                return ((this.field.centerCircle || {}).border || 0);
            },

            polygons: function () {
                return this.field.polygons || [];
            },

            hasLocationError: function () {
                return this.errors.has(this.field.attribute);
            },

            listenToEventName: function () {
                return this.field.listenToEventName
                    || "recenterMapOn"
            },

            mapErrorClasses() {
                return this.hasLocationError
                    ? this.errorClass
                    : '';
            },

            showErrors: function () {
                // console.error(this.errors);
            },

            mapCenter: function () {
                if (this.value.length === 0) {
                    this.setInitialValue();
                }

                let value = JSON.parse(this.value);

                return [
                    (value.latitude || this.field.defaultLatitude || 0),
                    (value.longitude || this.field.defaultLongitude || 0),
                ];
            },
        },

        methods: {
            fill: function (formData) {
                formData.append(this.field.attribute, this.value || '');
            },

            handleChange: function (value) {
                this.setValue(value.latitude, value.longitude);
            },

            mapMoved: function (event) {
                let coordinates = event.target.getCenter();

                this.setValue(coordinates.lat, coordinates.lng);
            },

            setInitialValue: function () {
                let value = JSON.parse(this.value || this.field.value);

                this.setValue(value.latitude, value.longitude);
            },

            setValue: function (latitude, longitude) {
                this.value = '{"latitude_field":'
                    + '"' + (this.field.latitude || 'latitude') + '"'
                    + ',"longitude_field":'
                    + '"' + (this.field.longitude || 'longitude') + '"'
                    + ',"latitude":'
                    + (latitude || 0)
                    + ',"longitude":'
                    + (longitude || 0)
                    + '}';
            },

            mapNewCenter: function (event) {
                var center = [event.lat, event.long];
                this.setValue(event.lat, event.long);
                this.map.panTo(center, {animate:true});
            },
        },
    };
</script>

<template>
    <default-field
        :errors="errors"
        :field="field"
        :full-width-content="true"
    >
        <template slot="field">
            <div class="map-field z-10 p-0 w-full form-control form-input-bordered overflow-hidden relative"
                :class="mapErrorClasses"
            >
                <l-map
                    :id="field.name"
                    ref="map"
                    :center="mapCenter"
                    :options="mapOptions"
                    :zoom="defaultZoom"
                    @move="mapMoved"
                >
                    <l-tile-layer
                        :url="tileUrl"
                        :subdomains="field.subdomains"
                    ></l-tile-layer>
                    <l-marker
                        :options="markerOptions"
                        :lat-lng="mapCenter"
                    ></l-marker>
                    <v-geosearch
                        :options="geosearchOptions"
                    ></v-geosearch>
                    <l-circle
                        v-if="circleHasRadius"
                        :lat-lng="mapCenter"
                        :radius="circleRadius"
                        :color="circleColor"
                        :fillColor="circleColor"
                        :weight="circleStroke"
                        :fillOpacity="circleOpacity"
                    />
                    <l-polygon
                        v-for="(polygon, index) in polygons"
                        :key="index"
                        v-bind="polygon"
                    />
                </l-map>
            </div>
        </template>
    </default-field>
</template>

<style lang="scss" scoped>
    @import "~leaflet/dist/leaflet.css";
    @import "~leaflet-geosearch/assets/css/leaflet.css";

    .leaflet-pane {
        position: relative;
    }

    .map-field {
        height: 350px;
    }

    .leaflet-pane .leaflet-shadow-pane {
        display: none;
    }
</style>
