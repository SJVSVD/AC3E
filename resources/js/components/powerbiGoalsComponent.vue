<template>
  <div class="row mt-4 mx-4">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-body px-0 pt-4 pb-2" style="min-height: 400px;">
          <div class="container">
            <div class="row">
              
              <!-- FILTROS -->
              <div class="col-3">
                <div class="card shadow">
                  <div class="card-header text-white">
                    <h5 class="card-title text-center">Filters</h5>
                  </div>
                  <div class="card-body p-3">

                    <!-- Selector de Tipo de Filtro -->
                    <label class="form-label">Filter by:</label>
                    <select v-model="selectedFilterType" class="form-select mb-3" @change="resetFilters()">
                      <option value="" disabled>Select a filter type</option>
                      <option value="researchType">Researcher Type</option>
                    </select>

                    <!-- Selector de Línea, Investigador o Tipo de Investigador
                    <div v-if="selectedFilterType === 'line'">
                      <label class="form-label">Select a research line:</label>
                      <select v-model="selectedResearchLine" class="form-select mb-3">
                        <option :value="null" disabled>Select a line</option>
                        <option v-for="line in researchLines" :key="line.id" :value="line.id">
                          {{ line.name }}
                        </option>
                      </select>
                    </div>

                    <div v-if="selectedFilterType === 'researcher'">
                      <label class="form-label">Select a researcher:</label>
                      <select v-model="selectedPerson" class="form-select mb-3">
                        <option :value="null" disabled>Select a researcher</option>
                        <option v-for="person in allResearchers" :key="person.id" :value="person.id">
                          {{ person.name }}
                        </option>
                      </select>
                    </div> -->

                    <div v-if="selectedFilterType === 'researchType'">
                      <label class="form-label">Select researcher type:</label>
                      <select v-model="selectedResearchType" class="form-select mb-3">
                        <option :value="null" disabled>Select a type</option>
                        <option v-for="type in researcherTypes" :key="type.id" :value="type.id">
                          {{ type.name }}
                        </option>
                      </select>
                    </div>

                    <label class="form-label">Select a module:</label>
                    <select v-model="selectedModule" class="form-select mb-3">
                      <option :value="null" >Select a module</option>
                      <option v-for="module in modules" :key="module.name" :value="module.name">
                        {{ module.displayName }}
                      </option>
                    </select>

                    <button class="btn btn-search-blue w-100 mt-3" @click="searchGoals">
                      Load Goals
                    </button>

                  </div>
                </div>
              </div>

              <!-- INPUTS DE METAS -->
              <div class="col-9">
                <div class="card">
                  <div class="card-body">
                    <label class="form-label">Performance targets:</label>
                    <div v-if="actualProgressReport > 0">
                      <div v-for="(input, index) in dynamicInputs" :key="index" class="mb-2">
                        <label :for="`progressReport${input.progressReport}`">Progress report year {{ input.progressReport }}:</label>
                        <input
                          type="number"
                          class="form-control"
                          :id="`progressReport${input.progressReport}`"
                          v-model="input.goal"
                        />
                      </div>
                      <button class="btn btn-continue float-end mt-3" @click="saveProgressReports">
                        Save
                      </button>
                    </div>
                    <div v-else>
                      <p class="text-muted">No goals available.</p>
                    </div>
                  </div>
                </div>
              </div>
              <!-- FIN INPUTS -->

            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { ref, onMounted } from "vue";

