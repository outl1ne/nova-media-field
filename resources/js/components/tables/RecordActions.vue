<template>
    <div>
        <a @click="() => field.value.onReportToEmailClick(field)">
            <span class="btn btn-default btn-primary leading-tight ml-2 px-3 text-sm" style="height: 24px; line-height: 24px; cursor: pointer;">Send to email</span>
        </a>
        <a :href="`/nova-vendor/nova-ecommerce/nvec-download-export-file/${field.value.id}`">
            <span class="btn btn-default btn-primary leading-tight ml-2 px-3 text-sm" style="height: 24px; line-height: 24px; cursor: pointer;">Download</span>
        </a>
    </div>
</template>
<script>
    export default {
      props: ['field'],
    }
</script>
