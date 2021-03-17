<template>
  <div class="flex w-full justify-end items-center mx-3">
    <component :is="component" v-if="hasComponent" />
  </div>
</template>

<script>
export default {
  props: ['resourceName', 'resourceId'],
  computed: {
    component() {
      return this.resourceName + '-index-toolbar';
    },
    hasComponent() {
      return this.component in this.$options.components;
    },
  },
};
</script>
