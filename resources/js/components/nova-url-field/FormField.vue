<script>
export default {
  props: ['resourceName', 'field'],

  beforeCreate() {
    this.$router.push(`/resources/media`);
  },
};
</script>
