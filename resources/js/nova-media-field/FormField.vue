<template>
  <div>
    <media-browsing-modal />

    <default-field :field="field" full-width-content :show-help-text="showHelpText">
      <template slot="field">
        <media-preview />

        <FieldButtons />
      </template>
    </default-field>
  </div>
</template>
<script>
import FieldButtons from '../components/common/FieldButtons';
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import MediaPreview from '../components/MediaPreview';
import MediaBrowsingModal from '../components/modals/MediaBrowsingModal';
export default {
  components: { FieldButtons, MediaPreview, MediaBrowsingModal },

  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],
};
</script>
