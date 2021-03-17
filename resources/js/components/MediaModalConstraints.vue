<template>
  <div
    v-if="currentCollection && currentCollectionData.constraints && currentCollectionData.constraints.length"
    class="media-rules"
  >
    <div class="media-rule-label">Constraints</div>
    <div v-for="constraintKey of Object.keys(currentCollectionData.constraints)" class="single-constraint">
      <span class="constraint-label">
        {{ parseConstraintKey(constraintKey) }}
      </span>
      <span class="value">
        {{ parseConstraintValue(constraintKey, currentCollectionData.constraints[constraintKey]) }}
      </span>
    </div>
  </div>
</template>

<script>
export default {
  props: ['field', 'currentCollectionData', 'currentCollection'],

  methods: {
    parseConstraintKey(key) {
      if (key === 'mimetypes') {
        return 'MIME Types';
      }

      return key.replace(/_/g, ' ');
    },

    parseConstraintValue(key, value) {
      if (key === 'mimetypes' && Array.isArray(value)) {
        let newValue = value[0];

        for (const valueKey in value) {
          if (valueKey == 0) continue;
          newValue += `, ${value[valueKey]}`;
        }

        return newValue;
      }

      const dimensions = ['height', 'width', 'min_height', 'min_width', 'max_height', 'max_width'];

      if (dimensions.indexOf(key) !== -1) {
        return `${value}px`;
      }

      return value;
    },
  },
};
</script>
