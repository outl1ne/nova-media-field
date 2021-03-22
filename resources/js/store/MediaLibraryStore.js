
const mediaLibraryModalInitialState = {
  isOpen: false,
  uploadOnly: false,
  field: null, // Form field value
}

export default {
  state: {
    mediaLibraryFiles: [],
    selectedFileIds: [],
    nextTmpMediaItemId: 0,
    mediaLibraryModal: {
      ...mediaLibraryModalInitialState
    }
  },
  getters: {
    'media-library/getNextMediaItemId': state => {
      return state.nextTmpMediaItemId;
    },

    'media-library/getField': state => {
      return state.mediaLibraryModal.field;
    },

    'media-library/isMediaLibraryModalOpen': state => {
      return state.mediaLibraryModal.isOpen;
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

    'media-library/closeMediaLibraryModal': state => {
      state.mediaLibraryModal = {
        ...mediaLibraryModalInitialState
      }
    },

    'media-library/openMediaLibraryModal': (state, options) => {
      state.mediaLibraryModal = {
        field: options?.field ?? state.mediaLibraryModal.field,
        uploadOnly: options?.uploadOnly ?? state.mediaLibraryModal.uploadOnly,
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
