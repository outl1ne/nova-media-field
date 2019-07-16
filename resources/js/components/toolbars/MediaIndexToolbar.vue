
<script>
export default {
  props: ['resourceName', 'resourceId', 'field'],
  mounted() {
    const createButtonEl = document.querySelector('.content').querySelector('[dusk="create-button"]');
    if (createButtonEl) {
      createButtonEl.parentNode.removeChild(createButtonEl);
    }
  },
};
</script>