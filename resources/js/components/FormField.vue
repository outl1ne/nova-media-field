<template>
    <default-field :field="field" :errors="errors">

        <template slot="field">

            <div ref="modals">
                <od-modal ref="isModalOpen" v-if="isModalOpen" :name="'isModalOpen'" :align="'flex justify-end'"
                          :width="1000">

                    <div slot="container">
                        <div class="flex flex-wrap justify-between mb-6">
                            <h2 class=" text-90 font-normal text-xl">Media library</h2>
                        </div>

                        <div class="flex mb-6" id="media-dropzone">

                            <div class="img-collection">

                                <div class="empty-message" v-if="files.length === 0">
                                    <p>
                                        There are currently no media files in this library
                                    </p>
                                    <p>
                                        Drag and drop files here to upload them
                                    </p>
                                </div>

                                <uploaded-file v-for="file in files"
                                               :selected="selectedFiles.find(item => item.processed && item.data.id === file.data.id) !== void 0"
                                               :active="file.processed && activeFile && file.data.id === activeFile.data.id"
                                               v-on:click.native="toggleFileSelect(file)" v-bind:key="file"
                                               :file="file.processed ? file.data : void 0"
                                               :progress="file.uploading ? file.uploadProgress : -1"/>
                            </div>

                            <div class="image-editor">
                                <edit-image v-if="activeFile !== void 0" :file="activeFile.data"/>
                            </div>

                            <div :class="`input-dropzone-wrap ${draggingFile ? 'visible' : ''} ${draggingFile && !draggingOverDropzone ? 'pulse' : ''}`">
                                <p>Drag and drop your files here!</p>
                                <input type="file" name="media" class="input-dropzone">
                            </div>

                        </div>
                    </div>
                    <div slot="buttons">
                        <div class="ml-auto">
                            <button type="button" @click.prevent="closeModal"
                                    class="btn btn-default btn-primary inline-flex items-center relative ml-auto mr-3">
                                {{ __('Close') }}
                            </button>
                        </div>
                    </div>
                </od-modal>
            </div>

            <media-preview v-if="selectedFiles.length !== 0" :files="selectedFiles" :multiple="multipleSelect"/>

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

