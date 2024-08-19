<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-xs">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Import Thesis Student
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="row">
                    <div class="col-7">
                        <div class="form-group">
                          <label for="archivo" style="font-size: 14px;">Select a file: </label>
                          <label for="" style="color: orange;">*</label>
                          <input type="file" ref="fileInput" accept=".xlsx, .xls" class= "form-control" @change="handleFileChange">
                        </div>
                      </div>
                      <div class="col-5 pt-2">
                        <br>
                        <a :class="[ 'btn', 'btn-continue', { 'btn-grey': errorMessage } ]" @click="processFile" :disabled="errorMessage">Process File</a>
                      </div>
                  </div>

                  <p v-if="errorMessage" style="color: red;">{{ errorMessage }}</p>
                  <div v-if="dataLoaded">
                    <label style="font-size: 14px;">{{ excelData.length }} records have been found.</label>
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
import {mixin} from '../../../../mixins.js'
import axios from 'axios'
export default {
    mixins: [mixin],
    data: () => ({
      excelData: [],
      headers: [],
      errorMessage: '',
      dataLoaded: false,
    }),
    created(){
    },
    methods: {
      handleFileChange(event) {
      const file = event.target.files[0];
      if (file) {
        this.readFile(file);
      }
    },
    readFile(file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        const data = e.target.result;
        const workbook = XLSX.read(data, { type: 'binary' });
        const sheetName = workbook.SheetNames[0];
        const sheet = workbook.Sheets[sheetName];
        const jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1 });

        if (jsonData.length > 0) {
          this.headers = jsonData[0];
          // Verificar si las cabeceras coinciden con las esperadas
          const expectedHeaders = [
          'idUsuario',	
          'Status',	
          'Progress Report',
          'Number of Research Line',
          'Identification',
          'Student Name',
          'RUN or Passport',
          'Gender (M/F)',
          'Student Mail',
          'Thesis Status',
          'Thesis Title',
          'Academic Degree',
          'Degree Denomination',
          'Name1',
          'Institution1',
          'Name2',
          'Institution2',
          'Name3',
          'Institution3',
          'University that gives the degree',
          'Year in which student starts Program',
          'Year in which thesis starts'	,
          'Year in which thesis ends',
          'Resources provided by the Center',
          'Posterior working area',	
          'Institution of Posterior working area',
          'Year of Information Report Working Area',
          'Researchers Involved',
          'Comentarios',
          ];
          const missingHeaders = expectedHeaders.filter(header => !this.headers.includes(header));
          if (missingHeaders.length > 0) {
            this.errorMessage = `The following headers are missing from the Excel file: ${missingHeaders.join(", ")}`;
            return;
          }
          this.errorMessage = "";
          this.excelData = jsonData.slice(1).map(row => {
            const obj = {};
            this.headers.forEach((header, index) => {
              // Verificar si el valor está definido, si no, asignar un valor vacío
              obj[header] = row[index] !== undefined ? row[index] : '';
            });
            return obj;
          });
          this.dataLoaded = true;
        } else {
          this.errorMessage = "El archivo Excel está vacío.";
        }
      };
      reader.readAsBinaryString(file);
    },

    async processFile() {
      if (this.excelData.length > 0) {
        try {
          const response = await axios.post('api/importThesis', {
            data: this.excelData
          });
          this.toast.success(this.excelData.length + ' records have been uploaded successfully.', {
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
          setTimeout(() => {
            this.$emit('close'); 
          }, 3000);
        } catch (error) {
          this.toast.warning('Error sending data to server:', error, {
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
      } else {
        this.toast.warning('The excel entered is invalid.', {
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
    },
}
</script>

<style src="@vueform/multiselect/themes/default.css"></style>