import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((Vue) => {
    Vue.component('index-nova-map-marker-field', IndexField);
    Vue.component('detail-nova-map-marker-field', DetailField);
    Vue.component('form-nova-map-marker-field', FormField);
});
