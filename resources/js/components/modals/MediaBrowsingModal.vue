<template>
  <modal width="1315">
    <div slot="container">
      <browsing-modal-header />

      <browsing-modal-constraints />
    </div>
    <div slot="buttons" class="w-full flex">
      <browsing-modal-footer />
    </div>
  </modal>
</template>

<script>
import Modal from '../common/Modal';
import BrowsingModalHeader from './browsing-modal/BrowsingModalHeader';
import BrowsingModalFooter from './browsing-modal/BrowsingModalFooter';
import BrowsingModalConstraints from './browsing-modal/BrowsingModalConstraints';

export default {
  components: { Modal, BrowsingModalHeader, BrowsingModalFooter, BrowsingModalConstraints },
};
</script>
