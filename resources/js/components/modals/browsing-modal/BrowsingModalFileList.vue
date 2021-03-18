<template>
  <div :class="`flex mb-6`">
    <div v-if="false">
      <div
        v-if="allMediaFiles.length === 0"
        class="empty-message"
      >
        <p>There are currently no media files in this library</p>
        <p>Drag and drop files here to upload them</p>
      </div>

      <div
        v-if="allMediaFiles.length === 0 && uploadOnly"
        class="empty-message"
      >
        <p>Drag and drop files here to upload them</p>
      </div>
    </div>
    <div
      ref="imgCollectionRef"
      class="img-collection flex w-full whitespace-normal"
    >
      <browsing-modal-file
        v-for="file of allMediaFiles"
        :key="file.id"
        :file="file"
      />
    </div>
  </div>
</template>
<script>
import BrowsingModalFile from './BrowsingModalFile';
import { mapGetters } from 'vuex';

export default {
  components: { BrowsingModalFile },

  props: {
    uploadOnly: {
      type: Boolean,
      default: false,
    },
  },

  computed: {
    ...mapGetters({
      allMediaFiles: 'media-library/getAllFiles',
    }),
  },

  mounted() {
    setTimeout(() => {
      console.log('woof', this.allMediaFiles)
    },2000)
  },
};
</script>
<style lang="scss">
.img-collection {
  height: 60vh;

  .uploaded-file {
    margin: 8px;
  }
}
</style>
