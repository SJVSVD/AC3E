<template>
    <transition name="fade">
        <div class="reusable-modal" v-if="isVisible">
            <div class="window">
                <slot></slot>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    name: 'reusableModal',
    data: () => ({
        isVisible: false,
    }),
    methods: {
        open() {
            this.isVisible = true
        },

        close() {
            this.isVisible = false
        },
    },
}
</script>
