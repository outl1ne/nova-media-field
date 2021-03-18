<template>
  <div
    :class="`uploaded-file ${hasFocus ? 'focus' : ''} ${hasSelected ? 'selected' : ''}`"
  >
    i am file {{ id }}
  </div>
</template>
<script>
export default {
  props: {
    id: {
      type: [String, Number],
      required: true
    }
  }
}
</script>
