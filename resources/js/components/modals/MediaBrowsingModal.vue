<template>
  <div>
    <modal
      v-if="isModalOpen"
      width="1315"
    >
      <div slot="container">
        <browsing-modal-header upload-only />

        <browsing-modal-constraints />

        <browsing-modal-file-list />
      </div>
      <div
        slot="buttons"
        class="w-full flex"
      >
        <browsing-modal-footer />
      </div>
    </modal>
  </div>
</template>

<script>
import Modal from '../common/Modal';
import BrowsingModalHeader from './browsing-modal/BrowsingModalHeader';
import BrowsingModalFooter from './browsing-modal/BrowsingModalFooter';
import BrowsingModalConstraints from './browsing-modal/BrowsingModalConstraints';
import { mapGetters } from 'vuex';
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

  computed: {
    ...mapGetters({
      isModalOpen: 'media-library/isMediaBrowserModalOpen',
    }),
  },

  mounted() {
    console.log('fetchImages')
    this.fetchImages();
  },
};
</script>
