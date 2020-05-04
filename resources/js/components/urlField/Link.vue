<template>
  <a class="no-underline dim font-bold text-80 hover:text-primary mr-2" :href="url" target="_blank">
    <slot />
  </a>
</template>

<script>
export default {
  props: ['url'],
};
</script>
