<template>

    <div :class="`preview-container ${multiple ? 'multiple-preview' : ''}`"
         :style="compactPreviewStyles">
        <media-edit-modal :isModalOpen.sync="isModalOpen" :file="editingFile"/>

        <draggable v-if="files && files.length && multiple && ordering"
                   class="media-preview"
                   v-model="files"
                   @start="drag=true"
                   @end="onDrageEnd">
            <uploaded-file @click="onClick(file)"
                           v-for="file in files"
                           v-bind:key="file"
                           :file="file.data"
                           :dimensions="field.detailThumbnailSize"
                           :hideName="hideName || Array.isArray(field.detailThumbnailSize)"/>
        </draggable>

        <div v-if="files && files.length && multiple && !ordering" class="media-preview no-order">
            <uploaded-file @click="onClick(file)"
                           v-for="file in files"
                           v-bind:key="file"
                           :file="file.data"
                           :dimensions="field.detailThumbnailSize"
                           :hideName="hideName || Array.isArray(field.detailThumbnailSize)"/>
        </div>

        <div class="media-preview" v-if="files && files.length && !multiple">
            <uploaded-file @click="onClick(files[0])"
                           :file="files[0].data"
                           :dimensions="field.detailThumbnailSize"
                           :hideName="hideName || Array.isArray(field.detailThumbnailSize)"/>
        </div>

    </div>

</template>

<script>
  import draggable from 'vuedraggable';

  export default {
    props: {
      ordering: {
        type: Boolean,
        default: true,
        required: false,
      },
      hideName: false,
      multiple: {
        type: Boolean,
        default: false,
        required: false,
      },
      changeOrder: {
        type: Function,
        required: true,
      },
      files: {
        type: Array,
        default: [],
        required: false,
      },
      field: {},
    },

    data: () => {
      return {
        drag: false,
        stateFile: this.files,
        editingFile: null,
        isModalOpen: false,
      };
    },

    components: {
      draggable,
    },

    methods: {
      onDrageEnd() {
        this.drag = false;
        this.changeOrder(this.files);
      },
      onClick(file) {
        if (this.field) {
          this.editingFile = file;
          this.isModalOpen = true;
        }
      },
      compactHeight() {
        return Array.isArray(this.dimensions) && (this.dimensions[1] || this.dimensions[0]);
      }
    },

    computed: {
      compactPreviewStyles() {
        if (!Array.isArray(this.field.detailThumbnailSize)) return null;
        return {
          ['min-height']: `${this.compactHeight() + 18}px`,
        }
      },
    }
  };
</script>

<style lang="scss">

    .preview-container {
        padding: 5px;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid #eef1f4;
        height: 119px;
        width: 119px;

        &.multiple-preview {
            min-height: 105px;
            height: auto;
            max-height: 235px;
            width: 100%;
            overflow-y: auto;

            .uploaded-file:hover {
                cursor: all-scroll;
            }

            .no-order {

                .uploaded-file:hover {
                    cursor: inherit;
                }
            }
        }

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

        .media-preview {
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
        }

        .uploaded-file {
            width: 104px;
            height: 104px;
            margin: 2.5px;
            border: 1px solid transparent;

            &:hover {
                border: 1px solid #bbbec0;
                box-shadow: none;
            }

            img {
                object-fit: cover;
            }
        }
    }

</style>
