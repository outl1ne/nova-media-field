import MediaLibraryStore from './store/MediaLibraryStore';

Nova.booting((Vue, router, Store) => {
  Vue.component('MediaPreview', require('./components/MediaPreview').default);
  Vue.component('EditImage', require('./components/EditImage').default);
  Vue.component('UploadedFile', require('./components/UploadedFile').default);
  Vue.component('OdModal', require('./components/common/Modal').default);
  Vue.component('MediaBrowsingModal', require('./components/MediaBrowsingModal').default);
  Vue.component('MediaModalConstraints', require('./components/MediaModalConstraints').default);
  Vue.component('IndexMediaField', require('./components/media-field/IndexField').default);
  Vue.component('DetailMediaField', require('./components/media-field/DetailField').default);
  Vue.component('FormMediaField', require('./components/media-field/FormField').default);
  Vue.component('MediaEditModal', require('./components/MediaEditModal').default);
  Vue.component('ThumbnailVideoIcon', require('./icons/VideoIcon').default);
  Vue.component('MediaResourceTable', require('./components/resource-tables/ResourceTable').default);
  Vue.component('MediaResourceTableRow', require('./components/resource-tables/ResourceTableRow').default);
  Vue.component('MediaIndexButton', require('./components/media-field/IndexButton').default);

  router.addRoutes([
    {
      name: 'media-library',
      path: '/media-library',
      component: require('./views/MediaView').default,
    },
  ]);

  Store.registerModule('media-library', MediaLibraryStore);
});
