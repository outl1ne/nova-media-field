<template>
  <div class="mime-type-icon">
    <img
      v-if="isImageFile && src && !isImageFileMissing"
      draggable="false"
      :src="src"
      @error="isImageFileMissing = true"
    />
    <missing-file-icon v-else-if="isImageFile && isImageFileMissing" class="p-2" />
    <audio-icon v-else-if="isAudioFile" class="p-2" />
    <thumbnail-video-icon v-else-if="isVideoFile && !showVideo" icon="video-icon" class="p-2" />
    <video v-else-if="isVideoFile && showVideo" controls>
      <source :src="src" :type="mimeType" />
    </video>
    <document-icon v-else class="p-2" />
  </div>
</template>
<script>
import AudioIcon from '../icons/AudioIcon';
import DocumentIcon from '../icons/DocumentIcon';
import MissingFileIcon from '../icons/MissingFileIcon';

export default {
  name: 'mime-type-icon',

  props: ['mimeType', 'src', 'showVideo'],

  components: {
    AudioIcon,
    DocumentIcon,
    MissingFileIcon,
  },

  data() {
    return {
      isImageFileMissing: false,
    };
  },

  computed: {
    isImageFile() {
      return this.mimeType?.indexOf('image/') === 0;
    },
    isAudioFile() {
      return this.mimeType?.indexOf('audio/') === 0;
    },
    isVideoFile() {
      return this.mimeType?.indexOf('video/') === 0;
    },
  },
};
</script>

<style lang="scss" scoped>
.mime-type-icon {
  width: 100%;
}

video {
  width: 100%;
  max-height: 100px;
}
</style>
