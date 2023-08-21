<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container-xs">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Change Password
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                        <slot name="body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Password: </label>
                                        <br>
                                        <input type="password" class= "form-control" v-model="edits.password">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for=""> Confirm Password: </label>
                                        <br>
                                        <input type="password" class= "form-control" v-model="edits.confirmPassword">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <slot name="footer">
                                    <a class="btn btn-continue float-end" @click="changePassword()" :disabled="buttonDisable">
                                        {{ buttonText }}
                                    </a>
                                </slot>
                            </div>
                        </slot>
                        </div>
                    </div>
                </div>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
            </div>
        </div>
    </transition>
</template>

<script>
import axios from 'axios'
import modalconfirmacion from '../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../sistema/alerts/alertModal.vue'
import {mixin} from '../../../../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
        buttonText:'Save Changes',
        buttonDisable: false,
        id: "",
        edits:{
            password: "",
            confirmPassword: "",
        },
        errors:[],
    }),
    props:{
        password1: Object,
    },
    created(){
        this.id = this.password1.id;
    },
    methods: {
        cerrarModal(){
            const elem = this.$refs.closeBtn;
            elem.click();
        },
        capitalizeFirstLetter(string) {
          return string.charAt(0).toUpperCase() + string.slice(1);
        },
        async changePassword() {
            this.errors = [];
            for (const item in this.edits){
                if(this.edits[item] === "" || this.edits[item] === null){
                    this.errors.push(item);
                }
            }

            if(this.edits.password != this.edits.confirmPassword){
                this.errors.push('diferents');
            }

            var mensaje = ""
            if (this.errors.length != 0){
                this.errors.forEach(item => {
                    if(item == 'confirmPassword'){
                        mensaje =   mensaje + "The field Confirm Password is required" + "\n" 
                    }else if(item == 'diferents'){
                        mensaje =   mensaje + "The passwords entered do not match" + "\n" 
                    }else{
                        mensaje =   mensaje + "The field " + this.capitalizeFirstLetter(item) + " is required" + "\n" 
                    }
                });
                this.toast.warning( mensaje, {
                position: "top-right",
                timeout: 5000,
                closeOnClick: true,
                pauseOnFocusLoss: true,
                pauseOnHover: true,
                draggable: true,
                draggablePercent: 0.6,
                showCloseButtonOnHover: false,
                hideProgressBar: true,
                closeButton: "button",
                icon: true,
                rtl: false
                });
            }
            if(this.errors.length === 0){
                const ok = await this.$refs.confirmation.show({
                    title: 'Change Password',
                    message: `Are you sure you want to change your password? This action cannot be undone.`,
                    okButton: 'Save',
                    cancelButton: 'Return'
                })
                if (ok) {
                    let usuario = {
                        password: this.edits.password,
                    };
                    var id = this.id;
                    axios.put(`api/changePassword/${id}`, usuario).then((result) => {
                        this.buttonText = 'Editing...';
                        this.buttonDisable = true;
                        this.toast.success("Profile edited successfully!", {
                            position: "top-right",
                            timeout: 3000,
                            closeOnClick: true,
                            pauseOnFocusLoss: true,
                            pauseOnHover: true,
                            draggable: true,
                            draggablePercent: 0.6,
                            showCloseButtonOnHover: false,
                            hideProgressBar: true,
                            closeButton: "button",
                            icon: true,
                            rtl: false
                        });
                        setTimeout(() => {this.cerrarModal();}, 1500);
                    })
                    .catch((error)=> {
                        if (error.response.status == 422){
                            this.errors = error.response.data.errors;
                            this.toast.warning('Existe un valor inválido.', {
                                position: "top-right",
                                timeout: 3000,
                                closeOnClick: true,
                                pauseOnFocusLoss: true,
                                pauseOnHover: true,
                                draggable: true,
                                draggablePercent: 0.6,
                                showCloseButtonOnHover: false,
                                hideProgressBar: true,
                                closeButton: "button",
                                icon: true,
                                rtl: false
                            });
                        }
                    });   
                }
            }
        }
    }
}
</script>

