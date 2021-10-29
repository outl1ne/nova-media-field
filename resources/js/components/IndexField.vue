<template>
  <div class="media-index-preview" v-if="files && files.length">
    <media-preview :ordering="false" :files="files" :multiple="multipleSelect" />
  </div>

  <div v-else>&mdash;</div>
</template>

<script>
function isString(value) {
  return typeof value === 'string' || value instanceof String;
}

export default {
  props: ['resource', 'resourceName', 'resourceId', 'field'],

  data: () => ({
    files: [],
  }),

  computed: {
    multipleSelect() {
      return this.field.multiple;
    },
  },

  mounted() {
    if (!this.field.value || this.field.value === '') {
      return;
    }

    axios
      .get('/api/media/find', {
        params: { ids: this.getInitialValue() },
      })
      .then(response => {
        this.files = response.data.map(file => ({
          data: file,
          processed: true,
          uploading: false,
          uploadProgress: 0,
        }));
      });
  },

  methods: {
    getInitialValue() {
      if (Array.isArray(this.field.value)) return this.field.value;
      if (isString(this.field.value)) return this.field.value.split(',');
      return [this.field.value];
    },
  },
};
</script>
<style lang="scss">
.media-index-preview {
  .preview-container {
    margin-bottom: 0;
    border: 0;
    min-height: 0;

    .uploaded-file-name {
      display: none;
    }

    .uploaded-file {
      height: 48px;
      width: 48px;
    }

    .thumbnail-container {
      margin-bottom: 0;
      height: 100%;
    }
  }

  .preview-container.multiple-preview {
    max-height: none;
    overflow: hidden;
    flex-wrap: wrap;
  }
}
</style>
