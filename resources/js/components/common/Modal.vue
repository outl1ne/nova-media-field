<template>
  <div class="od-modal-container">
    <modal>
      <div
        class="bg-white rounded-lg shadow-lg od-modal"
        :style="style"
      >
        <div class="p-6 pb-2 w-full">
          <slot name="container" />
        </div>

        <div class="bg-30 px-6 py-3 flex browsing-modal-footer">
          <slot name="buttons" />
        </div>
      </div>
    </modal>
  </div>
</template>

<script>
export default {
  props: {
    name: {
      type: String,
      default: 'modal',
      required: false,
    },
    width: {
      type: Number,
      default: 600,
      required: false,
    },
    align: {
      type: String,
      default: '',
      required: false,
    },
  },

  computed: {
    style() {
      return 'max-width: ' + this.width + 'px;';
    },
  },
};
</script>

<style>
.od-modal-container .modal > div {
  padding: 50px 25px;
}

.od-modal-container .modal > div > div {
  width: 100%;
  display: flex;
}

.od-modal {
  overflow: hidden;
  position: relative;
  width: 100%;
  margin: 0 auto;
}
</style>
