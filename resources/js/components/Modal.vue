<template>
  <portal to="modals" :name="name">
    <transition name="fade">
      <div class="od-modal-container">
        <modal>
          <div class="bg-white rounded-lg shadow-lg od-modal" :style="style">
            <div class="p-8"><slot name="container"></slot></div>

            <div class="bg-30 px-6 py-3 flex">
              <slot name="buttons"></slot>
            </div>
          </div>
        </modal>
      </div>
    </transition>
  </portal>
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

  data: () => ({
    //
  }),

  computed: {
    style() {
      return 'max-width: ' + this.width + 'px;';
    },
  },
};
</script>

<style lang="scss">
.od-modal-container .modal > div > div {
  padding: 0 20px;
  width: 100%;
}

.od-modal {
  overflow: hidden;
  position: relative;
  width: 100%;
  margin: 0 auto;
}
</style>
