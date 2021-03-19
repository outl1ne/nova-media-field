<template>
  <div class="modal-header flex flex-wrap justify-between mb-6">
    <h2 class="text-90 font-normal text-xl">
      <span v-if="!uploadOnly">Browse media library</span>
      <span v-if="uploadOnly">Upload media</span>
    </h2>
  </div>
</template>

<script>
export default {
  props: ['uploadOnly']
};
</script>
