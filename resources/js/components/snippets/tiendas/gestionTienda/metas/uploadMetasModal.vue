<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-xl">
              <div class="modal-header" style="font-weight: bold; color: #444444;">
                <slot name="header">Subir Archivo:</slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body" style="height: 600px;">
                <slot name="body">
                  <div class="text-center" style="position: relative; top: 10%;">
                    <label style="font-size: 16px;" for="xls_file">Documento (.XLS o . XLSX) con las Metas correspondientes:</label><br>
                    <label style="color: #b80f0a; font-size: 11px; font-weight: normal;" for="xls_file">El sistema está diseñado para aceptar<label style="color: #b80f0a;">SOLAMENTE</label> el archivo con un formato determinado para este propósito. Descargue <a v-if="plantilla != null" :href=plantilla.url  target="_blank" class="fw-bolder" style="color: #b80f0a;">aquí</a> la plantilla.</label><br>
                    <select class="form-select-fit" style="top: 15%;" v-model="selectedTipoMeta">
                      <option selected disabled :value="null">Seleccione tipo de meta:</option>
                      <option v-for="tipoMeta in tiposMetas" :key="tipoMeta.id" :value="tipoMeta">
                        <label>{{ tipoMeta.nombre }}</label>
                      </option>
                    </select> <br><br>
                    <form enctype="multipart/form-data">
                      <input style="width: 500px; margin-bottom: 15px; font-size: 12px;" type="file" name="xls_file" id="xls_file" accept=".xls, .xlsx" class="form-control"><br>
                      <a class="btn btn-danger btn-sm" @click="saveMetas()">Subir metas</a>
                    </form>
                    <label v-if="subidaExistosa == true">Archivo subido:<label style="font-size: 12px; font-weight: normal;">{{ documentXLS }}</label> <br></label>
                  </div>
                </slot>
              </div>
              <div class="modal-footer">
                <!--  -->
              </div>
              <modalconfirmacion ref="confirmation"></modalconfirmacion>
              <modalalerta ref="alert"></modalalerta>
            </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import axios from 'axios'
import modalconfirmacion from '../../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../../sistema/alerts/alertModal.vue'
import {mixin} from '../../../../../mixins.js'

export default {
  components: { modalconfirmacion, modalalerta },
  mixins: [mixin],
  data: () => ({
    subidaExistosa: false,
    selectedTipoMeta: null,
    tiposMetas: [
      {id: 1, nombre: "Mensual Tienda"},
      {id: 2, nombre: "Diarias Tienda"},
      {id: 3, nombre: "Colaborador"},
    ],
    plantilla: {url: "https://www.youtube.com/watch?v=dQw4w9WgXcQ"},
  }),
  methods: {
    cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
    },
    saveMetas(){
      this.subidaExistosa = false;
      if(this.selectedTipoMeta != null){
        if(document.querySelector('#xls_file').files.item(0) != null){
          this.documentXLS = document.getElementById('xls_file').files.item(0).name;
          var ExcelToJSON = function() {
            var result3 = {};
            this.parseExcel = function(file) {
              var result2 = {};
              var reader = new FileReader();
              reader.onload = function(e) {
                var data = e.target.result;
                var workbook = XLSX.read(data, {type: 'binary'});
                var result = {};
                workbook.SheetNames.forEach(function(sheetName) {
                  var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                  var json_object = JSON.stringify(XL_row_object);
                  if (json_object.length) result = JSON.parse(json_object);
                });
                result2 = result;
              };
              reader.onerror = function(e) {
                console.log(e);
              };
              reader.readAsBinaryString(file);
              setTimeout(() => {result3 = result2;}, 100);
            };
            setTimeout(() => {this.data = result3;}, 200);
          };
          var xlsFile = document.querySelector('#xls_file');
          var xl2json = new ExcelToJSON();
          xl2json.parseExcel(xlsFile.files[0]);
          setTimeout(() => {
            if (this.selectedTipoMeta.nombre == "Mensual Tienda"){
              this.subidaExistosa = true;
              axios.post('api/metasMensualesImport', xl2json.data).then((response) => {
                if (response.data.tiendasInexistentes != null){
                  this.toast.warning(`Las siguientes tiendas ingresadas en el documento no existen: ${response.data.tiendasInexistentes}`, {
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
                this.toast.success("Referencias subidas con éxito!", {
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
                // setTimeout(() => {this.cerrarModal();}, 1500);
              })
              .catch((error)=> {
                if (error.response.status == 422){
                  this.errors = error.response.data.errors;
                  this.toast.warning('Ocurrió un error!', {
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
            if (this.selectedTipoMeta.nombre == "Diarias Tienda"){
              this.subidaExistosa = true;
              axios.post('api/metasDiariasImport', xl2json.data).then((response) => {
                if (response.data.tiendasInexistentes != null){
                  this.toast.warning(`Las siguientes tiendas ingresadas en el documento no existen: ${response.data.tiendasInexistentes}`, {
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
                this.toast.success("Referencias subidas con éxito!", {
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
                // setTimeout(() => {this.cerrarModal();}, 1500);
              })
              .catch((error)=> {
                if (error.response.status == 422){
                  this.errors = error.response.data.errors;
                  this.toast.warning('Ocurrió un error!', {
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
            if (this.selectedTipoMeta.nombre == "Colaborador"){
              this.subidaExistosa = true;
              axios.post('api/metasColaboradorImport', xl2json.data).then((response) => {
                if (response.data.tiendasInexistentes != null){
                  this.toast.warning(`Las siguientes tiendas ingresadas en el documento no existen: ${response.data.tiendasInexistentes}`, {
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
                if (response.data.colaboradoresInexistentes != null){
                  this.toast.warning(`Los siguientes colaboradores ingresados en el documento no existen: ${response.data.colaboradoresInexistentes}`, {
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
                this.toast.success("Referencias subidas con éxito!", {
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
                // setTimeout(() => {this.cerrarModal();}, 1500);
              })
              .catch((error)=> {
                if (error.response.status == 422){
                  this.errors = error.response.data.errors;
                  this.toast.warning('Ocurrió un error!', {
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
          }, 300);
        }
        else{
          this.toast.warning('Seleccione un archivo.', {
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
      }
      else{
        this.toast.warning('Seleccione un tipo de meta.', {
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
    },
  }
}
</script>