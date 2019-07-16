<template>
  <panel-item :field="field">
    <template slot="value">
      <a :href="field.value" target="_blank">{{ field.value }}</a>
    </template>
  </panel-item>
</template>

<script>
export default {
  props: ['resourceName', 'field'],

  mounted() {
    const editButtonEl = document.querySelector('.content').querySelector('[dusk="edit-resource-button"]');

    if (editButtonEl) {
      editButtonEl.parentNode.removeChild(editButtonEl);
    }
  },
};
</script>
