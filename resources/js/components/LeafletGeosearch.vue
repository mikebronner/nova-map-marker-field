<template>
  <div>

  </div>
</template>

<script>
// Credit: fega https://www.npmjs.com/package/vue2-leaflet-geosearch
import { GeoSearchControl } from 'leaflet-geosearch';

export default {
  props: {
    options: {
      required: true,
    },
  },
  name: 'v-geosearch',
  mounted() {
    this.add();
  },
  beforeDestroy() {
    this.remove();
  },
  methods: {
    deferredMountedTo(parent) {
      const searchControl = new GeoSearchControl(this.options);
      parent.addControl(searchControl);
      searchControl.getContainer().onclick = e => { e.stopPropagation(); };
    },
    remove() {
      if (this.markerCluster) {
        this.$parent.removeLayer(this.markerCluster);
      }
    },
    add() {
      if (this.$parent._isMounted) {
        this.deferredMountedTo(this.$parent.mapObject);
      }
    },
  },
};
</script>
