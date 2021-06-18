<template>
  <div
    :class="`uploaded-file ${active ? 'active' : ''} ${selected ? 'selected' : ''}`"
    :style="compactStyles"
    @click="onClick"
  >
    <div v-if="progress !== -1" class="upload-progress">
      <div class="progress-bar">
        <div class="progress" :style="`width: ${progress}%`"></div>
      </div>
    </div>

    <div class="thumbnail-container" v-if="file.image_sizes !== void 0">
      <mime-type-icon :src="fileThumbnail" :mime-type="this.file.mime_type" />
    </div>

    <div class="checked-box" v-if="selected">
      <checkbox :checked="selected"/>
    </div>

    <div class="uploaded-file-name" v-if="file.file_name && !hideName">
      {{ file.file_name || '' }}
    </div>
  </div>
</template>

<script>
import DocumentIcon from "../icons/DocumentIcon";
import MissingFileIcon from "../icons/MissingFileIcon";
import MimeTypeIcon from "./MimeTypeIcon";

export default {
  props: {
    hideName: {
      type: Boolean,
      default: false,
      required: false,
    },
    progress: {
      type: Number,
      default: -1,
      required: false,
    },
    file: {
      type: Object,
      default: void 0,
      required: false,
    },
    active: {
      type: Boolean,
      default: false,
      required: false,
    },
    dimensions: {
      type: Array,
      default: null,
      required: false,
    },
    selected: {
      type: Boolean,
      default: false,
      required: false,
    },
  },

  components: {
    MimeTypeIcon,
    DocumentIcon,
    MissingFileIcon
  },

  methods: {
    onClick() {
      this.$emit('click');
    },
    compactWidth() {
      return Array.isArray(this.dimensions) && this.dimensions[0];
    },
    compactHeight() {
      return Array.isArray(this.dimensions) && (this.dimensions[1] || this.dimensions[0]);
    },
  },

  computed: {
    fileThumbnail() {
      if (!Object.keys(this.file).length) return '';
      if (this.file.image_sizes.thumbnail) return this.file.image_sizes.thumbnail.url;
      if (this.file.mime_type.indexOf('video') === 0) return this.file.data.thumbnail || '';
      return (this.file.image_sizes.thumbnail || this.file).url;
    },
    compactStyles() {
      return {
        width: `${this.compactWidth()}px`,
        height: `${this.compactHeight()}px`,
      };
    },
  },
};
</script>

<style lang="scss">
.checked-box {
  position: absolute;
  top: 12px;
  right: 12px;
  width: 30px;
  height: 30px;
}

.uploaded-file {
  width: 125px;
  height: 150px;
  position: relative;
  transition: box-shadow 0.2s, border-color 0.2s;
  overflow: hidden;
  cursor: pointer;

  .uploaded-file-name {
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

.upload-progress {
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
  align-content: center;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(#000, 0.01);

  .progress-bar {
    position: relative;
    width: 80%;
    height: 12px;
    border: 1px solid #4099de;
    border-radius: 4px;
    background-color: #c5ccde;
    overflow: hidden;
  }

  .progress {
    position: absolute;
    height: 100%;
    top: 0;
    left: 0;
    width: 0;
    transition: width 0.2s;
    background-color: #4099de;
  }
}
</style>
