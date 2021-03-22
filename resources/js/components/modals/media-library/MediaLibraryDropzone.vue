<template>
  <div
    v-if="isUploadMode || isDragAndDropping"
    class="flex w-full mb-6 align-middle media-library-dropzone h-full"
    :class="`${showDropAnimation ? 'drop-animation' : ''} ${isDragAndDropping ? 'drag-and-drop' : ''}`"
  >
    <div class="dropzone-content flex flex-col justify-center">
      <div class="mb-2 text-center">
        <svg
          class="fill-current w-4 h-4 mx-auto"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20"
        >
          <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" />
        </svg>
      </div>
      <div class="mb-2 text-center">
        To upload files you can simply drag and drop them in the area or click it to open file browser.
      </div>
      <input
        ref="uploadFileEl"
        type="file"
        name="media"
        class="input-dropzone"
        multiple
        @change="fileBrowserSelectListener"
      >
    </div>
  </div>
</template>
<script>
import MediaLibrary from '../../mixins/MediaLibrary';
import {debounce, throttle} from '../../../helpers';

export default {
  mixins: [MediaLibrary],

  props: {
    isUploadMode: {
      type: Boolean,
      default: false,
      required: true,
    },
  },

  data() {
    return {
      showDropAnimation: false,
      isDragAndDropping: false,
    };
  },

  mounted() {
    this.addEventListeners();
  },

  beforeDestroy() {
    this.clearEventListeners();
  },

  methods: {
    fileBrowserSelectListener(e) {
      e.preventDefault();
      e.stopPropagation();

      this.addFileListToMediaLibrary(e.target.files);

      this.$emit('update:isUploadMode', false);
    },

    dropEventListener(e) {
      e.preventDefault();
      e.stopPropagation();

      this.addFileListToMediaLibrary(e.dataTransfer.files);

      this.$emit('update:isUploadMode', false);
    },

    dragLeaveListener: debounce(function (e) {
      e.preventDefault();
      e.stopPropagation();

      if (!e.relatedTarget || !e.relatedTarget.closest('.od-modal')) {
        if (this.isUploadMode && this.isDragAndDropping) this.$emit('update:isUploadMode', false);
        this.isDragAndDropping = false;
        this.showDropAnimation = false;
      }
    }, 200),

    dragOverListener: throttle(function (e) {
      e.preventDefault();
      e.stopPropagation();

      if (!this.showDropAnimation && e.target && e.target.closest('.od-modal')) {
        this.showDropAnimation = true;

        if (!this.isUploadMode) {
          this.isDragAndDropping = true;
          this.showDropAnimation = true;
        }
      }
    }, 200),

    addEventListeners() {
      window.addEventListener('dragover', this.dragOverListener);
      window.addEventListener('dragleave', this.dragLeaveListener);
      window.addEventListener('drop', this.dropEventListener);
    },

    clearEventListeners() {
      window.removeEventListener('dragover', this.dragOverListener);
      window.removeEventListener('dragleave', this.dragLeaveListener);
      window.removeEventListener('drop', this.dropEventListener);
    },
  },
};
</script>

<style lang="scss" scoped>
@keyframes pulse {
  0% {
    opacity: 1;
  }
  50% {
    opacity: 0.3;
  }
  100% {
    opacity: 1;
  }
}

.media-library-dropzone {
  position: relative;
  z-index: 10000;

  &.drop-animation {
    .dropzone-content {
      animation: pulse infinite linear 2s;
      border-radius: 8px;
      border: 2px dashed #999a9d;
    }
  }

  &.drag-and-drop {
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    left: 0;
    background-color: #fff;
  }
}

.dropzone-content {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;

  transition: opacity 0.3s, border 0.3s;
  border: 2px dashed #fff;
}

.input-dropzone {
  position: absolute;
  top: 0;
  left: 0;
  opacity: 0;
  width: 100%;
  height: 100%;
  cursor: pointer;
}
</style>
