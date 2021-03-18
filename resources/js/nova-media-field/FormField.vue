<template>
  <div>
    <default-field :field="field" full-width-content>
      <template slot="field">
        <media-preview />
        <field-buttons />
      </template>
    </default-field>
  </div>
</template>
<script>
import FieldButtons from '../components/common/FieldButtons';
import MediaPreview from '../components/MediaPreview';

export default {
  components: { FieldButtons, MediaPreview },

  provide() {
    return {
      field: this.field,
    };
  },

  props: {
    field: {
      type: Object,
      default: null,
    },
  },
};
</script>
