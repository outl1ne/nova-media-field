export default {
  state: {
    mediaLibraryItems: [],
    nextTmpMediaItemId: 0,
  },
  getters: {
    'mediaLibrary.getNextMediaItemId': state => {
      return state.nextTmpMediaItemId;
    },

    'mediaLibrary.getItemsByCategory': state => {
      return state.nextTmpMediaItemId;
    },
  },
  mutations: {
    'mediaLibrary.increaseTmpMediaItemId': state => state.nextTmpMediaItemId++,

    'mediaLibrary.addToFiles': (state, newMediaLibraryItem) => {
      let existing = state.mediaLibraryItems.find(mediaItem => mediaItem.id === newMediaLibraryItem.id);
      if (existing) return;
      state.mediaLibraryItems.push(newMediaLibraryItem);
    },
  },
};
