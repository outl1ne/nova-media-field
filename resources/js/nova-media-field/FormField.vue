<template>
  <div>
    <default-field
      :field="field"
      full-width-content
    >
      <template slot="field">
        <media-preview v-if="hasValue" />
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

  computed: {
    hasValue() {
      return isNaN(this.field.value)
    }
  }
};
</script>
