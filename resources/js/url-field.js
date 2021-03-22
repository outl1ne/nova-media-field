import VueClipboard from 'vue-clipboard2';
import IndexField from './components/nova-url-field/IndexField';
import DetailField from './components/nova-url-field/DetailField';
import FormField from './components/nova-url-field/FormField';

Nova.booting(Vue => {
  Vue.use(VueClipboard);

  Vue.component('IndexUrlField', IndexField);
  Vue.component('DetailUrlField', DetailField);
  Vue.component('FormUrlField', FormField);
});
