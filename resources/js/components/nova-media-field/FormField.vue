<template>
  <div>
    <default-field
      :field="field"
      full-width-content
    >
      <template slot="field">
        <media-preview
          v-if="hasValue"
          :field="field"
        />
        <form-field-buttons :field="field" />
      </template>
    </default-field>
  </div>
</template>
<script>
import FormFieldButtons from './FormFieldButtons';
import MediaPreview from '../MediaPreview';

export default {
  components: { FormFieldButtons, MediaPreview },

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
