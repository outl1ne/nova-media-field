<template>
    <panel-item :field="field">

        <template slot="value">
            <media-preview :files="files" :multiple="multipleSelect" />
        </template>

    </panel-item>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data: () => ({
        files: []
    }),

    computed: {
        multipleSelect() {
            return this.field.multiple
        },
    },

    mounted() {

        if (!this.field.value || this.field.value === '') {
            return;
        }

        axios.get( '/api/media/find', {
            params: {
                ids: this.field.value.split(',')
            },
        }).then(response => {
            this.files = response.data.map(file => ({
                data: file,
                processed: true,
                uploading: false,
                uploadProgress: 0
            }));
        });
    }
}
</script>
