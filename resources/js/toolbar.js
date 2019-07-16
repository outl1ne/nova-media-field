Nova.booting((Vue, router) => {
  Vue.component('custom-index-toolbar', require('./components/toolbars/CustomIndexToolbar'));
  Vue.component('media-index-toolbar', require('./components/toolbars/MediaIndexToolbar'));
});
