<script>
    import { FormField, HandlesValidationErrors } from 'laravel-nova';
    import L from "leaflet";
    import { LCircle, LMap, LTileLayer, LMarker, LIcon } from 'vue2-leaflet';
    import { BingProvider, EsriProvider, GoogleProvider, LocationIQProvider, OpenCageProvider, OpenStreetMapProvider } from 'leaflet-geosearch';
    import VGeosearch from 'vue2-leaflet-geosearch';

    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
        iconUrl: require('leaflet/dist/images/marker-icon.png'),
        shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
    });

    export default {
        components: {
            LCircle,
            LMap,
            LMarker,
            LTileLayer,
            VGeosearch,
        },

        mixins: [FormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],

        data: function () {
            return {
                defaultLatitude: this.field.defaultLatitude || 0,
                defaultLongitude: this.field.defaultLongitude || 0,
                defaultZoom: this.field.defaultZoom || 12,
                tileUrl: 'https://{s}.tile.osm.org/{z}/{x}/{y}.png',
                geosearchOptions: {
                    provider: new EsriProvider(),
                    showMarker: false,
                    style: "bar",
                    searchLabel: this.customSearchLabel
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
                this.map = this.$refs.map.mapObject
            })
        },
        created: function () {
            switch (this.field.searchProvider) {
                case "bing":
                    this.geosearchOptions.provider = new BingProvider();
                    break;
                case "google":
                    this.geosearchOptions.provider = new GoogleProvider();
                    break;
                case "locationiq":
                    this.geosearchOptions.provider = new LocationIQProvider();
                    break;
                case "opencage":
                    this.geosearchOptions.provider = new OpenCageProvider();
                    break;
                case "openstreetmap":
                    this.geosearchOptions.provider = new OpenStreetMapProvider();
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

            firstLocationError: function () {
                if (this.hasLocationError) {
                    return (this.errors.first(this.latitudeFieldName)
                        || this.errors.first(this.longitudeFieldName));
                }
            },

            hasLocationError: function () {
                return (this.errors.has(this.latitudeFieldName)
                    || this.errors.has(this.longitudeFieldName));
            },

            latitudeFieldName: function () {
                return this.field.latitude || "latitude";
            },

            longitudeFieldName: function () {
                return this.field.longitude || "longitude";
            },

            customSearchLabel: function() {
                return this.field.customSearchLabel || "Enter address";
            },

            listenToEventName: function () {
                return this.field.listenToEventName || "newcenter"
            },

            mapErrorClasses() {
                return this.hasLocationError
                    ? this.errorClass
                    : '';
            },

            showErrors: function () {
                console.log(this.errors);
            },

            mapCenter: function () {
                if (this.value.latitude === undefined) {
                    this.setInitialValue();
                }

                return [
                    this.value.latitude || this.defaultLatitude,
                    this.value.longitude || this.defaultLongitude,
                ];
            },
        },

        methods: {
            fill: function (formData) {
                formData.append((this.field.latitude || "latitude"), this.value.latitude);
                formData.append((this.field.longitude || "longitude"), this.value.longitude);
            },

            handleChange: function (value) {
                this.value.latitude = value.latitude;
                this.value.longitude = value.longitude;
            },

            mapMoved: function (event) {
                let coordinates = event.target.getCenter();

                this.value.latitude = coordinates.lat;
                this.value.longitude = coordinates.lng;
            },

            setInitialValue: function () {
                this.value = {
                    latitude: this.field.value[this.field.latitude || "latitude"] || 0,
                    longitude: this.field.value[this.field.longitude || "longitude"] || 0,
                };
            },
            mapNewCenter: function (event) {
                var center = [event.lat,event.long]
                this.value.latitude = event.lat;
                this.value.longitude = event.long;
                this.map.panTo(center,{animate:true})
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
                </l-map>
            </div>
            <p v-if="hasLocationError" class="my-2 text-danger">
                {{ firstLocationError }}
            </p>
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
