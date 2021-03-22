import MediaLibraryStore from './store/MediaLibraryStore';
import IndexMediaField from './components/nova-media-field/IndexField';
import DetailMediaField from './components/nova-media-field/DetailField';
import FormMediaField from './components/nova-media-field/FormField';
import MediaView from './views/MediaView';
import MediaLibraryModal from './components/modals/MediaLibraryModal';

const modalRootEl = document.createElement('media-library-modal');
modalRootEl.id = 'media-library-modal';
document.querySelector('#nova').appendChild(modalRootEl);

Nova.booting((Vue, router, Store) => {
  Vue.component('IndexMediaField', IndexMediaField);
  Vue.component('DetailMediaField', DetailMediaField);
  Vue.component('FormMediaField', FormMediaField);
  Vue.component('MediaLibraryModal', MediaLibraryModal);

  router.addRoutes([
    {
      name: 'media-library',
      path: '/media-library',
      component: MediaView,
    },
  ]);

  Store.registerModule('media-library', MediaLibraryStore);
});
