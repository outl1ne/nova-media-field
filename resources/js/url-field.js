import VueClipboard from 'vue-clipboard2';

Nova.booting(Vue => {
  Vue.use(VueClipboard);

  Vue.component('IndexUrlField', require('./components/url-field/IndexField').default);
  Vue.component('DetailUrlField', require('./components/url-field/DetailField').default);
  Vue.component('FormUrlField', require('./components/url-field/FormField').default);
});
