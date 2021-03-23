<template>
  <div>
    <modal
      v-if="isModalOpen"
      width="1315"
    >
      <div
        slot="container"
        class="w-full h-full"
      >
        <media-library-header
          :upload-only="uploadOnly"
          :field="field"
        />

        <media-library-constraints :field="field" />

        <div class="modal-content">
          <media-library-file-list />

          <media-library-dropzone />
        </div>
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
import MediaLibraryDropzone from './media-library/MediaLibraryDropzone';

export default {
  components: {
    MediaLibraryDropzone,
    MediaLibraryFileList,
    Modal,
    MediaLibraryHeader,
    MediaLibraryFooter,
    MediaLibraryConstraints,
  },

  mixins: [MediaLibrary],

  props: {
    uploadOnly: {
      type: Boolean,
      default: false,
    },
  },

  computed: {
    ...mapGetters({
      isModalOpen: 'media-library/isMediaLibraryModalOpen',
      field: 'media-library/getField',
    }),
  },

  mounted() {
    this.fetchImages();
    document.addEventListener('keydown', e => {
      if (e.code === 'Escape' && this.isModalOpen) this.$store.commit('media-library/closeMediaLibraryModal');
    });
  },
};
</script>
<style lang="scss" scoped>
.modal-content {
  height: 100vh;
  max-height: calc(100vh - 300px);
  position: relative;
}
</style>
