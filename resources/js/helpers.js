/**
 * Creates uniform media library item
 *
 * @param {Object} model - "Media" model
 */
export function createMediaLibraryItemFromModel(model) {
  return {
    id: model.id,
    uploaded: true,
    thumbnail: model.image_sizes?.thumbnail?.url || '',
    model,
  };
}

/**
 * Creates uniform media library items from FileList
 *
 * @param {FileList} fileList - FileList array
 */
export function createMediaLibraryItemFromFileList(fileList, options = {}) {
  const { collection } = options
  return {
    id: this.getNextMediaItemId(),
    uploaded: false,
    thumbnail: '',
    fileList,
    progress: 0,
    uploading: false,
    processing: false,
    collection
  };
}

export function createUploadRequestPayloads(files) {
  const maxPayloadSizeKb = (+window.config.mediaLibrary.uploadMaxFilesizeMb) * 990000; // Leave 1% buffer
  const uploadRequestPayloads = [];

  let groupIndex = 0;
  let groupTotalSize = 0;
  for (const file of files.fileList) {
    if (maxPayloadSizeKb < groupTotalSize + (+file.size)) { // Request upload limit reached, create new group request
      groupIndex++;
      groupTotalSize = 0;
    }
    groupTotalSize += file.size
    if (!uploadRequestPayloads[groupIndex]) uploadRequestPayloads[groupIndex] = []
    uploadRequestPayloads[groupIndex].push(file)
  }

  return uploadRequestPayloads
}

export function debounce(func, wait, immediate) {
  let timeout;

  return function (...args) {
    const later = () => {
      timeout = null;
      if (!immediate) func.call(this, ...args);
    };

    const callNow = immediate && !timeout;

    clearTimeout(timeout);

    timeout = setTimeout(later, wait);
    if (callNow) func.call(this, ...args);
  };
}

export function throttle(func, wait, options = {}) {
  let context, args, result;
  let timeout = null;
  let previous = 0;

  const later = () => {
    previous = options.leading === false ? 0 : Date.now();
    timeout = null;
    result = func.apply(context, args);
    if (!timeout) context = args = null;
  };

  return function() {
    const now = Date.now();
    if (!previous && options.leading === false) previous = now;
    const remaining = wait - (now - previous);
    context = this;
    args = arguments;

    if (remaining <= 0 || remaining > wait) {
      if (timeout) {
        clearTimeout(timeout);
        timeout = null;
      }
      previous = now;
      result = func.apply(context, args);
      if (!timeout) context = args = null;
    } else if (!timeout && options.trailing !== false) {
      timeout = setTimeout(later, remaining);
    }

    return result;
  };
}
