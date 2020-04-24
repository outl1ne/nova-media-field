<template>
  <default-field :field="field" :errors="errors" fullWidthContent>
    <template slot="field">
      <div :class="`${isCompact && 'compact-form'} ${isMultiple && 'multi-file-upload'}`">
        <div ref="modals">
          <media-browsing-modal
            :field="field"
            :multipleSelect="multipleSelect"
            :files.sync="files"
            :isModalOpen.sync="isModalOpen"
            :chosenCollection.sync="chosenCollection"
            :activeFile.sync="activeFile"
            :updateMedia="updateMedia"
            :showUploadArea.sync="showUploadArea"
            :loadingMediaFiles.sync="loadingMediaFiles"
            :selectedFiles.sync="selectedFiles"
            @loadImages="fetchFiles"
            @search="searchValue => fetchFiles(searchValue)"
          />
        </div>

        <media-preview
          v-if="selectedFiles.length > 0"
          :ordering="field.ordering"
          :changeOrder="handleChange"
          :files="selectedFiles"
          :multiple="multipleSelect"
          :field="field"
        />

        <p :class="`${!isCompact && 'py-6'}`" :style="`padding-top: ${!isCompact && 9}px;`" v-else>
          {{ __('No media selected') }}
        </p>

        <div class="field-buttons ml-auto">
          <button
            type="button"
            v-if="selectedFiles.length"
            v-on:click="clearSelectedFiles"
            class="btn btn-default btn-danger inline-flex items-center relative ml-auto mr-3"
          >
            <span>
              {{ __('Clear') }}
            </span>
          </button>
          <button
            type="button"
            v-on:click="openMediaBrowsingModal"
            class="btn btn-default btn-primary inline-flex items-center relative ml-auto mr-3"
          >
            <span>
              {{ __('Media library') }}
            </span>
          </button>
        </div>
      </div>
    </template>
  </default-field>
</template>

<style lang="scss">
.compact-form {
  display: flex;
  align-items: center;

  .preview-container {
    margin-bottom: 0;
    width: auto;
    height: auto;
    max-width: 400px;

    .thumbnail-container {
      height: 100%;
    }

    &.multiple-preview {
      max-height: 130px;
    }
  }

  &:not(.multi-file-upload) {
    .preview-container {
      border: 0;
      padding: 0;
      max-width: 100%;
    }
  }

  .field-buttons {
    min-width: 300px;
    margin-left: 20px;
  }
}
</style>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import debounce from '../debounce';

