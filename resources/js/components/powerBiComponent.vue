<template>
  <div class="row mt-4 mx-4">
    <div class="col-12">
      <div class="card mb-4">
        <div class="row pb-0 p-4">
          <div class="col-md-4 col-12 mb-2"> 
            <div class="d-flex align-items-center"> {{  }}
              <select v-model="selectedProgress" class="form-select form-select-sm me-2" style="min-width: 100px; max-width: 200px;">
                <option disabled value="">Select a progress report</option>
                <option v-for="number in progressNumbers" :key="number" :value="number">{{ number }}</option>
              </select>
              <button class="btn btn-spacing btn-search-blue mt-2" @click="searchByProgressReport">
                <i class="fa-solid fa-magnifying-glass"></i>
              </button>
            </div>
          </div>
          <div class="col-md-8 col-12 mb-2">
            <div class="d-flex justify-content-end">
              <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')">
                <i class="fa-solid fa-rotate"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
          <div class="row">
            <div class="col-4">

            </div>
            <div class="col-4">

            </div>
            <div class="col-4 text-center">
              <canvas ref="chartRef"></canvas>

              <!-- Mostrar el conteo seleccionado -->
              <div v-if="selectedYearData">
                <p>{{ selectedYearData }}</p>
              </div>

              <!-- Mostrar el estado de carga -->
              <div v-if="loading">Loading...</div>
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
  import { Line } from 'vue-chartjs';
  import { Chart as ChartJS, CategoryScale, LinearScale, LineElement, PointElement, Title, Tooltip, Legend } from 'chart.js';
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';

// Registra las escalas y elementos necesarios
ChartJS.register(CategoryScale, LinearScale, LineElement, PointElement, Title, Tooltip, Legend);

export default {
  setup() {
    const chartData = ref({
      labels: [],
      datasets: [
        {
          label: 'Publications per Report',
          data: [],
          borderColor: 'rgb(75, 192, 192)',
          fill: false
        }
      ]
    });

    const chartOptions = ref({
      responsive: true,
      interaction: {
        mode: 'nearest', // Modo de interacción
        axis: 'x',       // Eje sobre el que interactuar (puedes usar 'x' o 'xy')
        intersect: false // Si es 'false', permite la interacción aunque no esté exactamente sobre el punto
      },
      plugins: {
        legend: {
          position: 'top'
        }
      },
      tooltip: {
        enabled: true // Asegúrate de que los tooltips estén habilitados
      }
    });

    const loading = ref(true);
    const chartRef = ref(null);
    const chartInstance = ref(null);
    const selectedYearData = ref('');

    // Esta es la función que será llamada cuando el usuario pase el cursor sobre el gráfico
    const handleHover = (event, chartElements) => {
      if (chartElements.length > 0) {
        const element = chartElements[0]; // Solo tomamos el primer punto si hay más de uno
        const year = chartData.value.labels[element.index];
        const count = chartData.value.datasets[0].data[element.index];

        selectedYearData.value = `Year/Report: ${year} - Total Publications: ${count}`;
      } else {
        selectedYearData.value = ''; // Si no hay ningún punto bajo el cursor
      }
    };

    onMounted(() => {
      axios.get('/api/getPublicationsByProgressReport')
        .then(response => {
          const result = response.data;
          chartData.value.labels = result.map(item => `Report ${item.progressReport}`);
          chartData.value.datasets[0].data = result.map(item => item.total);
          loading.value = false;

          nextTick(() => {
            if (chartRef.value) {
              const ctx = chartRef.value.getContext('2d');
              if (ctx) {
                chartInstance.value = new ChartJS(ctx, {
                  type: 'line',
                  data: chartData.value,
                  options: chartOptions.value
                });

                chartInstance.value.options.plugins.tooltip.callbacks = {
                  label: (tooltipItem) => {
                    return `Year/Report: ${tooltipItem.label} - Total Publications: ${tooltipItem.raw}`;
                  }
                };
              }
            }
          });
        })
        .catch(error => {
          console.error("Error fetching data:", error);
          loading.value = false;
        });
    });

    const handleClick = (event) => {
      if (!chartInstance.value) {
        console.log('El gráfico no está inicializado');
        return;
      }

      const points = chartInstance.value.getElementsAtEventForMode(event, 'nearest', { intersect: true });
      console.log(points);
      if (points.length > 0) {
        const firstPoint = points[0];
        const year = chartData.value.labels[firstPoint.index];
        const count = chartData.value.datasets[0].data[firstPoint.index];

        selectedYearData.value = `Year/Report: ${year} - Total Publications: ${count}`;
      } else {
        console.log('No se encontraron puntos en el gráfico.');
      }
    };

    return {
      chartRef,
      chartInstance,
      selectedYearData,
      handleHover,
      loading
    };
  }
};




</script>
