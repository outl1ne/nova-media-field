<template>
  <div>
    <modal
      v-if="isModalOpen"
      width="1315"
    >
      <div slot="container">
        <media-library-header upload-only />

        <media-library-constraints />

        <media-library-file-list v-if="!isUploadMode" />
      </div>
      <div
        slot="buttons"
        class="w-full flex"
      >
        <media-library-footer :is-upload-mode.sync="isUploadMode" />
      </div>
    </modal>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Modal from '../common/Modal';
import MediaLibraryHeader from './media-library/MediaLibraryHeader';
import MediaLibraryFooter from './media-library/MediaLibraryFooter';
import MediaLibraryConstraints from './media-library/MediaLibraryConstraints';
import MediaLibrary from '../mixins/MediaLibrary';
import MediaLibraryFileList from './media-library/MediaLibraryFileList';

export default {
  components: { MediaLibraryFileList, Modal, MediaLibraryHeader, MediaLibraryFooter, MediaLibraryConstraints },

  mixins: [MediaLibrary],

  props: {
    uploadOnly: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      isUploadMode: false
    }
  },

  computed: {
    ...mapGetters({
      isModalOpen: 'media-library/isMediaLibraryModalOpen',
    }),
  },

  mounted() {
    this.fetchImages();
  },
};
</script>
