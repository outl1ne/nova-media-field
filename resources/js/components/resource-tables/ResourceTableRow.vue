<template>
  <tr :dusk="resource['id'].value + '-row'">
    <!-- Fields -->
    <td v-for="field in resource.fields">
      <component
        :is="'index-' + field.component"
        :class="`text-${field.textAlign}`"
        :resource-name="resourceName"
        :via-resource="viaResource"
        :via-resource-id="viaResourceId"
        :field="field"
      />
    </td>
  </tr>
</template>

<script>
export default {
  props: [
    'testId',
    'deleteResource',
    'restoreResource',
    'resource',
    'resourcesSelected',
    'resourceName',
    'relationshipType',
    'viaRelationship',
    'viaResource',
    'viaResourceId',
    'viaManyToMany',
    'checked',
    'actionsAreAvailable',
    'shouldShowCheckboxes',
    'updateSelectionStatus',
  ],

  data: () => ({
    deleteModalOpen: false,
    restoreModalOpen: false,
  }),

  methods: {
    /**
     * Select the resource in the parent component
     */
    toggleSelection() {
      this.updateSelectionStatus(this.resource);
    },

    openDeleteModal() {
      this.deleteModalOpen = true;
    },

    confirmDelete() {
      this.deleteResource(this.resource);
      this.closeDeleteModal();
    },

    closeDeleteModal() {
      this.deleteModalOpen = false;
    },

    openRestoreModal() {
      this.restoreModalOpen = true;
    },

    confirmRestore() {
      this.restoreResource(this.resource);
      this.closeRestoreModal();
    },

    closeRestoreModal() {
      this.restoreModalOpen = false;
    },
  },
};
</script>
