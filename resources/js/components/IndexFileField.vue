<template>
  <div class="p-2">
    <img v-if="isImageFile && !fileIsMissing" :src="previewImg" @error="fileIsMissing = true"
         class="overflow-hidden rounded"/>
    <missing-file-icon  v-else-if="isImageFile && fileIsMissing" />
    <document-icon v-else-if="!isImageFile"  />
  </div>
</template>
<script>

import MissingFileIcon from "../icons/MissingFileIcon";
import DocumentIcon from "../icons/DocumentIcon";
export default {
  name: 'index-file-field',
  components: {DocumentIcon, MissingFileIcon},
  props: ['field'],

  data() {
    const isImageFile = this.field.value.mime_type.indexOf('image/') === 0
    return {
      fileIsMissing: false,
      isImageFile
    }
  },

  computed: {
    previewImg() {
      return this.field.value?.image_sizes?.thumbnail?.url || ''
    }
  }
}
</script>
<style scoped lang="scss">
img, svg {
  width: 100%;
  max-width: 50px;
}
</style>
