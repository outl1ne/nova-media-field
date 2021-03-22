<template>
  <div class="modal-header flex flex-wrap justify-between mb-6">
    <h2 class="text-90 font-normal text-xl">
      <span v-if="uploadOnly">Upload files</span>
      <span v-else-if="!uploadOnly && !field.displayCollection">Browse all files</span>
      <span v-else-if="!uploadOnly && field.displayCollection">Browse <strong>{{ collection }}</strong></span>
    </h2>
  </div>
</template>

<script>
export default {
  props: {
    uploadOnly: {
      type: Boolean,
      default: false
    },
    field: {
      type: Object,
      required: true
    }
  },

  computed: {
    collection() {
      const config = window.config.mediaLibrary
      return config?.collections?.[this.field.displayCollection]?.label || this.field.displayCollection
    }
  }
};
</script>