export default {
  setup() {
    const modules = ref([
      { name: 'isiPublications', displayName: 'WoS Publications' },
      { name: 'nonIsiPublications', displayName: 'Non-WoS Publications' },
      { name: 'books', displayName: 'Books' },
      { name: 'awards', displayName: 'Awards' },
      { name: 'organizationsScEvents', displayName: 'Organizations SC Events' },
      { name: 'participationScEvents', displayName: 'Participation SC Events' },
      { name: 'scCollaborations', displayName: 'Scientific Collaborations' },
      { name: 'thesisStudents', displayName: 'Thesis Students' },
      { name: 'postDocs', displayName: 'Postdocs' },
      { name: 'outreachActivities', displayName: 'Outreach Activities' },
      { name: 'patents', displayName: 'Patents' },
      { name: 'publicPrivate', displayName: 'Public-Private Initiatives' },
      { name: 'technologyKnowledge', displayName: 'Technology Knowledge' },
      { name: 'fundingSources', displayName: 'Funding Sources' },
    ]);

    const selectedFilterType = ref("");
    // const selectedResearchLine = ref(null);
    // const selectedPerson = ref(null);
    const selectedResearchType = ref(null);
    const selectedModule = ref(null);

    const researchLines = ref([]);
    const allResearchers = ref([]);
    const researcherTypes = ref([]);

    const actualProgressReport = ref(0);
    const dynamicInputs = ref([]);

    // Obtener año actual del Progress Report
    const getProgressReportYear = async () => {
      try {
        const response = await axios.get("/api/showProgressReport");
        actualProgressReport.value = response.data;
      } catch (error) {
        console.error("Error fetching progress report year:", error);
      }
    };

    const resetFilters = () => {
      // selectedResearchLine.value = null;
      // selectedPerson.value = null;
      selectedResearchType.value = null;
    };

    // const fetchResearchData = async () => {
    //   try {
    //     const response = await axios.get("/api/researchLines");
    //     researchLines.value = response.data.sort((a, b) => a.name.localeCompare(b.name)); // Ordenar por nombre
    //   } catch (error) {
    //     console.error("Error fetching research lines:", error);
    //   }
    // };

    const fetchResearcherTypes = async () => {
      try {
        const response = await axios.get("/api/rolesUser");
        // Filtrar solo los elementos con id 1, 2, 3
        researcherTypes.value = response.data
          .filter(role => [1, 2, 3].includes(role.id)) // Filtro por id
          .sort((a, b) => a.name.localeCompare(b.name)); // Ordenar por nombre
      } catch (error) {
        console.error("Error fetching researcher types:", error);
      }
    };

    // const fetchResearchers = async () => {
    //   try {
    //     const response = await axios.get("/api/getResearchers2");
    //     allResearchers.value = response.data.sort((a, b) => a.name.localeCompare(b.name)); // Ordenar por nombre
    //   } catch (error) {
    //     console.error("Error fetching researchers:", error);
    //   }
    // };

    // Función para cargar o inicializar metas
    const searchGoals = async () => {
      let relatedId = null;
      let type = "";
      let module = selectedModule.value; // Añadir la selección de módulo

      if (selectedFilterType.value === "researchType" && selectedResearchType.value) {
        relatedId = selectedResearchType.value;
        type = "RoleUser";
      } else {
        console.warn("No filter selected.");
        return;
      }

      try {
        // Modificar la URL de la solicitud para incluir el parámetro 'module' si está seleccionado
        const response = await axios.get(`/api/getProgressReport/${relatedId}?type=${type}&module=${module}`);

        let storedGoals = response.data.data?.goals || {};

        if (typeof storedGoals === "string") {
          storedGoals = JSON.parse(storedGoals);
        }

        const sortedGoals = Object.keys(storedGoals)
          .map(year => ({
            progressReport: parseInt(year),
            goal: storedGoals[year],
          }))
          .sort((a, b) => a.progressReport - b.progressReport);

        // Si no hay datos, inicializar los inputs hasta el año actual
        if (sortedGoals.length === 0) {
          dynamicInputs.value = Array.from({ length: actualProgressReport.value }, (_, index) => ({
            progressReport: index + 1,
            goal: null,
          }));
        } else {
          dynamicInputs.value = sortedGoals;
        }
      } catch (error) {
        console.error("Error loading progress reports:", error);
      }
    };


    // Guardar metas
    const saveProgressReports = async () => {
      const payload = {
        type: "RoleUser",
        related_id: selectedResearchType.value,
        goals: dynamicInputs.value,
        module: selectedModule.value // Añadir el campo module
      };

      console.log(payload);

      try {
        await axios.post("/api/saveProgressReport", payload);
        alert("Goals saved successfully!");
      } catch (error) {
        console.error("Error saving progress reports:", error);
        alert("Error saving goals.");
      }
    };

    onMounted(() => {
      getProgressReportYear();
      // fetchResearchData();
      // fetchResearchers();
      fetchResearcherTypes();
    });

    return {
      resetFilters,
      selectedFilterType,
      // selectedResearchLine,
      // selectedPerson,
      selectedModule,
      selectedResearchType,
      researchLines,
      allResearchers,
      researcherTypes,
      actualProgressReport,
      dynamicInputs,
      searchGoals,
      saveProgressReports,
      modules
    };
  },
};
</script>
