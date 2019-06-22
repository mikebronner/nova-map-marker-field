Nova.booting((Vue, router, store) => {
    Vue.component('index-nova-map-marker-field', require('./components/IndexField').default);
    Vue.component('detail-nova-map-marker-field', require('./components/DetailField').default);
    Vue.component('form-nova-map-marker-field', require('./components/FormField').default);
});
