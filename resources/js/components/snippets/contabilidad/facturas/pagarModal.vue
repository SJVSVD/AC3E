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
                    <label style="font-size: 16px;" for="xls_file">Documento (.XLS o . XLSX) con los Pagos de las Facturas:</label><br>
                    <label style="color: #b80f0a; font-size: 11px; font-weight: normal;" for="xls_file">El sistema está diseñado para aceptar<label style="color: #b80f0a;">SOLAMENTE</label> el archivo de SAP destinado a este propósito.</label><br>
                    <form enctype="multipart/form-data">
                      <input style="width: 500px; margin-bottom: 15px; font-size: 12px;" type="file" name="xls_file" id="xls_file" accept=".xls, .xlsx" class="form-control"><br>
                      <a class="btn btn-danger btn-sm" @click="pagosImport()">Subir pagos</a>
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
import modalconfirmacion from '../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../sistema/alerts/alertModal.vue'
import {mixin} from '../../../../mixins.js'

export default {
  components: { modalconfirmacion, modalalerta },
  mixins: [mixin],
  data: () => ({
    subidaExistosa: false,
  }),
  methods: {
    cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
    },
    pagosImport(){
      this.subidaExistosa = false;
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
          this.subidaExistosa = true;
          axios.post('api/referencesImport', xl2json.data).then((response) => {
            this.toast.success("Pagos subidos con éxito!", {
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
    },
  }
}
</script>