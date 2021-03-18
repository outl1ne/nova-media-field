export default {
  state: {
    mediaLibraryFiles: [],
    selectedFileIds: [],
    nextTmpMediaItemId: 0,
    mediaBrowser: {
      isOpen: false,
      uploadOnly: false
    }
  },
  getters: {
    'media-library/getNextMediaItemId': state => {
      return state.nextTmpMediaItemId;
    },

    'media-library/isMediaBrowserModalOpen': state => {
      return state.mediaBrowser.isOpen;
    },

    'media-library/getFiles': (state, fileIds = []) => {
      return fileIds.map(id => state.mediaLibraryFiles.find(item => item.id === id));
    },

    'media-library/getAllFiles': (state) => {
      return state.mediaLibraryFiles;
    },

    'media-library/getSelectedFiles': state => {
      return state.selectedFileIds.map(id => state.mediaLibraryFiles.find(item => item.id === id));
    },
  },
  mutations: {

    'media-library/closeMediaBrowserModal': state => {
      state.mediaBrowser = {
        isOpen: false,
        uploadOnly: false
      }
    },

    'media-library/openMediaBrowserModal': (state, options) => {
      state.mediaBrowser = {
        uploadOnly: options?.uploadOnly ?? state.mediaBrowser.uploadOnly,
        isOpen: true
      }
    },

    'media-library/increaseTmpMediaItemId': state => state.nextTmpMediaItemId++,

    'media-library/addToFiles': (state, newMediaLibraryItems = []) => {
      function addItemToLibrary(newItem) {
        let existing = state.mediaLibraryFiles.find(mediaItem => mediaItem.id === newItem.id);
        if (existing) return;
        state.mediaLibraryFiles.push(newItem);
      }

      if (Array.isArray(newMediaLibraryItems)) {
        for (const item of newMediaLibraryItems) {
          addItemToLibrary(item)
        }
      } else {
        addItemToLibrary(newMediaLibraryItems)
      }
    },
  },
};