function isString(value) {
  return typeof value === 'string' || value instanceof String;
}

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],

  data() {
    return {
      files: [],
      isModalOpen: false,
      activeFile: void 0,
      selectedFiles: [],
      chosenCollection: null,
      loadingMediaFiles: true,
      showUploadArea: false,
    };
  },

  watch: {
    selectedFiles: function(value) {
      if (!value || !Array.isArray(value) || !window.mediaLibrary.loadedFiles) return;
      window.mediaLibrary.loadedFiles = [
        ...window.mediaLibrary.loadedFiles,
        ...value.filter(item => !window.mediaLibrary.loadedFiles.find(file => file.data.id === item.data.id)),
      ];
    },
    isModalOpen: function(value) {
      if (!value && window.mediaLibrary.loadedFiles && window.mediaLibrary.loadedFiles.length) {
        window.mediaLibrary.files = [...window.mediaLibrary.loadedFiles];
        window.mediaLibrary.loadedFiles = null;
        this.updateMedia();
      }
    },
  },

  computed: {
    multipleSelect() {
      return this.field.multiple;
    },
    isCompact() {
      return Array.isArray(this.field.detailThumbnailSize) && this.field.detailThumbnailSize[0];
    },
    isMultiple() {
      return this.field.multiple || false;
    },
  },

  mounted() {
    if (this.field.value && this.field.value !== '') {
      axios.get('/api/media/find', { params: { ids: this.getInitialValue() } }).then(response => {
        this.selectedFiles = response.data.map(file => ({
          data: file,
          processed: true,
          uploading: false,
          uploadProgress: 0,
        }));
        if (window.mediaLibrary.loaded) {
          window.mediaLibrary.files = [
            ...window.mediaLibrary.files,
            ...this.selectedFiles.filter(
              item => !window.mediaLibrary.files.find(file => file.data.id === item.data.id)
            ),
          ];
          this.updateMedia();
        }
      });
    }

    if (!window.mediaLibrary) {
      this.loadingMediaFiles = true;

      window.mediaLibrary = {
        files: [],
        loaded: false,
        onload: [],
        currentPage: 0,
      };

      this.fetchFiles();
      window.mediaLibrary.loaded = true;
    } else if (window.mediaLibrary.loaded) {
      this.files = [...window.mediaLibrary.files];
    }

    this.updateFiles();
    this.updateMedia();

    window.mediaLibrary.onload.push(this.updateFiles);
  },

  methods: {
    updateFiles() {
      this.loadingMediaFiles = false;
      this.files = [...window.mediaLibrary.files];
    },

    updateMedia() {
      return debounce(() => {
        if (window.mediaLibrary.onload.length) {
          for (const cb of window.mediaLibrary.onload) {
            if (this.updateFiles !== cb) cb();
          }
        }
      }, 200)();
    },

    openMediaBrowsingModal() {
      this.isModalOpen = true;
      this.showUploadArea = false;
    },

    getInitialValue() {
      if (Array.isArray(this.field.value)) return this.field.value;
      if (isString(this.field.value)) return this.field.value.split(',');
      return [this.field.value];
    },

    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      if (!this.field.value) {
        this.value = '';
      } else {
        const ids = this.getInitialValue();
        const validatedIds = ids.filter(id => !isNaN(id));
        this.value = validatedIds.join(',') || '';
      }
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, this.selectedFiles.map(file => file.data.id) || '');
    },

    /**
     * Update the field's internal value.
     */
    handleChange(value) {
      this.selectedFiles = value;
    },

    clearSelectedFiles() {
      this.activeFile = void 0;
      this.selectedFiles = [];
    },

    async fetchFiles(searchValue = null) {
      return debounce(async () => {
        const changedFromSearchToOverall = window.mediaLibrary.previousSearchValue !== searchValue;
        window.mediaLibrary.previousSearchValue = searchValue;

        if (!changedFromSearchToOverall && window.mediaLibrary.currentPage + 1 > window.mediaLibrary.totalPages) return;
        if (changedFromSearchToOverall) window.mediaLibrary.currentPage = 0;

        window.mediaLibrary.fetching = true;
        const response = await axios.get('/api/media', {
          params: {
            search: searchValue,
            page: window.mediaLibrary.currentPage + 1,
          },
        });

        const { data } = response.data;
        let newFiles = data.map(file => {
          return {
            uploading: false,
            processed: true,
            data: file,
          };
        });

        if (changedFromSearchToOverall) {
          window.mediaLibrary.currentPage++;
        } else {
          window.mediaLibrary.currentPage++;
        }

        if (searchValue) {
          if (!window.mediaLibrary.loadedFiles) {
            window.mediaLibrary.loadedFiles = [...window.mediaLibrary.files];
          }

          window.mediaLibrary.files = [...newFiles];
        } else {
          if (window.mediaLibrary.loadedFiles && window.mediaLibrary.loadedFiles.length) {
            window.mediaLibrary.files = [...window.mediaLibrary.loadedFiles];
            window.mediaLibrary.loadedFiles = null;
          }

          window.mediaLibrary.files = [
            ...window.mediaLibrary.files,
            ...newFiles.filter(item => !window.mediaLibrary.files.find(file => file.data.id === item.data.id)),
          ];
        }

        window.mediaLibrary.totalPages = Math.ceil(response.data.total / response.data.per_page);
        window.mediaLibrary.fetching = false;

        this.updateFiles();
        this.updateMedia();
      }, 200)();
    },
  },
};
</script>
