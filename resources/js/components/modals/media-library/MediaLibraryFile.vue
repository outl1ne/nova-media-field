<template>
  <div :class="`media-library-file ${hasFocus ? 'focus' : ''} ${hasSelected ? 'selected' : ''}`">
    <div
      v-if="fileThumbnail"
      class="thumbnail-container"
    >
      <img
        draggable="false"
        :src="fileThumbnail"
      >
    </div>
    <div
      v-if="fileName && !hideName"
      class="media-library-file-name"
    >
      {{ fileName || '' }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    file: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      hideName: false,
      hasFocus: false,
      hasSelected: false
    }
  },

  computed: {
    fileName() {
      return this.file?.model?.file_name || this.file?.id
    },
    fileThumbnail() {
      return this.file?.thumbnail || ''
    }
  }
};
</script>

<style lang="scss" scoped>
.media-library-file {
  width: 125px;
  height: 150px;
  position: relative;
  transition: box-shadow 0.2s, border-color 0.2s;
  overflow: hidden;
  cursor: pointer;

  .media-library-file-name {
    padding: 4px 5px;
    font-size: 11px;
    line-height: 16px;
    width: 100%;
    overflow: hidden;
    position: absolute;
    bottom: 0;
    color: #4099de;
    white-space: nowrap;
    text-overflow: ellipsis;
    font-weight: bold;
    text-align: center;
  }

  &:hover {
    .thumbnail-container {
      box-shadow: 0 0 5px rgba(#4099de, 0.5);
      border-color: #4099de;
    }
  }

  &.selected {
    .thumbnail-container {
      border: 4px solid #d6666d;
      box-shadow: 0 0 10px rgba(#4099de, 0.5);
    }
  }

  &.active {
    .thumbnail-container {
      border: 4px solid #4099de;
    }
  }

  .thumbnail-container {
    position: relative;
    width: 100%;
    height: calc(100% - 25px);
    top: 0;
    left: 0;
    border-radius: 5px;
    overflow: hidden;
    border: 1px solid transparent;
    box-shadow: 0 0 5px rgba(#4099de, 0);
  }

  img,
  svg {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    object-fit: contain;
  }

  svg.thumbnail-placeholder {
    top: calc(50% - 13px);
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0.2;
    width: 100%;
    max-width: 75px;
  }
}

</style>
