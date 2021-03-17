<template>
  <div class="edit-image-container">
    <div class="form-field">
      <div class="thumbnail-container">
        <img v-if="file.mime_type.indexOf('image') === 0" :src="file.url" />
        <video v-if="file.mime_type.indexOf('video') === 0" controls>
          <source :src="file.url" :type="file.mime_type" />
        </video>
      </div>
    </div>

    <div class="separator" />

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
        v-model="file.title"
        type="text"
        class="w-full form-control form-input form-input-bordered"
        @input="onDataUpdate"
      />
    </div>

    <div class="form-field">
      <label class="text-80 leading-tight">Alt text</label>
      <textarea
        v-model="file.alt"
        rows="2"
        class="w-full form-control form-input form-input-bordered py-3 h-auto"
        @input="onDataUpdate"
      />
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

export default {
  props: {
    file: {
      type: Object,
      default: void 0,
      required: false,
    },
  },

  data: () => ({
    //
  }),

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
  height: 250px;
  margin-bottom: 15px;

  video {
    width: 100%;
  }
}

img {
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
