<template>
  <div
    class="loader-container"
    :class="center && 'centered'"
  >
    <div class="wrap">
      <div class="loader" />
      <div class="small-loader" />
    </div>
    <div
      v-if="text"
      class="text"
    >
      {{ text }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    center: {
      type: Boolean,
      default: false
    },
    text: {
      type: String,
      default: ''
    }
  }
}
</script>

<style lang="scss">

@keyframes rotate {
  0% {
    transform: translate(-50%, -50%) rotate(0);
  }
  100% {
    transform: translate(-50%, -50%) rotate(360deg);
  }
}

.loader-container {
  width: 100%;
  position: relative;

  &.centered {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-flow: column;
  }

  .text {
    font-size: 10px;
    margin-top: 12px;
  }

  .wrap {
    position: relative;
    width: 36px;
    height: 36px;
  }

  .loader {
    position: absolute;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 3px solid transparent;
    border-left-color: #4099de;
    border-right-color: #4099de;
    top: 50%;
    left: 50%;
    transform-origin: center;
    animation: rotate 1s linear infinite;
  }

  .small-loader {
    position: absolute;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 3px solid transparent;
    border-left-color: #4099de;
    border-right-color: #4099de;
    top: 50%;
    left: 50%;
    transform-origin: center;
    animation: rotate 1s linear reverse infinite;
  }
}

</style>
