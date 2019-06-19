<template>
    <od-modal ref="isModalOpen" v-if="isModalOpen" :name="'isModalOpen'" :align="'flex justify-end'"
              width="1315">

        <div slot="container">
            <div class="modal-header flex flex-wrap justify-between mb-6">
                <h2 class="text-90 font-normal text-xl">
                    Media library
                    {{ currentCollection ? `(${field.collections[currentCollection].label})` : '' }}
                </h2>

                <div class="collection-select">
                    <span>Collection</span>
                    <select-control
                            :id="collectionField.attribute"
                            :dusk="collectionField.attribute"
                            :fieldName="collectionField.fieldName"
                            :options="collectionField.options"
                            v-model="currentCollection"
                            :disabled="displayCollection != null"
                            class="w-full form-control form-select">
                        <option value="" selected>{{ __('All collections') }}</option>
                    </select-control>
                </div>
            </div>

            <media-modal-constraints :field="field"
                                     :currentCollection="currentCollection" />

            <div :class="`flex mb-6`" id="media-dropzone">

                <div class="img-collection">

                    <div class="empty-message" v-if="files.length === 0">
                        <p>
                            There are currently no media files in this library
                        </p>
                        <p>
                            Drag and drop files here to upload them
                        </p>
                    </div>

                    <uploaded-file v-for="file in files.filter(filterUploadedFiles)"
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
</template>

<script>
  import debounce from './../debounce';

  export default {

    props: ['field', 'isModalOpen', 'chosenCollection', 'activeFile', 'selectedFiles', 'updateMedia', 'files'],

    data: () => ({
      draggingOverDropzone: false,
      draggingFile: false,
    }),

    computed: {
      currentCollection: {
        get: function () {
          return this.field.displayCollection || this.chosenCollection;
        },
        set: function (value) {
          this.$emit('update:chosenCollection', value);
        }
      },

      displayCollection() {
        return this.field.displayCollection;
      },

      collectionField() {
        const {collections} = this.field;
        if (!collections || !Object.keys(collections).length) return false;
        return {
          attribute: 'display-collection',
          fieldName: 'display-collection',
          options: Object.keys(collections).map(collectionKey => {
            return {
              label: collections[collectionKey].label,
              value: collectionKey
            };
          })
        };
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

    },

    watch: {
      isModalOpen: function (newVal, oldVal) { // watch it

        if (newVal) {
          this.addEventListeners();
        } else {
          this.clearEventListeners();
        }
      }
    },

    methods: {

      openModal() {
        this.$emit('update:isModalOpen', true);
      },

      closeModal() {
        this.$emit('update:isModalOpen', false);
      },

      filterUploadedFiles(file) {

        if (file.uploading || !file.processed) {
          return true;
        }

        return !this.currentCollection || (file.data.collection_name && file.data.collection_name === this.currentCollection);
      },

      dropEventListener(e) {
        e.preventDefault();
        e.stopPropagation();

        this.draggingFile = false;
        this.draggingOverDropzone = false;

        for (const fileKey of Object.keys(e.dataTransfer.files)) {
          this.files.unshift({
            fileInput: e.dataTransfer.files[fileKey],
            collection: this.currentCollection || null,
            data: {},
            uploadProgress: 0,
            uploading: false,
            processed: false
          });

          this.$emit('update:files', this.files);
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

        if (e.target.matches('.input-dropzone')) this.draggingOverDropzone = true;
      },

      setEndDrag: debounce(function () {

        if (this.endDrag) {
          this.draggingFile = false;
          this.draggingOverDropzone = false;
        }
      }, 1000),

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

          if (fileInfo.collection) {
            form.append('collection', fileInfo.collection);
          }

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

            this.$emit('update:files', this.files);

            this.updateMedia();
          });
        }
      },

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
    }
  };
</script>
