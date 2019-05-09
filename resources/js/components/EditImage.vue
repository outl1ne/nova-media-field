<template>
    <div class="edit-image-container">

        <div class="form-field">
            <div class="thumbnail-container">
                <img :src="file.url" />
            </div>
        </div>

        <div class="separator"></div>

        <div class="form-field">
            <div>
                <label class="text-80 leading-tight">
                    URL
                </label>
            </div>
            <div>
                <input type="text" readonly="readonly" class="w-full form-control form-input form-input-bordered" :value="file.url">
            </div>
        </div>

        <div class="form-field">
            <div>
                <label class="text-80 leading-tight">
                    Alt text
                </label>
            </div>
            <div>
                <textarea v-on:input="onDataUpdate" v-model="file.alt" rows="3" class="w-full form-control form-input form-input-bordered py-3 h-auto"></textarea>
            </div>
        </div>


    </div>
</template>


<script>
    import debounce from './../debounce';

    export default {
        props: {
            file: {
                type: Object,
                default: void 0,
                required: false
            }
        },

        data: () => ({
            //
        }),

        methods: {
            onDataUpdate() {
                this.updateImageData(this.file);
            },

            updateImageData: debounce(function(file) {
                axios.post( '/api/media/update',
                    {
                        id: file.id,
                        alt: file.alt
                    }
                )
            }, 500)

        },
    };
</script>

<style scoped>


    .thumbnail-container {
        position: relative;
        width: 100%;
        height: 120px;
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
</style>
