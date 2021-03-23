<template>
  <div
    class="media-rules"
  >
    <div
      class="single-constraint"
    >
      <span class="constraint-label">
        Max file size
      </span>
      <span class="value">
        {{ config.uploadMaxFilesizeMb }} MB
      </span>
    </div>
    <div
      v-for="constraintKey of Object.keys(constraints)"
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
import {getExtension} from 'mime/lite'

const keyToLabel = {
  mimetypes: 'Allowed file types'
}

const mimeTypeToExtension = {}

const keyValueResolvers = {
  mimetypes: arrayToString
}

// Converts array of string values into one comma separated string
function arrayToString(arr, separator = ', ') {
  if (Array.isArray(arr)) {
    const resolvedValues = []

    for (const arrKey in arr) {
      const item = arr[arrKey]
      const value = mimeTypeToExtension?.[item] || getExtension(item) || item
      if (resolvedValues.indexOf(value) !== -1) continue; // Skip already existing value
      resolvedValues.push(value)
    }

    return resolvedValues.join(separator);
  }

  return ''
}

export default {

  props: {
    field: {
      type: Object,
      default: null
    }
  },

  computed: {
    config() {
      return window.config.mediaLibrary
    },

    constraints() {
      return this.config.collections?.[this.field.displayCollection]?.constraints || {}
    },
  },

  methods: {
    // Formats constraint keys for better readability
    parseConstraintKey(key) {
      if (keyToLabel[key]) return keyToLabel[key]
      return key.replace(/_/g, ' ');
    },

    // Formats constraint values for better readability
    parseConstraintValue(key) {
      const value = this.constraints[key]
      const resolveValue = keyValueResolvers[key];
      if (typeof resolveValue === 'function') return resolveValue(value)
      return value;
    }
  }
};
</script>
<style lang="scss" scoped>
.media-rules {
  padding: 10px 10px 10px 0;
  display: flex;
  justify-content: flex-start;
  align-content: center;
  align-items: center;
  border-bottom: 1px dashed rgba(0, 0, 0, 0.2);
  border-top: 1px dashed rgba(0, 0, 0, 0.2);
  flex-wrap: wrap;
  width: 100%;
  margin: 8px 0px;

  .media-rule-label {
    width: 100%;
    padding-bottom: 5px;
  }

  .single-constraint {
    display: flex;
    flex-flow: column;
    padding: 0 16px;
    font-size: 14px;
    border-left: 1px dashed rgba(0, 0, 0, 0.2);

    &:first-child {
      padding-left: 0;
      border-left: 0;
    }

    > span:nth-child(2) {
      font-weight: bold;
    }
  }
}

</style>
