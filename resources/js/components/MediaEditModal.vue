<template>
  <od-modal v-if="isModalOpen" ref="isModalOpen" :name="'isModalOpen'" :align="'flex justify-end'" width="1315">
    <div slot="container">
      <div class="modal-header flex flex-wrap justify-between mb-6">
        <h2 class="text-90 font-normal text-xl">Edit media</h2>
      </div>
      <edit-image v-if="file" :file="file.data" />
      <div v-else class="loader-container">
        <div class="loader" />
        <div class="small-loader" />
      </div>
    </div>
    <div slot="buttons" class="w-full flex">
      <div class="flex w-full justify-end">
        <button type="button" class="btn btn-default btn-primary mr-3" @click.prevent="closeModalAndSave">
          {{ __('Apply and close') }}
        </button>
        <button type="button" class="btn btn-default btn-danger" @click.prevent="closeModal">
          {{ __('Close') }}
        </button>
      </div>
    </div>
  </od-modal>
</template>

<script>
import debounce from './../debounce';

export default {
  props: ['isModalOpen', 'file'],

  methods: {
    openModal() {
      this.$emit('update:isModalOpen', true);
    },

    closeModal() {
      this.listenUploadArea = false;
      this.$emit('update:isModalOpen', false);
    },

    closeModalAndSave() {
      this.$emit('update:isModalOpen', false);
      this.$toasted.show('Update successful', { type: 'success' });
    },
  },
};
</script>
