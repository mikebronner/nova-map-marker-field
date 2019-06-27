<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import L from "leaflet";
import { LMap, LTileLayer, LMarker, LIcon } from 'vue2-leaflet';
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
        LMap,
        LMarker,
        LTileLayer,
        VGeosearch,
    },

    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data: function () {
        return {
            tileUrl: 'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
            geosearchOptions: {
                provider: new EsriProvider(),
                showMarker: false,
                style: "bar",
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
    },

    computed: {
        mapCenter: function () {
            if (this.value.latitude === undefined) {
                this.setInitialValue();
            }

            return [
                this.value.latitude,
                this.value.longitude,
            ];
        },

        zoom: function () {
            if (this.value.latitude === undefined) {
                this.setInitialValue();
            }

            if (! this.value.latitude) {
                return 4;
            }

            return 18;
        }
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
                latitude: this.field.value.latitude || 0,
                longitude: this.field.value.longitude || 0,
            };
        },
    },
};
</script>

<template>
    <default-field
        :field="field"
        :errors="errors"
        :full-width-content="true"
    >
        <template slot="field">
            <l-map
                class="z-10 map-field w-full form-control form-input-bordered overflow-hidden relative"
                ref="map"
                :center="mapCenter"
                :options="mapOptions"
                :zoom="zoom"
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
            </l-map>
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
