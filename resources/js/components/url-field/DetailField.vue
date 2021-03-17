<template>
  <panel-item :field="field">
    <template slot="value">
      <div class="flex items-center">
        <Link :url="field.value">
          {{ field.value }}
        </Link>
        <CopyIcon :text="field.value" />
      </div>
    </template>
  </panel-item>
</template>

<script>
import Link from './Link';
import CopyIcon from './CopyIcon';

export default {
  props: ['resourceName', 'field'],
  components: { Link, CopyIcon },
  mounted() {
    const editButtonEl = document.querySelector('.content').querySelector('[dusk="edit-resource-button"]');

    if (editButtonEl) {
      editButtonEl.parentNode.removeChild(editButtonEl);
    }
  },
};
</script>
