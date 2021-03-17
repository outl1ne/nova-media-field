<template>
  <div class="edit-image-container">
    <div class="form-field">
      <div class="thumbnail-container" />
    </div>

    <div class="separator" />

    <div class="form-field">
      <div>
        <label class="text-80 leading-tight"> File name </label>
      </div>
      <div class="file-name" />
    </div>

    <div class="form-field">
      <label class="text-80 leading-tight">URL</label>
      <input type="text" readonly="readonly" class="w-full form-control form-input form-input-bordered" />
    </div>

    <div class="form-field">
      <label class="text-80 leading-tight">Title</label>
      <input type="text" class="w-full form-control form-input form-input-bordered" />
    </div>

    <div class="form-field">
      <label class="text-80 leading-tight">Alt text</label>
      <textarea rows="2" class="w-full form-control form-input form-input-bordered py-3 h-auto" />
    </div>

    <div class="label-field">
      <label class="text-80 leading-tight">
        <span>Collection:</span>
      </label>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style lang="scss" scoped>
.file-name {
  font-weight: bold;
  color: #4099de;
}

.thumbnail-container {
  position: relative;
  width: 100%;
  height: 250px;
  margin-bottom: 15px;

  video {
    width: 100%;
  }
}

img {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  object-fit: contain;
}

.form-field {
  padding: 10px 20px;
}

.label-field {
  padding: 10px 20px;

  > label {
    display: block;
    padding: 2px 0;

    span:nth-child(1) {
      font-weight: bold;
    }
  }
}
</style>
