<template>
    <loading-view :loading="loading">
        <div>
            <slot>
                <heading :level="1" class="mb-3">{{ panel.name }}</heading>
            </slot>
            <card class="mb-6 py-3 px-6">
                <div class="flex mb-4">
                    <div class="w-1/2 h-auto before">
                        <heading :level="3" class="mb-3 text-center">
                        <span
                                class="inline-block bg-50 rounded-full px-3 py-1 text-sm font-semibold  mr-2">previous post</span>
                        </heading>
                        <card class="mb-6 py-3 px-6">
                            <component
                                    :class="{ 'remove-bottom-border': index == previousFields.length - 1 , 'previous' : changed(field)}"
                                    :key="index"
                                    v-for="(field, index) in previousFields"
                                    :is="resolveComponentName(field)"
                                    :resource-name="auditableResourceName"
                                    :resource-id="auditableResourceId"
                                    :field="field"
                            />
                        </card>
                    </div>
                    <div class="w-1/2  h-auto">
                        <heading :level="3" class="mb-3 text-center">
                        <span
                                class="inline-block bg-50 rounded-full px-3 py-1 text-sm font-semibold  mr-2">current post</span>
                        </heading>
                        <card class="mb-6 py-3 px-6">
                            <component
                                    :class="{ 'remove-bottom-border': index == currentFields.length - 1 , 'current' : changed(field)}"
                                    :key="index"
                                    v-for="(field, index) in currentFields"
                                    :is="resolveComponentName(field)"
                                    :resource-name="auditableResourceName"
                                    :resource-id="auditableResourceId"
                                    :field="field"
                            />
                        </card>
                    </div>
                </div>
            </card>
        </div>
    </loading-view>
</template>

<script>
    export default {
        props: ['resourceName', 'resourceId', 'resource', 'panel'],
        data() {
            return {
                currentFields: [],
                previousFields: [],
                changes: [],
                loading: true,
            };
        },
        computed: {
            auditableResourceName() {
                return this.getAuditableField.resourceName;
            },
            auditableResourceId() {
                return this.getAuditableField.morphToId;
            },
            getAuditableField() {
                return this.resource.fields.find(field => {
                    return field.attribute === "auditable" && field.morphToRelationship === "auditable";
                })
            }
        },
        mounted() {
            axios.get(`/nova-vendor/nova-auditing/${this.auditableResourceName}/` + this.resourceId)
                .then(({data: {current, previous, changes}}) => {
                    this.currentFields = current;
                    this.previousFields = previous;
                    this.changes = changes;
                }).finally(() => {
                this.loading = false;
            });
        },
        methods: {
            /**
             * Resolve the component name.
             */
            resolveComponentName(field) {
                return field.prefixComponent ? 'detail-' + field.component : field.component
            },

            changed(field) {
                return !!this.changes.find(item => field.attribute === item);
            }
        },
    }
</script>
