<template>
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-xs">
              <div class="modal-header" style="font-weight: bold; color: #444444;">
                <slot name="header">Seleccionar Empresa:</slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body" style="min-height: 150px;">
                <slot name="body">
                  <div class="text-center" style="position: relative; top: 10%;">
                    <select class="form-select-fit" style="top: 15%;" v-model="selectedCompany">
                      <option selected disabled :value="null">Seleccione la empresa de la que desea exportar datos:</option>
                      <option v-for="empresa in empresas" :key="empresa.id" :value="empresa.id">
                        <label>{{ empresa.razonSocial }}</label>
                      </option>
                      <option value="Todas">Todas.</option>
                    </select>
                  </div>
                </slot>
              </div>
              <div class="modal-footer">
                <slot name="footer">
                  <div v-if="selectedCompany != null">
                    <a v-if="accionSeleccionada == 'facturasExportPropuesta'" class="btn btn-alert float-end" @click="facturasExportPropuesta(selectedCompany);">Continuar</a>
                    <a v-if="accionSeleccionada == 'facturasExportGastoAutorizado'" class="btn btn-alert float-end" @click="facturasExportGastoAutorizado(selectedCompany);">Continuar</a>
                    <a v-if="accionSeleccionada == 'facturasExport'" class="btn btn-alert float-end" @click="facturasExport(selectedCompany);">Continuar</a>
                  </div>
                </slot>
              </div>
              <modalconfirmacion ref="confirmation"></modalconfirmacion>
              <modalalerta ref="alert"></modalalerta>
            </div>
        </div>
      </div>
    </div>
</template>

<script>
import axios from 'axios'
import {mixin} from '../../mixins.js'

export default {
  components: {  },
  mixins: [mixin],
  data(){
      return{
          empresas: null,
          selectedCompany: null,
          accionSeleccionada: null,
      }
  },
  props:{
        accion: Object,
    },
  created(){
    this.accionSeleccionada = this.accion;
    this.getEmpresas();
  },
  methods: {
    getEmpresas(){
      axios.get('../api/empresas').then( response =>{
          this.empresas = response.data;
      }).catch(e=> console.log(e))
    },
    facturasExportPropuesta(){
      var data = {
        userID: this.userID,
        selectedCompany: this.selectedCompany,
      };
      axios({
          url: `api/facturasExportPropuesta`, data,
          method: 'POST',
          responseType: 'arraybuffer',
      }).then((response) => {
          let blob = new Blob([response.data], {
              type: 'application/xlsx'
          })
          let link = document.createElement('a')
          link.href = window.URL.createObjectURL(blob)
          link.download = 'Propuesta.xlsx'
          link.click()
      })
    },
    facturasExportGastoAutorizado(){
      var data = {
        userID: this.userID,
        selectedCompany: this.selectedCompany,
      };
      axios({
          url: `api/facturasExportGastoAutorizado`, data,
          method: 'POST',
          responseType: 'arraybuffer',
      }).then((response) => {
          let blob = new Blob([response.data], {
              type: 'application/xlsx'
          })
          let link = document.createElement('a')
          link.href = window.URL.createObjectURL(blob)
          link.download = 'Facturas Gasto Autorizado.xlsx'
          link.click()
      })
    },
    facturasExport(){
      var data = {
        userID: this.userID,
        selectedCompany: this.selectedCompany,
      };
      axios({
          url: `api/facturasExport`, data,
          method: 'POST',
          responseType: 'arraybuffer',
      }).then((response) => {
          let blob = new Blob([response.data], {
              type: 'application/xlsx'
          })
          let link = document.createElement('a')
          link.href = window.URL.createObjectURL(blob)
          link.download = 'Detalle de todas las Facturas.xlsx'
          link.click()
      })
    },
  }
}
</script>