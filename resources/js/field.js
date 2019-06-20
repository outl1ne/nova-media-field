
Nova.booting((Vue, router, store) => {

    Vue.component('media-preview', require('./components/MediaPreview'))
    Vue.component('edit-image', require('./components/EditImage'))
    Vue.component('uploaded-file', require('./components/UploadedFile'))
    Vue.component('od-modal', require('./components/Modal'))
    Vue.component('media-browsing-modal', require('./components/MediaBrowsingModal'))
    Vue.component('media-modal-constraints', require('./components/MediaModalConstraints'))
    Vue.component('index-media-field', require('./components/IndexField'))
    Vue.component('detail-media-field', require('./components/DetailField'))
    Vue.component('form-media-field', require('./components/FormField'))
})
