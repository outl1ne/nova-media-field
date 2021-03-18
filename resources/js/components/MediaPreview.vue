<template>
  <div
    :class="`preview-container ${field.allowMultipleFiles ? 'multiple-preview' : ''}`"
    :style="compactPreviewStyles"
  >
    <div class="media-preview">
      <browsing-modal-file />
    </div>
  </div>
</template>

<script>
import BrowsingModalFile from "./modals/browsing-modal/BrowsingModalFile";

export default {
  components: {
    BrowsingModalFile,
  },
  inject: ['field'],
  computed: {
    compactPreviewStyles() {
      if (!Array.isArray(this?.field?.detailThumbnailSize)) return null;
      return {
        'min-height': `${this.compactHeight() + 18}px`,
      };
    },
  },
};
</script>
