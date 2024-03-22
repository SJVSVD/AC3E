<template>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>Modelo</th>
                    <th>Cantidad de Registros</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(count, model) in statistics" :key="model">
                    <td>{{ model }}</td>
                    <td>{{ count }}</td>
                </tr>
            </tbody>
        </table>
        <button @click="exportExcel">Exportar a Excel</button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                statistics: {
                    'Funding Sources': 0,
                    'Technology Knowledge': 0,
                    'Public Private': 0,
                    'Patents': 0,
                    'Outreach Activities': 0,
                    'Post Doc': 0,
                    'Thesis Student': 0,
                    'SC Collaborations': 0,
                    'Participation SC Events': 0,
                    'Organizations SC Events': 0,
                    'Awards': 0,
                    'Books': 0,
                    'Non ISI Publication': 0,
                    'ISI Publication': 0,
                }
            }
        },
        methods: {
            exportExcel() {
                window.location.href = '/export-statistics';
            }
        }
    }
</script>
