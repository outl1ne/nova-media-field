<template>
  <div
    id="media-library-dropzone"
    class="flex w-full mb-6 align-middle"
    :class="showDropAnimation && 'drop-animation'"
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

import MediaLibrary from "../../mixins/MediaLibrary";

export default {

  mixins: [MediaLibrary],

  props: {
    isUploadMode: {
      type: Boolean,
      default: false,
      required: true
    }
  },

  data() {
    return {
      showDropAnimation: false
    }
  },

  mounted() {
    this.addEventListeners()
  },

  beforeDestroy() {
    this.clearEventListeners()
  },

  methods: {

    fileBrowserSelectListener(e) {
      e.preventDefault();
      e.stopPropagation();

      this.addFileListToMediaLibrary(e.target.files)

      this.$emit('update:isUploadMode', false)
    },

    dropEventListener(e) {
      e.preventDefault();
      e.stopPropagation();

      this.addFileListToMediaLibrary(e.dataTransfer.files)

      this.$emit('update:isUploadMode', false)
    },

    dragLeaveListener(e) {
      e.preventDefault();
      e.stopPropagation();

      if (e.target.matches('.input-dropzone')) {
        this.showDropAnimation = false;
      }
    },

    dragOverListener(e) {
      e.preventDefault();
      e.stopPropagation();

      if (e.target.matches('.input-dropzone')) this.showDropAnimation = true;
    },

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

  }
};
</script>

<style lang="scss">
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

#media-library-dropzone {
  padding-bottom: 52%;
  position: relative;
  border-radius: 8px;
  transition: opacity 0.3s, border 0.3s;
  border: 2px dashed #fff;

  &.drop-animation {
    border: 2px dashed #999a9d;
    animation: pulse infinite linear 2s;
  }
}

.dropzone-content {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.media-rules {
  padding: 0 0 10px 0;
  display: flex;
  justify-content: flex-start;
  align-content: center;
  align-items: center;
  border-bottom: 1px dashed rgba(0, 0, 0, 0.1);
  width: calc(100% - 300px);
  margin-bottom: 10px;
  margin-top: -15px;
  flex-wrap: wrap;

  .media-rule-label {
    width: 100%;
    padding-bottom: 5px;
  }

  .single-constraint {
    display: flex;
    flex-flow: column;
    padding-right: 35px;
    font-size: 14px;

    > span:nth-child(1) {
      text-transform: capitalize;
    }

    > span:nth-child(2) {
      font-weight: bold;
    }
  }
}

input {
  position: absolute;
  top: 0;
  left: 0;
  opacity: 0;
  width: 100%;
  height: 100%;
  z-index: 9999;
  cursor: pointer;
}
</style>
