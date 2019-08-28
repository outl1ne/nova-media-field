<template>
    <div class="edit-image-container">

        <div class="form-field">
            <div class="thumbnail-container">
                <img :src="file.url" v-if="file.mime_type.indexOf('image') === 0" />
                <video v-if="file.mime_type.indexOf('video') === 0" controls>
                    <source :src="file.url" :type="file.mime_type">
                </video>
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
                    Title
                </label>
            </div>
            <div>
                <input type="text" v-on:input="onDataUpdate" v-model="file.title" class="w-full form-control form-input form-input-bordered" />
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

        <div class="label-field">
            <label class="text-80 leading-tight">
                <span>File size:</span>
                <span>{{ parseInt(file.file_size / 1000 , 10) }} kb</span>
            </label>
            <label class="text-80 leading-tight">
                <span>Mime type:</span>
                <span>{{ file.mime_type }}</span>
            </label>
            <label v-if="file.dimensions" class="text-80 leading-tight">
                <span>Dimensions:</span>
                <span>{{ file.dimensions.width }} x {{ file.dimensions.height }}</span>
            </label>
        </div>

        <div class="label-field">
            <label class="text-80 leading-tight">
                <span>Collection:</span>
                <span>{{ file.collection_name || 'none' }}</span>
            </label>
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
                        title: file.title,
                        alt: file.alt
                    }
                )
            }, 500)

        },
    };
</script>

<style lang="scss" scoped>


    .thumbnail-container {
        position: relative;
        width: 100%;
        height: 120px;
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
