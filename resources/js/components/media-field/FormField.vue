<template>
  <default-field :field="field" :errors="errors" fullWidthContent :show-help-text="showHelpText">
    <field-buttons />
  </default-field>
</template>
<script>
import FieldButtons from '../common/FieldButtons';
export default {
  components: { FieldButtons },
};
</script>
