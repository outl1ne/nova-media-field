<script>
import {mapActions, mapGetters, mapMutations} from 'vuex';
import {createMediaLibraryItemFromFileList, createMediaLibraryItemFromModel} from "../../helpers";

export default {
  computed: {
    ...mapGetters({ nextMediaItemIdFromStore: 'media-library/getNextMediaItemId' }),
  },

  methods: {
    createMediaLibraryItemFromFileList,
    createMediaLibraryItemFromModel,
    ...mapActions({
      uploadFiles: 'media-library/uploadFiles',
    }),
    ...mapMutations({
      addToFiles: 'media-library/addToFiles',
      increaseNextId: 'media-library/increaseTmpMediaItemId',
    }),

    /**
     * Adds Media model to media library file list and displays it in media browser
     *
     * @param {Object} models - Array of "Media" models or single model object
     */
    addModelsToMediaLibrary(models) {
      const mapModel = model => this.createMediaLibraryItemFromModel(model);
      if (Array.isArray(models)) {
        this.addToFiles(models.map(mapModel));
      } else {
        // Handles single file
        this.addToFiles(mapModel(models));
      }
    },

    /**
     * Adds FileList files to media library for upload
     *
     * @param {FileList|File|Array} fileList - FileList array or single File object
     */
    addFileListToMediaLibrary(fileList) {
      if (!(fileList instanceof FileList)) return;
      else if (fileList instanceof File) fileList = [fileList]; // Support older browsers

      // If not already a list
      if (!fileList.length) {
        fileList = [fileList];
      }

      this.uploadFiles(this.createMediaLibraryItemFromFileList(fileList));
    },

    /**
     * Used when adding custom item to media library list that has no association with model, like files that are not
     * yet uploaded
     */
    getNextMediaItemId() {
      const id = this.nextMediaItemIdFromStore;
      this.increaseNextId();
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
      const {
        data: { data: imageList },
      } = await axios.get('/api/media', { params: { page, search } });
      this.addModelsToMediaLibrary(imageList);
    },
  },
};
</script>
