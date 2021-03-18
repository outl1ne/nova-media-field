<template>
  <div>
    <modal
      v-if="isModalOpen"
      width="1315"
    >
      <div slot="container">
        <browsing-modal-header upload-only />

        <browsing-modal-constraints />

        <browsing-modal-file-list v-if="!isUploadMode" />
      </div>
      <div
        slot="buttons"
        class="w-full flex"
      >
        <browsing-modal-footer :is-upload-mode.sync="isUploadMode" />
      </div>
    </modal>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Modal from '../common/Modal';
import BrowsingModalHeader from './browsing-modal/BrowsingModalHeader';
import BrowsingModalFooter from './browsing-modal/BrowsingModalFooter';
import BrowsingModalConstraints from './browsing-modal/BrowsingModalConstraints';
import MediaLibrary from '../mixins/MediaLibrary';
import BrowsingModalFileList from './browsing-modal/BrowsingModalFileList';

export default {
  components: { BrowsingModalFileList, Modal, BrowsingModalHeader, BrowsingModalFooter, BrowsingModalConstraints },

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
      isModalOpen: 'media-library/isMediaBrowserModalOpen',
    }),
  },

  mounted() {
    this.fetchImages();
  },
};
</script>
