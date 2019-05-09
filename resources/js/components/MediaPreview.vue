<template>

    <div :class="`preview-container ${multiple ? 'multiple-preview' : ''}`">

        <div class="media-preview" v-if="files && files.length">

            <uploaded-file v-if="multiple" v-for="file in files" v-bind:key="file" :file="file.data" />

            <uploaded-file v-if="!multiple" :file="files[0].data" />

        </div>

    </div>

</template>

<script>
    export default {
        props: {
            multiple: {
                type: Boolean,
                default: false,
                required: false,
            },
            files: {
                type: Array,
                default: [],
                required: false,
            }
        },
    }
</script>

<style lang="scss">

    .preview-container {
        padding: 5px;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid #eef1f4;
        height: 105px;
        width: 105px;
        overflow: hidden;

        &.multiple-preview {
            min-height: 105px;
            height: auto;
            max-height: 205px;
            width: 100%;
            overflow-y: auto;
        }

        &::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.1);
            background-color: #fff;
            border-radius: 3px;
        }

        &::-webkit-scrollbar {
            width: 6px;
            border-radius: 3px;
        }

        &::-webkit-scrollbar-thumb {
            background-color: rgba(#000, .1);
            border-radius: 3px;
        }

        .media-preview {
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
            max-height: 205px;
        }

        .uploaded-file {
            width: 90px;
            height: 90px;
            margin: 2.5px;
        }
    }

</style>
