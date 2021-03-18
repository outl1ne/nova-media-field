import MediaLibraryStore from './store/MediaLibraryStore';
import IndexMediaField from './nova-media-field/IndexField';
import DetailMediaField from './nova-media-field/DetailField';
import FormMediaField from './nova-media-field/FormField';
import MediaView from './views/MediaView';
import MediaBrowsingModal from './components/modals/MediaBrowsingModal';

const modalRootEl = document.createElement('media-browsing-modal');
modalRootEl.id = 'media-browsing-modal';
document.querySelector('#nova').appendChild(modalRootEl);

Nova.booting((Vue, router, Store) => {
  Vue.component('IndexMediaField', IndexMediaField);
  Vue.component('DetailMediaField', DetailMediaField);
  Vue.component('FormMediaField', FormMediaField);
  Vue.component('MediaBrowsingModal', MediaBrowsingModal);

  router.addRoutes([
    {
      name: 'media-library',
      path: '/media-library',
      component: MediaView,
    },
  ]);

  Store.registerModule('media-library', MediaLibraryStore);
});
