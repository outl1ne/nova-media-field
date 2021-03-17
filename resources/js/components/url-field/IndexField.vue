<template>
  <div class="flex items-center">
    <Link :url="field.value">
      {{ __('Link') }}
    </Link>
    <CopyIcon :text="field.value" />
  </div>
</template>

<script>
import CopyIcon from './CopyIcon';
import Link from './Link';

export default {
  components: { CopyIcon, Link },
  props: ['resourceName', 'field'],
  mounted() {
    document.querySelectorAll('table tr td:last-child span:nth-child(2)').forEach(node => {
      node.parentNode.removeChild(node);
    });
  },
  methods: {
    async copyToClipboard() {
      await this.$copyText(this.field.value);

      this.$toasted.show(this.__('Link copied to the clipboard.'), { type: 'success' });
    },
  },
};
</script>
