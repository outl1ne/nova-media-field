<template>
  <div
    v-if="hasConstraints"
    class="media-rules"
  >
    <div
      class="single-constraint"
    >
      <span class="constraint-label">
        Max file size
      </span>
      <span class="value">
        {{ field.maxFileSize }} MB
      </span>
    </div>
    <div
      v-for="constraintKey of Object.keys(collection.constraints)"
      :key="constraintKey"
      class="single-constraint"
    >
      <span class="constraint-label">
        {{ parseConstraintKey(constraintKey) }}
      </span>
      <span class="value">
        {{ parseConstraintValue(constraintKey) }}
      </span>
    </div>
  </div>
</template>
<script>
const keyToLabel = {
  mimetypes: 'Mime types'
}

// Converts array of string values into one comma separated string
function arrayToString(items, separator = ', ') {
  if (Array.isArray(items)) {
    let newValue = items[0]; // First item without separator

    for (const valueKey in items) {
      if (!valueKey) continue; // Skip first item
      newValue += `${separator}${items[valueKey]}`;
    }

    return newValue;
  }

  return items
}

const keyValueResolvers = {
  mimetypes: arrayToString
}

export default {

  props: {
    field: {
      type: Object,
      default: null
    }
  },

  computed: {
    hasConstraints() {
      return this.collection && this.collection.constraints && Object.keys(this.collection.constraints).length;
    },

    collection() {
      return this.field.collections[this.field.displayCollection]
    }
  },

  methods: {
    // Formats constraint keys for better readability
    parseConstraintKey(key) {
      if (keyToLabel[key]) return keyToLabel[key]
      return key.replace(/_/g, ' ');
    },

    // Formats constraint values for better readability
    parseConstraintValue(key) {
      const value = this.collection.constraints[key]
      const resolveValue = keyValueResolvers[key];
      if (typeof resolveValue === 'function') return resolveValue(value)
      return value;
    }
  }
};
</script>
<style lang="scss" scoped>

.media-rules {
  padding: 0 0 10px 0;
  display: flex;
  justify-content: flex-start;
  align-content: center;
  align-items: center;
  border-bottom: 1px dashed rgba(0, 0, 0, 0.1);
  width: calc(100% - 300px);
  margin-bottom: 10px;
  margin-top: -15px;
  flex-wrap: wrap;

  .media-rule-label {
    width: 100%;
    padding-bottom: 5px;
  }

  .single-constraint {
    display: flex;
    flex-flow: column;
    padding-right: 35px;
    font-size: 14px;

    > span:nth-child(1) {
      text-transform: capitalize;
    }

    > span:nth-child(2) {
      font-weight: bold;
    }
  }
}

</style>
