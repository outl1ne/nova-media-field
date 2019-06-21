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
                                      :selectedFiles.sync="selectedFiles" />
            </div>

            <media-preview :ordering="field.ordering" v-if="selectedFiles.length !== 0" hideName :changeOrder="handleChange" :files="selectedFiles" :multiple="multipleSelect"/>

            <div class="ml-auto">
                <button type="button" v-on:click="openModal"
                        class="btn btn-default btn-primary inline-flex items-center relative ml-auto mr-3">
                      <span>
                            {{ __('Browse or upload media') }}
                      </span>
                </button>
            </div>
        </template>
    </default-field>
</template>

<script>
  import {FormField, HandlesValidationErrors} from 'laravel-nova';
  import debounce from './../debounce';

  export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data: () => ({
      files: [],
      isModalOpen: false,
      activeFile: void 0,
      selectedFiles: [],
      chosenCollection: null
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
            ids: this.field.value.split(',')
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
        window.mediaLibrary = {
          files: [],
          loaded: false,
          onload: []
        };

        axios.get('/api/media', {
          params: {
            limit: 16
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

      window.mediaLibrary.onload.push(this.updateFiles);
    },

    methods: {

      updateFiles() {
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

      openModal() {
        this.isModalOpen = true;
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

    },
  };
</script>
