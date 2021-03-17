import MediaLibraryStore from './store/MediaLibraryStore';
import IndexMediaField from './nova-media-field/IndexField';
import DetailMediaField from './nova-media-field/DetailField';
import FormMediaField from './nova-media-field/FormField';
import MediaView from './views/MediaView';

Nova.booting((Vue, router, Store) => {
  Vue.component('IndexMediaField', IndexMediaField);
  Vue.component('DetailMediaField', DetailMediaField);
  Vue.component('FormMediaField', FormMediaField);

  router.addRoutes([
    {
      name: 'media-library',
      path: '/media-library',
      component: MediaView,
    },
  ]);

  Store.registerModule('media-library', MediaLibraryStore);
});
