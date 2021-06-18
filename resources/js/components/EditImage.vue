<template>
  <div class="edit-image-container">
    <div class="form-field">
      <div class="thumbnail-container">
        <mime-type-icon :src="file.url" :mime-type="file.mime_type" :show-video="true" />
      </div>
    </div>

    <div class="separator"></div>

    <div class="form-field">
      <div>
        <label class="text-80 leading-tight"> File name </label>
      </div>
      <div class="file-name">
        {{ file.file_name }}
      </div>
    </div>

    <div class="form-field">
      <label class="text-80 leading-tight">URL</label>
      <input
        type="text"
        readonly="readonly"
        class="w-full form-control form-input form-input-bordered"
        :value="file.url"
      />
    </div>

    <div class="form-field">
      <label class="text-80 leading-tight">Title</label>
      <input
        type="text"
        class="w-full form-control form-input form-input-bordered"
        v-on:input="onDataUpdate"
        v-model="file.title"
      />
    </div>

    <div class="form-field">
      <label class="text-80 leading-tight">Alt text</label>
      <textarea
        v-on:input="onDataUpdate"
        v-model="file.alt"
        rows="2"
        class="w-full form-control form-input form-input-bordered py-3 h-auto"
      ></textarea>
    </div>

    <div class="label-field">
      <label class="text-80 leading-tight">
        <span>Collection:</span>
        <span>{{ file.collection_name || 'none' }}</span>
      </label>
    </div>
  </div>
</template>

<script>
import debounce from './../debounce';
import MissingFileIcon from "../icons/MissingFileIcon";
import DocumentIcon from "../icons/DocumentIcon";
import MimeTypeIcon from "./MimeTypeIcon";

export default {
  props: {
    file: {
      type: Object,
      default: void 0,
      required: false,
    },
  },

  components: {
    MimeTypeIcon,
    MissingFileIcon,
    DocumentIcon
  },

  data: () => ({
    imageFileMissing: false
  }),

  watch: {
    file() {
      this.imageFileMissing = false
    }
  },

  methods: {
    onDataUpdate() {
      this.updateImageData(this.file);
    },

    updateImageData: debounce(file => {
      axios.post('/api/media/update', {
        id: file.id,
        title: file.title,
        alt: file.alt,
      });
    }, 500),
  },
};
</script>

<style lang="scss" scoped>
.file-name {
  font-weight: bold;
  color: #4099de;
}

.thumbnail-container {
  position: relative;
  width: 100%;
  margin-bottom: 15px;
  padding-bottom: 40%;
}

img, video {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  object-fit: contain;
}

.form-field {
  padding: 10px 20px;
}

.label-field {
  padding: 10px 20px;

  > label {
    display: block;
    padding: 2px 0;

    span:nth-child(1) {
      font-weight: bold;
    }
  }
}
</style>
