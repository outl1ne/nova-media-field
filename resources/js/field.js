import MediaLibraryStore from './store/MediaLibraryStore';

Nova.booting((Vue, router, Store) => {
  Vue.component('media-preview', require('./components/MediaPreview').default);
  Vue.component('edit-image', require('./components/EditImage').default);
  Vue.component('uploaded-file', require('./components/UploadedFile').default);
  Vue.component('od-modal', require('./components/common/Modal').default);
  Vue.component('media-browsing-modal', require('./components/MediaBrowsingModal').default);
  Vue.component('media-modal-constraints', require('./components/MediaModalConstraints').default);
  Vue.component('index-media-field', require('./components/media-field/IndexField').default);
  Vue.component('detail-media-field', require('./components/media-field/DetailField').default);
  Vue.component('form-media-field', require('./components/media-field/FormField').default);
  Vue.component('media-edit-modal', require('./components/MediaEditModal').default);
  Vue.component('thumbnail-video-icon', require('./icons/VideoIcon').default);
  Vue.component('media-resource-table', require('./components/resource-tables/ResourceTable').default);
  Vue.component('media-resource-table-row', require('./components/resource-tables/ResourceTableRow').default);
  Vue.component('media-index-button', require('./components/media-field/IndexButton').default);

  router.addRoutes([
    {
      name: 'media-library',
      path: '/media-library',
      component: require('./views/MediaView').default,
    },
  ]);

  Store.registerModule('media-library', MediaLibraryStore);
});
