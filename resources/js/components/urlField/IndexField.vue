<template>
  <a :href="field.value" target="_blank">{{ field.value }}</a>
</template>

<script>
export default {
  props: ['resourceName', 'field'],

  mounted() {
    document.querySelectorAll('table tr td:last-child span:nth-child(2)').forEach(node => {
      node.parentNode.removeChild(node);
    });
  },
};
</script>
