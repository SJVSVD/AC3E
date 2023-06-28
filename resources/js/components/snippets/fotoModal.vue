<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Ver Foto 
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                        <slot name="body">
                            <div class="text-center">
                                <img style="max-width:90%; max-height:90%;" v-bind:src="'data:image/jpeg;base64,'+detalle1" />
                            </div>
                        </slot>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        data: () => ({
            detalle1: null,
        }),
        props:{
            detalle: Object,
        },
        created(){
            this.detalle1 = this.detalle;
        },
    }
</script>
