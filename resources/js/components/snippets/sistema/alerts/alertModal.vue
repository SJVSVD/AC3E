<template>
    <reusable-modal ref="reusable">
        <h2 class="mt-0">{{ title }}</h2>
        <p>{{ message }}</p>
        <div class="btns">
            <span class="btn-ok" @click="_confirm">{{ okButton }}</span>
        </div>
    </reusable-modal>
</template>

<script>
import reusableModal from './reusableModal.vue'

export default {
    name: 'ConfirmDialogue',
    components: { reusableModal },
    data: () => ({
        title: undefined,
        message: undefined,
        okButton: undefined,
        cancelButton: undefined,
        resolvePromise: undefined,
        rejectPromise: undefined,
    }),
    methods: {
        show(options = {}) {
            this.title = options.title
            this.message = options.message
            this.okButton = options.okButton
            if (options.cancelButton) {
                this.cancelButton = options.cancelButton
            }
            this.$refs.reusable.open()
            return new Promise((resolve, reject) => {
                this.resolvePromise = resolve
                this.rejectPromise = reject
            })
        },
        _confirm() {
            this.$refs.reusable.close()
            this.resolvePromise(true)
        },
        _cancel() {
            this.$refs.reusable.close()
            this.resolvePromise(false)
            //this.rejectPromise(new Error('Se canceló la acción.'))
        }
    },
}
</script>