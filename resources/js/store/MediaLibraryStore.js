import {createMediaLibraryItemFromModel, createUploadRequestPayloads} from "../helpers";

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
      ...mediaLibraryModalInitialState,
    },
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

    'media-library/getAllFiles': state => {
      return state.mediaLibraryFiles;
    },

    'media-library/getSelectedFiles': state => {
      return state.selectedFileIds.map(id => state.mediaLibraryFiles.find(item => item.id === id));
    },
  },
  mutations: {
    'media-library/closeMediaLibraryModal': state => {
      state.mediaLibraryModal = {
        ...mediaLibraryModalInitialState,
      };
    },

    'media-library/openMediaLibraryModal': (state, options) => {
      state.mediaLibraryModal = {
        field: options?.field ?? state.mediaLibraryModal.field,
        uploadOnly: options?.uploadOnly ?? state.mediaLibraryModal.uploadOnly,
        isOpen: true,
      };
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
          addItemToLibrary(item);
        }
      } else {
        addItemToLibrary(newMediaLibraryItems);
      }
    },

    'media-library/removeFromFiles': (state, id) => {
      const index = state.mediaLibraryFiles.findIndex(mediaItem => mediaItem.id === id)
      state.mediaLibraryFiles.splice(index, 1)
    },

    'media-library/updateFile': (state, id, key, value) => {
      const index = state.mediaLibraryFiles.findIndex(mediaItem => mediaItem.id === id)
      state.mediaLibraryFiles[index][key] = value
    },
  },
  actions: {
    'media-library/uploadFiles': ({ commit }, files, options = {}) => {
      const { onUploadProgress } = options
      commit('media-library/addToFiles', files)
      const uploadRequestPayloads = createUploadRequestPayloads(files)

      for (const requestPayload of uploadRequestPayloads) {
        const form = new FormData();

        for (const file of requestPayload) {
          form.append('file[]', file);
        }

        form.append('collection', files.collection);

        const onSuccess = ({data}) => {
          commit('media-library/removeFromFiles', files.id)

          if (Array.isArray(data)) {
            commit('media-library/addToFiles', data.map(createMediaLibraryItemFromModel))
          }
        }

        const onError = error => {
          if (!error.response) {
            Nova.$emit('error', 'Failed to upload image.');
            return;
          }

          const response = error.response.data;

          if (Array.isArray(response && response.errors && response.errors.file)) {
            Nova.$emit('error', response.errors.file[0]);
          } else if (response.message) {
            Nova.$emit('error', response.message);
          } else {
            Nova.$emit('error', 'Failed to upload image.');
          }

          commit('media-library/removeFromFiles', files.id)
        }

        const onUploadProgress = e => {
          console.log('e', e)
        }

        axios
          .post('/api/media/upload', form, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
            onUploadProgress,
          })
          .then(onSuccess)
          .catch(onError)
      }
    },
  },
};
