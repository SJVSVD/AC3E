<template>
    <div class="row mt-4 mx-4">
      <div class="col-12">
        <div class="card mb-4">
          <div class="row pb-0 p-4">
            <div class="col-4">
              <div class="d-flex align-items-center">
                <select
                  v-model="selectedProgress"
                  class="form-select form-select-sm me-2"
                  style="min-width: 100px; max-width: 200px;"
                >
                  <option disabled value="">Select a progress report</option>
                  <option
                    v-for="number in progressNumbers"
                    :key="number"
                    :value="number"
                  >
                    {{ number }}
                  </option>
                </select>
                <button
                  class="btn btn-spacing btn-search-blue mt-2"
                  @click="searchByProgressReport"
                >
                  <i class="fa-solid fa-magnifying-glass"></i>
                </button>
              </div>
            </div>
            <div class="col-8">
              <div class="d-flex justify-content-end">
                <a
                  class="btn btn-spacing btn-search-blue"
                  @click="recargarTabla('General')"
                >
                  <i class="fa-solid fa-rotate"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
            <div class="container">
              <div class="row">
                <div class="col-6">
                  <div class="card">
                    <div class="card-header">
                      <label for="researchLineSelect" class="form-label">
                        Selecciona una línea:
                      </label>
                      <select
                        id="researchLineSelect"
                        v-model="selectedResearchLine"
                        class="form-select"
                        @change="handleLineSelection"
                      >
                        <option value="" disabled>Selecciona una línea</option>
                        <option
                          v-for="line in researchLines"
                          :key="line.name"
                          :value="line.name"
                        >
                          {{ line.name }}
                        </option>
                      </select>
                    </div>
                    <div class="card-header" v-if="selectedMembers.length">
                      <label for="researchLineSelect" class="form-label">
                        Selecciona un miembro:
                      </label>
                      <select
                        id="researchLineSelect"
                        v-model="selectedPerson"
                        class="form-select"
                      >
                        <option value="" disabled>Selecciona un miembro</option>
                        <option
                          v-for="member in selectedMembers"
                          :key="member.id"
                          :value="member.id"
                        >
                          {{ member.name }}
                        </option>
                      </select>
                    </div>
                    <div class="card-body" v-else>
                      <p class="text-muted">No hay miembros disponibles.</p>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="card">
                    <div class="card-header">
                      <label class="form-label">Inputs dinámicos:</label>
                    </div>
                    <div class="card-body">
                      <div v-if="actualProgressReport > 0">
                        <div
                          v-for="n in actualProgressReport"
                          :key="n"
                          class="mb-2"
                        >
                          <label :for="`progressReport${n}`">Progress report year {{ n }}:</label>
                          <input
                            type="number"
                            class="form-control"
                            :id="`progressReport${n}`"
                            v-model="dynamicInputs[n - 1].goal"
                          />
                        </div>
                        <button class="btn btn-primary mt-3" @click="saveProgressReports">
                          Guardar
                        </button>
                      </div>
                      <div v-else>
                        <p class="text-muted">No hay inputs disponibles.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <modalconfirmacion ref="confirmation"></modalconfirmacion>
        <modalalerta ref="alert"></modalalerta>
      </div>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  import modalconfirmacion from "./snippets/sistema/alerts/confirmationModal.vue";
  import modalalerta from "./snippets/sistema/alerts/alertModal.vue";
  import { mixin } from "./../mixins.js";
  
  export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data() {
      return {
        indicators: null,
        mostrarTabla: false,
        mostrarCarga: true,
        permisos: null,
        table: null,
        actualProgressReport: 0,
        selectedProgress: "",
        progressNumbers: [],
        researchLines: [],
        selectedResearchLine: "",
        selectedMembers: [],
        selectedPerson: "",
        dynamicInputs: []
      };
    },
    created() {
      this.getProgressReport();
      this.fetchResearchLinesAndMembers();
    },
    methods: {
      searchByProgressReport() {
        axios
          .get(`api/getIndicators/${this.selectedProgress}`)
          .then((response) => {
            this.indicators = response.data;
            this.recargarTabla("Search");
          })
          .catch((e) => console.log(e));
      },
      getProgressReport() {
        axios
          .get("api/showProgressReport")
          .then((response) => {
            this.actualProgressReport = response.data;
            this.dynamicInputs = Array.from({
              length: this.actualProgressReport
            }, (_, index) => ({
              progressReport: index + 1,
              goal: null
            }));
            this.selectedProgress = this.actualProgressReport;
            this.progressNumbers = this.createNumberArray(response.data);
          })
          .catch((e) => console.log(e));
      },
      createNumberArray(progress) {
        return Array.from({ length: progress }, (_, index) => index + 1);
      },
      recargarTabla(tipoRecarga) {
        this.mostrarCarga = true;
        if (tipoRecarga == "General") {
          this.indicators = null;
          this.getIndicators();
        } else {
          this.table.destroy();
          this.crearTabla("#myTableIndicators");
        }
      },
      async fetchResearchLinesAndMembers() {
        try {
          const response = await axios.get("/api/getUsersWithResearch");
          const data = Array.isArray(response.data)
            ? response.data
            : Object.values(response.data);
  
          const groupedLines = {};
          data.forEach((user) => {
            const lineName = user.research_line.name;
            if (!groupedLines[lineName]) {
              groupedLines[lineName] = [];
            }
            groupedLines[lineName].push({
              id: user.id,
              name: user.name,
            });
          });
  
          this.researchLines = Object.keys(groupedLines).map((lineName) => ({
            name: lineName,
            members: groupedLines[lineName].sort((a, b) =>
              a.name.localeCompare(b.name)
            ),
          }));
        } catch (error) {
          console.error("Error fetching research lines and members:", error);
        }
      },
      handleLineSelection() {
        const selectedLine = this.researchLines.find(
          (line) => line.name === this.selectedResearchLine
        );
        this.selectedMembers = selectedLine ? selectedLine.members : [];
      },
      saveProgressReports() {
        const payload = {
          type: this.selectedResearchLine ? "ResearchLine" : "Individual",
          related_id: this.selectedResearchLine || this.selectedPerson,
          progressReports: this.dynamicInputs
        };
  
        axios
          .post("/api/saveProgressReports", payload)
          .then((response) => {
            console.log("Saved successfully", response.data);
          })
          .catch((error) => {
            console.error("Error saving progress reports", error);
          });
      }
    }
  };
  </script>
  