<template>
    <div :class="`uploaded-file ${active ? 'active' : ''} ${selected ? 'selected' : ''}`">

        <div v-if="progress !== -1" class="upload-progress">
            <div class="progress-bar">
                <div class="progress" :style="`width: ${progress}%`"></div>
            </div>
        </div>

        <div class="thumbnail-container" v-if="file !== void 0">
            <img draggable="false" :src="file.image_sizes.thumbnail.url" />
        </div>

        <div class="checked-box" v-if="selected">
            <checkbox :checked="selected" />
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            progress: {
                type: Number,
                default: -1,
                required: false,
            },
            file: {
                type: Object,
                default: void 0,
                required: false
            },
            active: {
                type: Boolean,
                default: false,
                required: false
            },
            selected: {
                type: Boolean,
                default: false,
                required: false
            }
        },

        data: () => ({
            //
        }),

        computed: {
        },
    };
</script>

<style lang="scss">

    .checked-box {
        position: absolute;
        top: 5px;
        right: 5px;
    }

    .uploaded-file {
        width: 150px;
        height: 150px;
        border: 1px solid #bacad6;
        border-radius: 5px;
        position: relative;
        box-shadow: 0 0 5px rgba(#4099de, 0);
        transition: box-shadow .2s, border-color .2s;
        overflow: hidden;
        cursor: pointer;

        &:hover {
            box-shadow: 0 0 5px rgba(#4099de, .5);
            border-color: #4099de;
        }

        &.selected {
            border: 3px solid #bacad6;
        }

        &.active {
            border: 3px solid #4099de;
        }

        .thumbnail-container {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            object-fit: cover;
        }

        img {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            object-fit: cover;
        }
    }

    .upload-progress {
        position: absolute;
        display: flex;
        justify-content: center;
        align-items: center;
        align-content: center;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(#000, .01);

        .progress-bar {
            position: relative;
            width: 80%;
            height: 12px;
            border: 1px solid #4099de;
            border-radius: 4px;
            background-color: #c5ccde;
            overflow: hidden;
        }

        .progress {
            position: absolute;
            height: 100%;
            top: 0;
            left: 0;
            width: 0;
            transition: width .2s;
            background-color: #4099de;
        }
    }
</style>
