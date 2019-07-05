<template>
    <panel-item :field="field">

        <template slot="value">
            <media-preview :ordering="false" :files="files" :multiple="multipleSelect"/>
        </template>

    </panel-item>
</template>

<script>
  export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data: () => ({
      files: []
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

      axios.get('/api/media/find', {
        params: {
          ids: isString(this.field.value) ? this.field.value.split(',') : this.field.value
        },
      }).then(response => {
        this.files = response.data.map(file => ({
          data: file,
          processed: true,
          uploading: false,
          uploadProgress: 0
        }));
      });
    }
  };
</script>
