<script>
import { mapGetters } from 'vuex';

export default {
  computed: {
    ...mapGetters({ nextMediaItemIdFromStore: 'media-library/getNextMediaItemId' }),
  },

  methods: {
    /**
     * Adds Media model to media library file list and displays it in media browser
     *
     * @param {Object} models - Array of "Media" models or single model object
     */
    addModelsToMediaLibrary(models) {
      if (Array.isArray(models)) {
        const mapModels = model => this.createMediaLibraryItemFromModel(model)
        this.$store.commit('media-library/addToFiles', models.map(mapModels));
      } else {
        this.$store.commit('media-library/addToFiles', this.createMediaLibraryItemFromModel(models));
      }
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
        thumbnail: model.image_sizes?.thumbnail?.url || '',
        model,
      };
    },

    /**
     * Used when adding custom item to media library list that has no association with model
     */
    getNextMediaItemId() {
      const id = this.nextMediaItemIdFromStore;
      this.$store.commit('media-library/increaseTmpMediaItemId');
      return `tmp-${id}`;
    },

    /**
     * Fetches a page of images and adds them to media library list
     *
     * @param {Number} options.page - Pagination property
     * @param {Number} options.page - Pagination property
     * @param {Object} options.search - Filters results by provided search options
     * @param {String} options.search.file_name - Search by file name
     * @param {String} options.search.alt - Search by file alt text
     * @param {String} options.search.title - Search by file title
     * @returns {Promise<void>}
     */
    async fetchImages(options = {}) {
      const { page = 1, search } = options;
      const { data: { data: imageList } } = await axios.get('/api/media', { params: { page, search } });
      this.addModelsToMediaLibrary(imageList);
    },
  },
};
</script>
