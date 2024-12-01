<template>
    <div>
      <line-chart :chart-data="chartData" :options="chartOptions" />
    </div>
  </template>
  
  <script>
  import { Line } from 'vue-chartjs';
  import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement } from 'chart.js';
  
  ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement);
  
  export default {
    components: {
      LineChart: Line,
    },
    props: {
      publicationData: {
        type: Array,
        required: true,
      },
      goalData: {
        type: Number,
        required: true,
      },
    },
    data() {
      return {
        chartData: {
          labels: [],
          datasets: [
            {
              label: 'Publicaciones por año',
              backgroundColor: '#42A5F5',
              borderColor: '#42A5F5',
              data: [],
              fill: false,
            },
            {
              label: 'Meta',
              borderColor: '#FF0000',
              data: [],
              fill: false,
              borderDash: [10, 5],
            },
          ],
        },
        chartOptions: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: {
              title: {
                display: true,
                text: 'Año',
              },
            },
            y: {
              title: {
                display: true,
                text: 'Número de Publicaciones',
              },
            },
          },
        },
      };
    },
    methods: {
      processData() {
        // Agrupar las publicaciones por año
        const groupedData = {};
        this.publicationData.forEach((item) => {
          const year = item.progressReportYear;
          groupedData[year] = (groupedData[year] || 0) + 1;
        });
  
        // Configurar los datos del gráfico
        const years = Object.keys(groupedData).sort(); // Ordenar los años
        const counts = years.map((year) => groupedData[year]);
  
        // Actualizar las etiquetas y datos del gráfico
        this.chartData.labels = years;
        this.chartData.datasets[0].data = counts;
  
        // Configurar la línea de meta
        const goalLine = Array(years.length).fill(this.goalData);
        this.chartData.datasets[1].data = goalLine;
      },
    },
    mounted() {
      this.processData();
    },
    watch: {
      // Si los datos cambian, vuelve a procesarlos
      publicationData: 'processData',
      goalData: 'processData',
    },
  };
  </script>
  