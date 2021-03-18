<template>
  <div class="flex w-full justify-between">
    <div>
      <button
        v-if="!isUploadMode"
        type="button"
        class="btn btn-default btn-primary whitespace-no-wrap"
      >
        {{ __('Upload files') }}
      </button>
      <button
        v-if="isUploadMode"
        type="button"
        class="btn btn-default btn-primary whitespace-no-wrap"
      >
        {{ __('Back to library') }}
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
        @click="closeBrowsingModal"
      >
        {{ __('Close') }}
      </button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isUploadMode: false
    }
  },
  methods: {
    closeBrowsingModal() {
      this.$store.commit('media-library/closeMediaBrowserModal');
    },
  },
};
</script>
