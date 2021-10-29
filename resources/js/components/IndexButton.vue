<template>
  <div>
    <div ref="modals">
      <media-browsing-modal
        :field="field"
        :multipleSelect="multipleSelect"
        :files.sync="files"
        :upload-only="true"
        :isModalOpen.sync="isModalOpen"
        :chosenCollection.sync="chosenCollection"
        :activeFile.sync="activeFile"
        :showUploadArea.sync="showUploadArea"
        @updateFiles="updateFiles"
        @updateMedia="updateMedia"
        :loadingMediaFiles.sync="loadingMediaFiles"
        :selectedFiles.sync="selectedFiles"
      />
    </div>

    <button type="button" v-on:click="openMediaBrowsingModal" class="btn btn-default btn-primary whitespace-no-wrap">
      {{ __('Upload media') }}
    </button>
  </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import debounce from './../debounce';

function isString(value) {
  return typeof value === 'string' || value instanceof String;
}

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ['field', 'onUploadFinished'],

  mounted() {
    window.addEventListener('open-media-library', this.openMediaBrowsingModal);
  },

  beforeDestroy() {
    window.removeEventListener('open-media-library', this.openMediaBrowsingModal);
  },

  data() {
    return {
      files: [],
      isModalOpen: false,
      activeFile: void 0,
      selectedFiles: [],
      chosenCollection: null,
      loadingMediaFiles: false,
      showUploadArea: true,
    };
  },

  watch: {
    isModalOpen: function (value) {
      if (!value) {
        this.files = [];
      }
    },
  },

  computed: {
    multipleSelect() {
      return this.field.multiple;
    },
  },

  methods: {
    updateFiles(file) {
      this.loadingMediaFiles = false;
      this.files.splice(this.files.map(file => file.uploading).indexOf(true), 1);
      this.files.push(file);
      this.updateMedia();
    },

    updateMedia: debounce(function () {
      this.onUploadFinished();
      this.isModalOpen = false;
      this.$toasted.show('Files have been successfully uploaded!', { type: 'success' });
    }, 500),

    openMediaBrowsingModal() {
      this.isModalOpen = true;
    },
  },
};
</script>
