<template>
  <div>
    <div ref="modals">
      <media-browsing-modal
        :field="field"
        :multiple-select="multipleSelect"
        :files.sync="files"
        :upload-only="true"
        :is-modal-open.sync="isModalOpen"
        :chosen-collection.sync="chosenCollection"
        :active-file.sync="activeFile"
        :show-upload-area.sync="showUploadArea"
        :loading-media-files.sync="loadingMediaFiles"
        :selected-files.sync="selectedFiles"
        @updateFiles="updateFiles"
      />
    </div>

    <button
      type="button"
      class="btn btn-default btn-primary whitespace-no-wrap"
      @click="openMediaBrowsingModal"
    >
      {{ __('Upload media') }}
    </button>
  </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import debounce from '../../debounce';

function isString(value) {
  return typeof value === 'string' || value instanceof String;
}

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ['field', 'onUploadFinished'],

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

  computed: {
    multipleSelect() {
      return this.field.multiple;
    },
  },

  watch: {
    isModalOpen: function (value) {
      if (!value) {
        this.files = [];
      }
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
      this.$toasted.show('Files have been successfully uploaded!', { type: 'success' });
    }, 1000),

    openMediaBrowsingModal() {
      this.isModalOpen = true;
    },
  },
};
</script>
