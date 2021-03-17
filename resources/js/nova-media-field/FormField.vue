<template>
  <div>
    <default-field :field="field" full-width-content :show-help-text="showHelpText">
      <template slot="field">
        <FieldButtons />
      </template>
    </default-field>
  </div>
</template>
<script>
import FieldButtons from '../components/common/FieldButtons';
import { FormField, HandlesValidationErrors } from 'laravel-nova';
export default {
  components: { FieldButtons },

  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],
};
</script>
