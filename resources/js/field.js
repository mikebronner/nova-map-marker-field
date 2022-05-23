Nova.booting((Vue) => {
    Vue.component('leaflet-geosearch', require('./components/LeafletGeosearch.vue').default);
    Vue.component('index-nova-map-marker-field', require('./components/IndexField.vue').default);
    Vue.component('detail-nova-map-marker-field', require('./components/DetailField.vue').default);
    Vue.component('form-nova-map-marker-field', require('./components/FormField.vue').default);
});
