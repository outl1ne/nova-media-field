<template>
  <div class="flex w-full justify-between">
    <div>
      <button
        type="button"
        class="btn btn-default btn-primary whitespace-no-wrap"
        @click="openFileBrowser"
      >
        {{ __('Upload files') }}
      </button>
    </div>

    <div
      class="flex justify-end"
      :class="!isUploadMode && 'w-full'"
    >
      <button
        v-if="!isUploadMode"
        type="button"
        class="btn btn-default btn-primary mr-3"
      >
        {{ __('Apply and close') }}
      </button>
      <button
        type="button"
        class="btn btn-default btn-danger"
        @click="closeMediaLibrary"
      >
        {{ __('Close') }}
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    isUploadMode: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    closeMediaLibrary() {
      this.$store.commit('media-library/closeMediaLibraryModal');
    },
    openFileBrowser() {
      document.querySelector('input.input-dropzone')?.click();
    }
  },
};
</script>
