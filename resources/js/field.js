import MediaLibraryStore from './store/MediaLibraryStore';
import MediaPreview from './components/MediaPreview';
import EditImage from './components/EditImage';
import UploadedFile from './components/UploadedFile';
import OdModal from './components/common/Modal';
import MediaBrowsingModal from './components/MediaBrowsingModal';
import MediaModalConstraints from './components/MediaModalConstraints';
import IndexMediaField from './components/media-field/IndexField';
import DetailMediaField from './components/media-field/DetailField';
import FormMediaField from './components/media-field/FormField';
import MediaEditModal from './components/MediaEditModal';
import ThumbnailVideoIcon from './icons/VideoIcon';
import MediaResourceTable from './components/resource-tables/ResourceTable';
import MediaResourceTableRow from './components/resource-tables/ResourceTableRow';
import MediaIndexButton from './components/media-field/IndexButton';
import MediaView from './views/MediaView';

Nova.booting((Vue, router, Store) => {
  Vue.component('MediaPreview', MediaPreview);
  Vue.component('EditImage', EditImage);
  Vue.component('UploadedFile', UploadedFile);
  Vue.component('OdModal', OdModal);
  Vue.component('MediaBrowsingModal', MediaBrowsingModal);
  Vue.component('MediaModalConstraints', MediaModalConstraints);
  Vue.component('IndexMediaField', IndexMediaField);
  Vue.component('DetailMediaField', DetailMediaField);
  Vue.component('FormMediaField', FormMediaField);
  Vue.component('MediaEditModal', MediaEditModal);
  Vue.component('ThumbnailVideoIcon', ThumbnailVideoIcon);
  Vue.component('MediaResourceTable', MediaResourceTable);
  Vue.component('MediaResourceTableRow', MediaResourceTableRow);
  Vue.component('MediaIndexButton', MediaIndexButton);

  router.addRoutes([
    {
      name: 'media-library',
      path: '/media-library',
      component: MediaView,
    },
  ]);

  Store.registerModule('media-library', MediaLibraryStore);
});
