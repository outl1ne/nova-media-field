<template>
  <div class="field-buttons ml-auto">
    <button
      v-if="selectedFiles.length"
      type="button"
      class="btn btn-default btn-danger inline-flex items-center relative ml-auto mr-3"
    >
      <span>
        {{ __('Clear') }}
      </span>
    </button>
    <button
      type="button"
      class="btn btn-default btn-primary inline-flex items-center relative ml-auto mr-3"
      @click="openMediaLibraryModal"
    >
      <span>
        {{ __('Media library') }}
      </span>
    </button>
  </div>
</template>
<script>
import { mapGetters } from 'vuex';

export default {
  inject: ['_field'],
  props: {
    hasSelectedFiles: {
      type: Boolean,
      default: false,
      required: true,
    },
  },

  computed: {
    ...mapGetters({
      selectedFiles: 'media-library/getSelectedFiles',
    }),
  },

  methods: {
    openMediaLibraryModal() {
      this.$store.commit('media-library/openMediaLibraryModal');
    },
  },
};
</script>
