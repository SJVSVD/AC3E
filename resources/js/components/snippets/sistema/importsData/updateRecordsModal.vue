<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-xs">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Update Records
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
    let jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1 });

    // Limitar a las primeras 910 filas (incluyendo la fila de cabecera)
    jsonData = jsonData.slice(0, 912);

    if (jsonData.length > 0) {
      this.headers = jsonData[0];
      // Verificar si las cabeceras coinciden con las esperadas
      const expectedHeaders = [
        "ID",	"idUsuario",	"Status"	,"Progress Report","Institution with which the Center collaborates",	
        "Activity Name",	"Country Origin",	"City Origin",	"Beginning Date",	"Ending Date",	
        "Number of Research Line",	"Name of AC3E member",	"Name of external Researchers Involved",
        "Collaboration stay",	"Comentarios",	"External researcher is"	,"Country/City of destination",
        "State","Colaboracion internacional"

      ];
      const missingHeaders = expectedHeaders.filter(header => !this.headers.includes(header));
      if (missingHeaders.length > 0) {
        this.errorMessage = `The following headers are missing from the Excel file: ${missingHeaders.join(", ")}`;
        return;
      }
      this.errorMessage = "";
      this.excelData = jsonData.slice(1).filter(row => {
        // Verificar que la fila no esté vacía
        return row.some(cell => cell !== undefined && cell !== '');
      }).map(row => {
        const obj = {};
        this.headers.forEach((header, index) => {
          // Verificar si el valor está definido, si no, asignar un valor vacío
          obj[header] = row[index] !== undefined ? row[index] : '';
        });
        return obj;
      });
      console.log(this.excelData);
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
          const response = await axios.post('api/updateRecords', {
            records: this.excelData
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