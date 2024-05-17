<template>
    <div class="row">
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card overflow-hidden" style="height: 160px;">
                <!-- <div class="card-body p-3 text-white text-shadow-black background-size" style="background-image:linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('https://wallpaperaccess.com/full/5816762.jpg');">
                    <div class="row">
                        <h6 class="fw-bolder text-white">Cuentas Activas Recientes</h6>
                        <div class="text-white fs-7" v-for="usuarioActivo in usuariosActivos" :key="usuarioActivo.id">
                            <span class="dot"></span>
                            {{ usuarioActivo.name }}
                        </div>
                    </div>
                </div> -->
                <div class="card-body p-3 text-white  background-size" style="background-color: #4d4d4d ; min-height: 160px;">
                    <div class="row">
                        <div class="col-12">
                            <div class="numbers" style="min-height: 80px; text-align: center; margin-top: 40px;">
                                <p class="text-sm mb-0 text-uppercase text-white fw-bold">Welcome</p>
                                <h5 style="color: #ffff;" class="fw-bolder">
                                    {{ userName }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card overflow-hidden" style="height: 160px;">
                <div class="card-body p-3 text-white  background-size" style="background-color: #4d4d4d; min-height: 160px;">
                    <div class="row">
                        <div class="col-12">
                            <div class="numbers" style="min-height: 80px; text-align: center; margin-top: 40px;">
                                <p class="text-sm mb-0 text-uppercase text-white fw-bold">Current date and time</p>
                                <h5 style="color: orange;" class="fw-bolder">
                                    {{ time }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card overflow-hidden" style="height: 160px;">
                <div class="card-body p-3 text-white background-size" style="background-image: url('/img/AC3EGif (2).gif'); transform: scale(3); background-size: contain; background-position: center; min-height: 160px;">
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-8">
            <div v-if="is('Administrator')" class="card z-index-2 p-0" style="min-height: 200px; max-height: 650px;">
                <div class="table-responsive p-4">
                    <div v-show="mostrarCarga" class="loader-sm"></div>
                    <table v-show="mostrarTabla" class="table align-items-center mb-0" id="myTableRegistros">
                        <thead>
                            <tr style="color: black">
                                <th style="min-width: 16px;"></th>
                                <th class="text-uppercase text-xs font-weight-bolder">Creation Date</th>
                                <th class="text-uppercase text-xs font-weight-bolder">Module</th>
                                <th class="text-uppercase text-xs font-weight-bolder">User</th>
                                <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(registro, index) in registros" :key="index">
                                <td></td>
                                <td>{{ thisDate(registro.created_at,true) }}</td>
                                <td v-if="registro.modulo == 'isiPublication'">ISI Publications</td>
                                <td v-else-if="registro.modulo == 'scCollaborations' && registro.moduleType == '1'">Conjoint Projects</td>
                                <td v-else-if="registro.modulo == 'fundingSources'">Funding Sources</td>
                                <td v-else-if="registro.modulo == 'organizationsScEvents'">Organization Sc Events</td>
                                <td v-else-if="registro.modulo == 'outreachActivities'">Outreach Activities</td>
                                <td v-else-if="registro.modulo == 'participationScEvents'">Participation Sc Events</td>
                                <td v-else-if="registro.modulo == 'patents'">Patents</td>
                                <td v-else-if="registro.modulo == 'postDoc'">Postdoctoral Fellows</td>
                                <td v-else-if="registro.modulo == 'publicPrivate'">Public-Private Connections</td>
                                <td v-else-if="registro.modulo == 'scCollaborations' && registro.moduleType == '0'">Sc Collaborations</td>
                                <td v-else-if="registro.modulo == 'nonIsiPublication'">Non ISI Publications</td>
                                <td v-else-if="registro.modulo == 'thesisStudent'">Thesis Students</td>
                                <td v-else-if="registro.modulo == 'awards'">Awards</td>
                                <td v-else-if="registro.modulo == 'books'">Books</td>
                                <td v-else-if="registro.modulo == 'technologyKnowledge'">Tec. and Know Transfer</td>
                                <td v-else>---</td> 
                                <td>{{ registro.usuario.name }}</td>
                                <td v-if="registro.modulo == 'isiPublication'"><a class="btn btn-success btn-xs" title="Details" @click="verIsiPublication(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                <td v-else-if="registro.modulo == 'scCollaborations' && registro.moduleType == '1'"><a class="btn btn-success btn-xs" title="Details" @click="verConjointProjects(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                <td v-else-if="registro.modulo == 'fundingSources'"><a class="btn btn-success btn-xs" title="Details" @click="verFundingSources(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                <td v-else-if="registro.modulo == 'organizationsScEvents'"><a class="btn btn-success btn-xs" title="Details" @click="verOrganizationScEvents(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                <td v-else-if="registro.modulo == 'outreachActivities'"><a class="btn btn-success btn-xs" title="Details" @click="verOutreachActivities(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                <td v-else-if="registro.modulo == 'participationScEvents'"><a class="btn btn-success btn-xs" title="Details" @click="verParticipationScEvents(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                <td v-else-if="registro.modulo == 'patents'"><a class="btn btn-success btn-xs" title="Details" @click="verPatents(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                <td v-else-if="registro.modulo == 'postDoc'"><a class="btn btn-success btn-xs" title="Details" @click="verPostDoc(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                <td v-else-if="registro.modulo == 'publicPrivate'"><a class="btn btn-success btn-xs" title="Details" @click="verPublicPrivate(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                <td v-else-if="registro.modulo == 'scCollaborations' && registro.moduleType == '0'"><a class="btn btn-success btn-xs" title="Details" @click="verScCollaborations(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                <td v-else-if="registro.modulo == 'nonIsiPublication'"><a class="btn btn-success btn-xs" title="Details" @click="verNonIsiPublications(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                <td v-else-if="registro.modulo == 'thesisStudent'"><a class="btn btn-success btn-xs" title="Details" @click="verThesisStudents(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                <td v-else-if="registro.modulo == 'awards'"><a class="btn btn-success btn-xs" title="Details" @click="verAwards(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                <td v-else-if="registro.modulo == 'books'"><a class="btn btn-success btn-xs" title="Details" @click="verBooks(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                <td v-else-if="registro.modulo == 'technologyKnowledge'"><a class="btn btn-success btn-xs" title="Details" @click="verTechnologyKnowledge(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                <td v-else>---</td> 
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card z-index-2 p-0" style="min-height: 200px; max-height: 650px;">
                <div class="row p-3">
                    <div class="col-md-6">
                        <a class="btn btn-search-blue"  style="min-height: 120px; display: flex; justify-content: center; align-items: center;" v-if="is('Administrator')" @click="exportConsolidado">
                            <i class="fa fa-fw fa-lg fa-solid fa-download"></i> {{ buttonText1 }}
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a class="btn btn-search-blue"  style="min-height: 120px; display: flex; justify-content: center; align-items: center;" @click="exportIndividual">
                            <i class="fa fa-fw fa-lg fa-solid fa-download"></i> {{ buttonText2 }}
                        </a>
                    </div>
                </div>
                <!-- <div class="row p-3">
                    <div class="col-4">
                        <input type="file" ref="fileInput" accept=".xlsx" class= "form-control" @change="processExcelFile">
                    </div>
                </div>
                <div class="row p-3">
                    <table v-if="keywordObjects.length > 0">
                        <thead>
                            <tr>
                            <th>DOI</th>
                            <th>Keyword</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in keywordObjects" :key="index">
                            <td>{{ item.doi }}</td>
                            <td>{{ item.keyword }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div> -->
            </div>
        </div>
        <modalver v-bind:isiPublication1="isiPublication" v-if="showDetailsIsi" @close="showDetailsIsi = false"></modalver>
        <modalver1 v-bind:project1="conjointProjects" v-if="showDetailsConjointProjects" @close="showDetailsConjointProjects = false"></modalver1>
        <modalver2 v-bind:fundingSource1="fundingSources" v-if="showDetailsFundingSources" @close="showDetailsFundingSources = false"></modalver2>
        <modalver3 v-bind:organization1="organizationScEvents" v-if="showDetailsOrganizationScEvents" @close="showDetailsOrganizationScEvents = false"></modalver3>
        <modalver4 v-bind:activity1="outreachActivities" v-if="showDetailsOutreachActivities" @close="showDetailsOutreachActivities = false"></modalver4>
        <modalver5 v-bind:participation1="participationScEvents" v-if="showDetailsParticipationScEvents" @close="showDetailsParticipationScEvents = false"></modalver5>
        <modalver6 v-bind:patent1="patents" v-if="showDetailsPatents" @close="showDetailsPatents = false"></modalver6>
        <modalver7 v-bind:postDoc1="postDoc" v-if="showDetailsPostDoc" @close="showDetailsPostDoc = false"></modalver7>
        <modalver8 v-bind:publicPrivate1="publicPrivate" v-if="showDetailsPublicPrivate" @close="showDetailsPublicPrivate = false"></modalver8>
        <modalver9 v-bind:collaboration1="scCollaborations" v-if="showDetailsScCollaborations" @close="showDetailsScCollaborations = false"></modalver9>
        <modalver10 v-bind:nonIsiPublication1="nonIsiPublications" v-if="showDetailsNonIsiPublications" @close="showDetailsNonIsiPublications = false"></modalver10>
        <modalver11 v-bind:thesisStudent1="thesisStudents" v-if="showDetailsThesisStudents" @close="showDetailsThesisStudents = false"></modalver11>
        <modalver12 v-bind:award1="awards" v-if="showDetailsAwards" @close="showDetailsAwards = false"></modalver12>
        <modalver13 v-bind:book1="books" v-if="showDetailsBooks" @close="showDetailsBooks = false"></modalver13>
        <modalver14 v-bind:technology1="technologyKnowledge" v-if="showDetailsTechnologyKnowledge" @close="showDetailsTechnologyKnowledge = false"></modalver14>
    </div>
</template>

<script>
import axios from 'axios'
import modalconfirmacion from './snippets/sistema/alerts/confirmationModal.vue'
import modalver from './snippets/sistema/isiPublications/detailsIsiPublicationModal.vue'
import modalver1 from './snippets/sistema/conjointProjects/detailsConjointProjectsModal.vue'
import modalver2 from './snippets/sistema/fundingSources/detailsFundingSourcesModal.vue'
import modalver3 from './snippets/sistema/organizationScEvents/detailsOrganizationScEventsModal.vue'
import modalver4 from './snippets/sistema/outreachActivities/detailsOutreachActivitiesModal.vue'
import modalver5 from './snippets/sistema/participationScEvents/detailsParticipationScEventsModal.vue'
import modalver6 from './snippets/sistema/patents/detailsPatentsModal.vue'
import modalver7 from './snippets/sistema/postDoc/detailsPostDocModal.vue'
import modalver8 from './snippets/sistema/publicPrivate/detailsPublicPrivateModal.vue'
import modalver9 from './snippets/sistema/scCollaborations/detailsScCollaborationsModal.vue'
import modalver10 from './snippets/sistema/nonIsiPublications/detailsNonIsiPublicationModal.vue'
import modalver11 from './snippets/sistema/thesisStudents/detalleThesisModal.vue'
import modalver12 from './snippets/sistema/awards/detailsAwardsModal.vue'
import modalver13 from './snippets/sistema/books/detailsBooksModal.vue'
import modalver14 from './snippets/sistema/technologyKnowledge/detailsTechnologyKnowledgeModal.vue'
import modalalerta from './snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../mixins.js'
import * as XLSX from 'xlsx';

export default {
    components: { modalconfirmacion,modalalerta,modalver,modalver1,modalver2,modalver3,modalver4,modalver5,modalver6,modalver7,modalver8,modalver9,modalver10,modalver11,modalver12,modalver13,modalver14 },
    mixins: [mixin],
    data() {
        return {
            cantidadRegistros: 10,
            registros: [],
            buttonText1: 'Download Consolidated',
            buttonText2: 'Download Individual Return',
            CLPRates: null,
            interval: null,
            time: null,
            weather: null,
            coords: null,
            usuariosActivos: [],
            mostrarTabla: false,
            mostrarCarga: true,
            isiPublication: '',
            conjointProjects: '',
            fundingSources: '',
            organizationScEvents: '',
            outreachActivities: '',
            participationScEvents: '',
            patents: '',
            postDoc: '',
            publicPrivate: '',
            scCollaborations: '',
            nonIsiPublications: '',
            thesisStudents: '',
            awards: '',
            books: '',
            technologyKnowledge: '',
            showDetailsIsi: false,
            showDetailsConjointProjects: false,
            showDetailsFundingSources: false,
            showDetailsOrganizationScEvents: false,
            showDetailsOutreachActivities: false,
            showDetailsParticipationScEvents: false,
            showDetailsPatents: false,
            showDetailsPostDoc: false,
            showDetailsPublicPrivate: false,
            showDetailsScCollaborations: false,
            showDetailsNonIsiPublications: false,
            showDetailsThesisStudents: false,
            showDetailsAwards: false,
            showDetailsBooks: false,
            showDetailsTechnologyKnowledge: false,
            keywordObjects: [],
        }
    },
    beforeDestroy() {
        clearInterval(this.interval);
    },
    created() {
        this.interval = setInterval(() => {
            options = {
                year: 'numeric', month: 'numeric', day: 'numeric',
                hour: 'numeric', minute: 'numeric', second: 'numeric',
                hour12: false,
            };
            this.time = Intl.DateTimeFormat(navigator.language, options).format()
        }, 1000);
    },
    mounted(){
        this.getRegistros(this.cantidadRegistros);
    },
    methods: {
        // Procesa un archivo Excel que se carga y realiza operaciones en sus datos.
        // async processExcelFile(event) {
        //     try {
        //         const file = event.target.files[0];
        //         const reader = new FileReader();
        //         reader.onload = async (event) => {
        //             const data = new Uint8Array(event.target.result);
        //             const workbook = XLSX.read(data, { type: 'array' });
        //             const sheetName = workbook.SheetNames[0];
        //             const worksheet = workbook.Sheets[sheetName];
        //             const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

        //             const totalRows = jsonData.length;
        //             let processedCount = 0;
        //             const processedData = await Promise.all(jsonData.map(async (row, index) => {
        //                 const doi = row[0]; // La primera columna es el DOI
        //                 const keyword = await this.getKeywordsFromDOI(doi);
        //                 processedCount++;
        //                 console.log(`Procesando fila ${index + 1}/${totalRows}. Faltan ${totalRows - processedCount} filas.`);
        //                 return {
        //                     doi: doi,
        //                     keyword: keyword
        //                 };
        //             }));

        //             console.log(processedData);
        //             this.keywordObjects = processedData;
        //         };
        //         reader.readAsArrayBuffer(file);
        //     } catch (error) {
        //         console.error('Error al procesar el archivo xlsx:', error);
        //     }
        // },

        // Obtiene palabras clave a partir de un DOI utilizando una solicitud a un servicio web.
        // async getKeywordsFromDOI(doi) {
        //     try {
        //         const encodedDoi = encodeURIComponent(doi);
        //         const response = await axios.post('api/useDoi', { doi: encodedDoi });
        //         return response.data.Data[0].Keyword.Keywords;
        //     } catch (error) {
        //         console.error('Error al obtener las palabras clave:', error);
        //         return ''; // Maneja el error como prefieras
        //     }
        // },

        // Muestra los detalles de un proyecto conjunto.
        verConjointProjects(conjointProject) {
            this.conjointProjects = conjointProject;
            this.showDetailsConjointProjects = true;
        },

        // Muestra los detalles de una fuente de financiamiento.
        verFundingSources(fundingSource) {
            this.fundingSources = fundingSource;
            this.showDetailsFundingSources = true;
        },

        // Muestra los detalles de eventos científicos de una organización.
        verOrganizationScEvents(organizationScEvent) {
            this.organizationScEvents = organizationScEvent;
            this.showDetailsOrganizationScEvents = true;
        },

        // Muestra los detalles de actividades de divulgación.
        verOutreachActivities(outreachActivity) {
            this.outreachActivities = outreachActivity;
            this.showDetailsOutreachActivities = true;
        },

        // Muestra los detalles de la participación en eventos científicos.
        verParticipationScEvents(participationScEvent) {
            this.participationScEvents = participationScEvent;
            this.showDetailsParticipationScEvents = true;
        },

        // Muestra los detalles de patentes.
        verPatents(patent) {
            this.patents = patent;
            this.showDetailsPatents = true;
        },

        // Muestra los detalles de un postdoc.
        verPostDoc(postDoc) {
            this.postDoc = postDoc;
            this.showDetailsPostDoc = true;
        },

        // Muestra los detalles de colaboraciones público-privadas.
        verPublicPrivate(publicPrivate) {
            this.publicPrivate = publicPrivate;
            this.showDetailsPublicPrivate = true;
        },

        // Muestra los detalles de colaboraciones científicas.
        verScCollaborations(scCollaboration) {
            this.scCollaborations = scCollaboration;
            this.showDetailsScCollaborations = true;
        },

        // Muestra los detalles de publicaciones no ISI.
        verNonIsiPublications(nonIsiPublication) {
            this.nonIsiPublications = nonIsiPublication;
            this.showDetailsNonIsiPublications = true;
        },

        // Muestra los detalles de estudiantes de tesis.
        verThesisStudents(thesisStudent) {
            this.thesisStudents = thesisStudent;
            this.showDetailsThesisStudents = true;
        },

        // Muestra los detalles de premios.
        verAwards(award) {
            this.awards = award;
            this.showDetailsAwards = true;
        },

        // Muestra los detalles de libros.
        verBooks(book) {
            this.books = book;
            this.showDetailsBooks = true;
        },

        // Muestra los detalles de conocimiento tecnológico.
        verTechnologyKnowledge(technologyKnowledge) {
            this.technologyKnowledge = technologyKnowledge;
            this.showDetailsTechnologyKnowledge = true;
        },

        // Muestra los detalles de publicaciones ISI.
        verIsiPublication(isiPublication){
            this.isiPublication = isiPublication;
            this.showDetailsIsi = true;
        },

        // Obtiene registros de una cantidad especificada y actualiza la tabla.
        getRegistros(cantidad){
            axios.get(`api/getRegistros/${cantidad}`).then( response =>{
                this.registros = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#myTableRegistros');
                this.table.buttons().remove();
            }).catch(e=> console.log(e))
        },

        // Exporta un archivo consolidado.
        exportConsolidado(){
            this.buttonText1 = 'Downloading...';
            var data = {
                userID: this.userID,
            };
            axios({
                url: `api/exportConsolidado`, data,
                method: 'POST',
                responseType: 'arraybuffer',
            }).then((response) => {
                let blob = new Blob([response.data], {
                    type: 'application/xlsx'
                })
                let link = document.createElement('a')
                link.href = window.URL.createObjectURL(blob)
                link.download = 'Consolidated.xlsx'
                link.click()
                this.buttonText1 = 'Download Consolidated';
                this.toast.success("Consolidated have been downloaded successfully!", {
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
            })
            .catch((error) => {
              if (error.response) {
                // Si hay una respuesta del servidor
                if (error.response.status === 422) {
                  // Error de validación
                  this.toast.warning(`Validation error: ${error.response.data.message}`, {
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
                } else if (error.response.status === 404) {
                  // Recurso no encontrado
                  this.toast.error("Resource not found.", {
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
                } else {
                  // Otro tipo de error
                  this.toast.error(`An error occurred: ${error.response.data.message}`, {
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
              } else if (error.request) {
                // Si la solicitud fue hecha pero no se recibió respuesta
                this.toast.error("No response from server.", {
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
              } else {
                // Otro tipo de error
                this.toast.error(`An error occurred: ${error.message}`, {
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
        },

        // Exporta un archivo individual.
        exportIndividual(){
            this.buttonText2 = 'Downloading...';
            var data = {
                userID: this.userID,
            };
            axios({
                url: `api/exportIndividual`, data,
                method: 'POST',
                responseType: 'arraybuffer',
            }).then((response) => {
                let blob = new Blob([response.data], {
                    type: 'application/xlsx'
                })
                let link = document.createElement('a')
                link.href = window.URL.createObjectURL(blob)
                link.download = 'PlanillaIndividual.xlsx'
                link.click()
                this.buttonText2 = 'Download Individual Return';
                this.toast.success("Individual return have been downloaded successfully!", {
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
            })
            .catch((error) => {
              if (error.response) {
                // Si hay una respuesta del servidor
                if (error.response.status === 422) {
                  // Error de validación
                  this.toast.warning(`Validation error: ${error.response.data.message}`, {
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
                } else if (error.response.status === 404) {
                  // Recurso no encontrado
                  this.toast.error("Resource not found.", {
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
                } else {
                  // Otro tipo de error
                  this.toast.error(`An error occurred: ${error.response.data.message}`, {
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
              } else if (error.request) {
                // Si la solicitud fue hecha pero no se recibió respuesta
                this.toast.error("No response from server.", {
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
              } else {
                // Otro tipo de error
                this.toast.error(`An error occurred: ${error.message}`, {
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
        },

    }

}
</script>

