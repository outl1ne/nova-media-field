<script>
import { mapGetters } from 'vuex';

export default {
  methods: {
    /**
     * Adds Media model to media library file list and displays it in media browser
     *
     * @param {Object} model - "Media" model
     */
    addModelToMediaLibrary(model) {
      this.$store.commit('mediaLibrary.addToFiles', this.createMediaLibraryItemFromModel(model));
    },

    /**
     * Creates uniform media library item
     *
     * @param {Object} model - "Media" model
     */
    createMediaLibraryItemFromModel(model) {
      return {
        id: model.id,
        uploaded: true,
        thumbnail: null,
        model,
      };
    },

    /**
     * Used when adding custom item to media library list that has no association with model
     */
    getNextMediaItemId() {
      const id = this.nextMediaItemIdFromStore;
      this.$store.commit('mediaLibrary.increaseTmpMediaItemId');
      return `tmp-${id}`;
    },
  },

  computed: {
    ...mapGetters({ nextMediaItemIdFromStore: 'mediaLibrary.getNextMediaItemId' }),
  },
};
</script>
