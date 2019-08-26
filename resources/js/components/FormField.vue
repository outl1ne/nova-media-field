<template>
    <default-field :field="field" :errors="errors" fullWidthContent>

        <template slot="field">

            <div ref="modals">
              <media-browsing-modal :field="field"
                                      :multipleSelect="multipleSelect"
                                      :files.sync="files"
                                      :isModalOpen.sync="isModalOpen"
                                      :chosenCollection.sync="chosenCollection"
                                      :activeFile.sync="activeFile"
                                      :updateMedia="updateMedia"
                                      :showUploadArea.sync="showUploadArea"
                                      :loadingMediaFiles.sync="loadingMediaFiles"
                                      :selectedFiles.sync="selectedFiles" />
            </div>

            <media-preview
              v-if="selectedFiles.length > 0"
              :ordering="field.ordering"
              hideName
              :changeOrder="handleChange"
              :files="selectedFiles"
              :multiple="multipleSelect"
              :field="field"
            />
            <p class="py-6" style="padding-top: 9px;" v-else>
                {{ __('No media selected') }}
            </p>

            <div class="ml-auto">
                <button type="button"
                        v-on:click="openMediaBrowsingModal"
                        class="btn btn-default btn-primary inline-flex items-center relative ml-auto mr-3">
                      <span>
                            {{ __('Media library') }}
                      </span>
                </button>
                <button type="button"
                        v-if="selectedFiles.length"
                        v-on:click="clearSelectedFiles"
                        class="btn btn-default btn-danger inline-flex items-center relative ml-auto mr-3">
                      <span>
                            {{ __('Clear') }}
                      </span>
                </button>
            </div>
        </template>
    </default-field>
</template>

<script>
  import {FormField, HandlesValidationErrors} from 'laravel-nova';
  import debounce from './../debounce';

  function isString (value) {
    return typeof value === 'string' || value instanceof String;
  }

  export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data: () => ({
      files: [],
      isModalOpen: false,
      activeFile: void 0,
      selectedFiles: [],
      chosenCollection: null,
      loadingMediaFiles: true,
      showUploadArea: false,
    }),

    computed: {
      multipleSelect() {
        return this.field.multiple;
      }
    },

    mounted() {

      if (this.field.value && this.field.value !== '') {
        axios.get('/api/media/find', {
          params: {
            ids: isString(this.field.value) ? this.field.value.split(',') : this.field.value
          },
        }).then(response => {
          this.selectedFiles = response.data.map(file => ({
            data: file,
            processed: true,
            uploading: false,
            uploadProgress: 0
          }));
        });
      }

      if (!window.mediaLibrary) {
        this.loadingMediaFiles = true;

        window.mediaLibrary = {
          files: [],
          loaded: false,
          onload: []
        };

        axios.get('/api/media', {
          params: {
            limit: 128
          },
        }).then(response => {
          this.files = response.data.map(file => {
            return {
              uploading: false,
              processed: true,
              data: file
            };
          });

          window.mediaLibrary.files = response.data.map(file => {
            return {
              uploading: false,
              processed: true,
              data: file
            };
          });

          this.files = [...window.mediaLibrary.files];

          window.mediaLibrary.loaded = true;

          this.updateMedia();
        });
      } else if (window.mediaLibrary.loaded) {
        this.files = [...window.mediaLibrary.files];
      }

      this.updateMedia();

      window.mediaLibrary.onload.push(this.updateFiles);
    },

    methods: {

      updateFiles() {
        this.loadingMediaFiles = false;
        this.files = [...window.mediaLibrary.files];
      },

      updateMedia: debounce(() => {
        if (window.mediaLibrary.onload.length) {
          for (const cb of window.mediaLibrary.onload) {
            if (this.updateFiles !== cb) {
              cb();
            }
          }
        }
      }, 1000),

      openMediaBrowsingModal() {
        this.isModalOpen = true;
        this.showUploadArea = false;
      },

      /*
       * Set the initial, internal value for the field.
       */
      setInitialValue() {
        this.value = this.field.value || '';
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

    },
  };
</script>
