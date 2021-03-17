import VueClipboard from 'vue-clipboard2';

Nova.booting(Vue => {
  Vue.use(VueClipboard);

  Vue.component('index-url-field', require('./components/url-field/IndexField').default);
  Vue.component('detail-url-field', require('./components/url-field/DetailField').default);
  Vue.component('form-url-field', require('./components/url-field/FormField').default);
});