<style lang="scss">

    @keyframes pulse {
        0% {
            opacity: 1;
        }
        50% {
            opacity: .4;
        }
        100% {
            opacity: 1;
        }
    }

    .preview-container {
        margin-bottom: 20px;
    }

    .empty-message {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        text-align: center;
        color: #7c858e;
    }

    #media-dropzone {
        position: relative;
        display: flex;
        min-height: 300px;

        .img-collection {
            width: calc(100% - 274px);
            display: flex;
            margin-left: -5px;
            flex-wrap: wrap;
            overflow-y: auto;
            max-height: 480px;
            position: relative;


            &::-webkit-scrollbar-track {
                -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
                background-color: #fff;
                border-radius: 3px;
            }

            &::-webkit-scrollbar {
                width: 6px;
                border-radius: 3px;
            }

            &::-webkit-scrollbar-thumb {
                background-color: rgba(#000, .1);
                border-radius: 3px;
            }

            .uploaded-file {
                margin: 5px;
            }
        }

        .image-editor {
            width: 295px;
            border-left: 1px dashed rgba(#000, .1);
            margin-left: 5px;
        }

        .input-dropzone-wrap {
            position: absolute;
            width: 100%;
            height: 100%;
            border: 1px dashed rgba(#000, .4);
            background-color: #fff;
            top: 0;
            left: 0;
            display: flex;
            justify-content: center;
            align-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;

            p {
                text-align: center;
                color: #7c858e;
                line-height: 18px;
                margin: 0;
            }

            &.visible {
                opacity: 1;
                visibility: visible;
            }

            &.pulse {
                opacity: 1;

                p {
                    animation: pulse 1s infinite;
                }
            }
        }

        .input-dropzone {
            opacity: 0;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }
    }

</style>

<script>
  import {FormField, HandlesValidationErrors} from 'laravel-nova';
  import debounce from './../debounce';

  export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field', 'multipleUploads'],

    data: () => ({
      files: [],
      isModalOpen: false,
      draggingOverDropzone: false,
      draggingFile: false,
      activeFile: void 0,
      selectedFiles: [],
      endDrag: false
    }),

    computed: {
      multipleSelect() {
        return this.field.multiple;
      },
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

      dropEventListener(e) {
        e.preventDefault();
        e.stopPropagation();

        this.draggingFile = false;
        this.draggingOverDropzone = false;

        for (const fileKey of Object.keys(e.dataTransfer.files)) {
          this.files.unshift({
            fileInput: e.dataTransfer.files[fileKey],
            uploadProgress: 0,
            uploading: false,
            processed: false
          });
        }

        this.uploadFiles();
      },

      dragEndListener(e) {
        e.preventDefault();

        this.endDrag = true;

        this.setEndDrag();
      },


      dragLeaveListener(e) {
        e.preventDefault();

        if (e.target.matches('.input-dropzone')) {
          this.draggingOverDropzone = false;
        } else {
          this.endDrag = true;
          this.setEndDrag();
        }
      },


      dragOverListener(e) {
        e.preventDefault();

        this.draggingFile = true;

        this.endDrag = false;

        if (e.target.matches('.input-dropzone')) {
          this.draggingOverDropzone = true;
        }
      },

      setEndDrag: debounce(function () {

        if (this.endDrag) {
          this.draggingFile = false;
          this.draggingOverDropzone = false;
        }
      }, 1000),

      updateMedia: debounce(() => {
        if (window.mediaLibrary.onload.length) {
          for (const cb of window.mediaLibrary.onload) {
            if (this.updateFiles !== cb) {
              cb();
            }
          }
        }
      }, 1000),

      addEventListeners() {
        window.addEventListener('dragover', this.dragOverListener);
        window.addEventListener('dragleave', this.dragLeaveListener);
        window.addEventListener('dragend', this.dragEndListener);
        window.addEventListener('drop', this.dropEventListener);
      },

      clearEventListeners() {
        window.removeEventListener('dragover', this.dragOverListener);
        window.removeEventListener('dragleave', this.dragLeaveListener);
        window.removeEventListener('dragend', this.dragEndListener);
        window.removeEventListener('drop', this.dropEventListener);
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
        this.value = value;
      },

      openModal() {
        this.addEventListeners();
        this.isModalOpen = true;
      },

      closeModal() {
        this.clearEventListeners();
        this.isModalOpen = false;
      },

      toggleFileSelect(file) {

        if (this.activeFile && this.activeFile.data.id === file.data.id) {
          this.activeFile = void 0;

          if (!this.multipleSelect) {
            this.selectedFiles = [];
            return;
          }
        }

        if (this.multipleSelect && !this.selectedFiles.find(item => item.processed && item.data.id === file.data.id)) {
          this.selectedFiles.push(file);
        } else if (this.multipleSelect && this.selectedFiles.find(item => item.processed && item.data.id === file.data.id)) {
          const i = this.selectedFiles.findIndex(item => item.processed && +item.data.id === +file.data.id);

          if (i > -1) {
            this.selectedFiles.splice(i, 1);
          }

          return;
        } else if (!this.multipleSelect) {
          this.selectedFiles = [file];
        }

        this.activeFile = file;
      },

      uploadFiles() {

        for (const fileInfo of this.files) {

          if (!fileInfo.fileInput || fileInfo.uploading || fileInfo.processed) {
            continue;
          }

          const form = new FormData();

          form.append('file', fileInfo.fileInput);

          fileInfo.uploading = true;

          axios.post('/api/media/upload',
            form,
            {
              headers: {
                'Content-Type': 'multipart/form-data'
              },
              onUploadProgress: e => {
                fileInfo.uploadProgress = (e.loaded / e.total) * 100;
              }
            }
          ).then(response => {

            window.mediaLibrary.files.unshift({
              uploading: false,
              processed: true,
              data: response.data
            });

            this.updateMedia();
          });
        }


      },
    },
  };
</script>
