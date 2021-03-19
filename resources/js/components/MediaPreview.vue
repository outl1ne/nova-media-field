<template>
  <div
    :class="`preview-container ${field.allowMultipleFiles ? 'multiple-preview' : ''}`"
    :style="compactPreviewStyles"
  >
    <div class="media-preview">
      Raw id: {{ field.value }}
    </div>
  </div>
</template>

<script>
export default {
  inject: ['field'],
  computed: {
    compactPreviewStyles() {
      if (!Array.isArray(this.field?.detailThumbnailSize)) return null;
      return {
        'min-height': `${this.compactHeight() + 18}px`,
      };
    },
  },
};
</script>
