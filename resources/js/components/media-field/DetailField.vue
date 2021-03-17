<template>
  <panel-item :field="field">
    <template slot="value">
      <media-preview :ordering="false" :files="files" :multiple="multipleSelect" />
    </template>
  </panel-item>
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
