<template>
  <div id="media-dropzone" :class="`flex mb-6`">
    <div ref="imgCollectionRef" class="img-collection">
      <div class="empty-message">
        <p>There are currently no media files in this library</p>
        <p>Drag and drop files here to upload them</p>
      </div>

      <div class="empty-message">
        <p>Drag and drop files here to upload them</p>
      </div>
    </div>

    <div class="image-editor">
      <edit-image />
    </div>

    <div>
      <p>
        <svg class="fill-current w-4 h-4 mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
          <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" />
        </svg>
      </p>
      <p>To upload files you can simply drag and drop them in the area or click it to open file browser.</p>
      <input ref="uploadFiles" type="file" name="media" class="input-dropzone" multiple />
    </div>
  </div>
</template>
<script>
import EditImage from '../../EditImage';

export default {
  components: { EditImage },
};
</script>
