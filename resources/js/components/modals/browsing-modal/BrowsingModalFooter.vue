<template>
  <div>
    <button type="button" class="btn btn-default btn-primary whitespace-no-wrap">
      {{ __('Upload files') }}
    </button>
    <button type="button" class="btn btn-default btn-primary whitespace-no-wrap">
      {{ __('Back') }}
    </button>

    <div class="flex w-full justify-end">
      <button type="button" class="btn btn-default btn-primary mr-3">
        {{ __('Apply and close') }}
      </button>
      <button type="button" class="btn btn-default btn-danger">
        {{ __('Close') }}
      </button>
    </div>
  </div>
</template>

<script>
export default {};
</script>
