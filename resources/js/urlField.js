import VueClipboard from 'vue-clipboard2';

Nova.booting((Vue, router, store) => {
  Vue.use(VueClipboard);

  Vue.component('index-url-field', require('./components/urlField/IndexField').default);
  Vue.component('detail-url-field', require('./components/urlField/DetailField').default);
  Vue.component('form-url-field', require('./components/urlField/FormField').default);
});
