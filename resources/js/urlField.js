Nova.booting((Vue, router, store) => {
  Vue.component('index-url-field', require('./components/urlField/IndexField'));
  Vue.component('detail-url-field', require('./components/urlField/DetailField'));
  Vue.component('form-url-field', require('./components/urlField/FormField'));
});
