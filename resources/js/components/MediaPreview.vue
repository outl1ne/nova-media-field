<template>

    <div :class="`preview-container ${multiple ? 'multiple-preview' : ''}`">


        <draggable v-if="files && files.length && multiple" class="media-preview" v-model="files" @start="drag=true" @end="onDrageEnd">
            <uploaded-file v-if="multiple" v-for="file in files" v-bind:key="file" :file="file.data" :hideName="hideName"/>
        </draggable>

        <div class="media-preview" v-if="files && files.length && !multiple">
            <uploaded-file v-if="!multiple" :file="files[0].data" :hideName="hideName"/>
        </div>

    </div>

</template>

<script>
  import draggable from 'vuedraggable'

  export default {
    props: {
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
      }
    },

    data: () => {
      return {
        drag: false,
        stateFile: this.files
      }
    },

    components: {
      draggable,
    },

    methods: {
      onDrageEnd() {
        this.drag = false;
        this.changeOrder(this.files);
      }
    }
  };
</script>

<style lang="scss">

    .preview-container {
        padding: 5px;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid #eef1f4;
        height: 105px;
        width: 105px;
        overflow: hidden;

        &.multiple-preview {
            min-height: 105px;
            height: auto;
            max-height: 235px;
            width: 100%;
            overflow-y: auto;
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
            max-height: 235px;
        }

        .uploaded-file {
            width: 104px;
            height: 104px;
            margin: 2.5px;
            border: 1px solid #bbbec0;

            &:hover {
                border: 1px solid #bbbec0;
                box-shadow: none;
                cursor: all-scroll;
            }

            img {
                object-fit: cover;
            }
        }
    }

</style>
