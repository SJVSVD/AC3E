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
        <div v-if="activeDataCollection" class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card overflow-hidden" style="height: 200px;">
                <div class="card-body p-3 text-white  background-size" style="background-color: #4d4d4d; min-height: 160px;">
                    <div class="row">
                        <div class="col-12">
                            <div class="numbers" style="min-height: 80px; text-align: center; margin-top: 10px;">
                                <p class="text-sm mb-0 text-uppercase text-white fw-bold">Data Collection Countdown</p>
                                <h4 style="color: red;" class="fw-bolder">
                                    {{ timeCountdown }}
                                </h4>
                                Once you have finished uploading your information, you must notify by clicking the button below:
                                <a v-if="!notify" class="btn btn-dark-grey " title="Notify Data Collection as Complete"  @click="notifyData">
                                    <i class="fa-solid fa-envelope-circle-check"></i>&nbsp; Notify Data Collection as Complete
                                </a>
                                <a v-else class="btn btn-orange ">
                                    You have already notified
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
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
        <div class="col-lg-10">
            <div class="card z-index-2 p-0" style="min-height: 200px; max-height: 650px;">
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
                            <tr v-for="(registro, created_at) in registros" :key="created_at">
                                <td></td>
                                <td>{{ thisDate(registro.created_at,true) }}</td>
                                <td v-if="registro.modulo == 'isiPublication'">WoS Publications</td>
                                <td v-else-if="registro.modulo == 'scCollaborations' && registro.moduleType == '1'">Collaborative Projects</td>
                                <td v-else-if="registro.modulo == 'fundingSources'">Funding Sources</td>
                                <td v-else-if="registro.modulo == 'organizationsScEvents'">Organization Sc Events</td>
                                <td v-else-if="registro.modulo == 'outreachActivities'">Outreach Activities</td>
                                <td v-else-if="registro.modulo == 'participationScEvents'">Participation Sc Events</td>
                                <td v-else-if="registro.modulo == 'patents'">Intellectual Property</td>
                                <td v-else-if="registro.modulo == 'postDoc'">Postdoctoral Fellows</td>
                                <td v-else-if="registro.modulo == 'publicPrivate'">Public-Private Connections</td>
                                <td v-else-if="registro.modulo == 'scCollaborations' && registro.moduleType == '0'">Visits And Stays</td>
                                <td v-else-if="registro.modulo == 'nonIsiPublication'">Non WoS Publications</td>
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
        <div class="col-lg-2">
            <div class="card z-index-2 p-0" style="max-height: 650px;">
                <div class="row p-3">
                    <!-- <button @click="updateResearchLines">Actualizar Research Lines</button> -->
                    <!-- Botón de Export Consolidado (solo para Administrador) -->
                    <div class="col-12" v-if="can('See_Download_Database')">
                        <a class="btn btn-search-blue w-100 d-flex justify-content-center align-items-center" :title="buttonText1" style="min-height: 40px; min-width: 20px;" @click="exportConsolidado">
                            <i class="fa fa-fw fa-download"></i>&nbsp;{{ buttonText1 }}
                        </a>
                    </div>
                    <!-- Botón de Export Individual-->
                    <div v-if="can('See_Individual_Export')" class="col-12 pt-1">
                        <a class="btn btn-purple w-100 d-flex justify-content-center align-items-center" :title="buttonText2"  style="min-height: 40px; min-width: 20px;" @click="exportIndividual">
                            <i class="fa fa-fw fa-download"></i>&nbsp;{{ buttonText2 }}
                        </a>
                    </div>
                    <!-- Botón de Ver Actividad Reciente -->
                    <div class="col-12 pt-1" v-if="is('Administrator')">
                        <a class="btn btn-orange w-100 d-flex justify-content-center align-items-center" style="min-height: 40px; min-width: 20px;" title="View recent activity" @click="showVerActividadReciente = true">
                            <i class="fa fa-history"></i>&nbsp; View recent activity
                        </a>
                    </div>
                    <!-- Botón de Modo levantamiento -->
                    <div class="col-12 pt-1" v-if="is('Administrator')">
                        <a class="btn btn-continue w-100 d-flex justify-content-center align-items-center" style="min-height: 40px; min-width: 20px;" title="Display New Message" @click="showLiftMode = true">
                            <i class="fa-solid fa-envelope"></i>&nbsp; Display New Message
                        </a>
                    </div>
                    <!-- Botón de Performance Data Collection Mode -->
                    <div class="col-12 pt-1" v-if="is('Administrator')">
                        <a class="btn btn-closed w-100 d-flex justify-content-center align-items-center" style="min-height: 40px; min-width: 20px;" title="Activate Performance Data Collection Mode" @click="showDataCollection = true">
                            <i class="fa-solid fa-clock"></i>Performance Data Collection Mode
                        </a>
                    </div>
                </div>
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
        <modalrecent v-bind:sessions1="sessions" v-if="showVerActividadReciente" @close="showVerActividadReciente = false"></modalrecent>
        <modalliftmode v-if="showLiftMode" @close="showLiftMode = false"></modalliftmode>
        <modaldatacollection v-if="showDataCollection" @close="showDataCollection = false"></modaldatacollection>
        <modalconfirmdata v-bind:iduser="userID" v-if="showConfirmData" @close="showConfirmData = false"></modalconfirmdata>
        <modalconfirmacion ref="confirmation"></modalconfirmacion>
        <modalactiveannouncement  v-bind:activeAnnouncement1="activeAnnouncement" v-if="activeAnnouncement" @close="activeAnnouncement = null"></modalactiveannouncement>
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
import modalrecent from './snippets/sistema/recentActivityModal.vue'
import modalliftmode from './snippets/sistema/liftModeModal.vue'
import modaldatacollection from './snippets/sistema/dataCollectionModal.vue'
import modalconfirmdata from './snippets/sistema/confirmDataModal.vue'
import modalactiveannouncement from './snippets/sistema/activeAnnouncementModal.vue'
import modalalerta from './snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../mixins.js'
import * as XLSX from 'xlsx';

export default {
    components: { modalconfirmdata,modaldatacollection,modalactiveannouncement, modalliftmode, modalrecent,modalconfirmacion,modalalerta,modalver,modalver1,modalver2,modalver3,modalver4,modalver5,modalver6,modalver7,modalver8,modalver9,modalver10,modalver11,modalver12,modalver13,modalver14 },
    mixins: [mixin],
    data() {
        return {
            activeAnnouncement: null,
            cantidadRegistros: 10,
            registros: [],
            buttonText1: 'Download Database',
            buttonText2: 'Export personal results',
            CLPRates: null,
            intervalRealTime: null,
            intervalCountDown: null,
            time: null,
            timeCountdown: null,
            weather: null,
            coords: null,
            usuariosActivos: [],
            sessions: [],
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
            notify: false,
            researcher: false,
            activeDataCollection: false,
            showConfirmData: false,
            showLiftMode: false,
            showDataCollection: false,
            showVerActividadReciente: false,
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
        clearInterval(this.intervalCountDown);  // Limpiar el intervalo de la cuenta regresiva
        clearInterval(this.intervalRealTime);   // Limpiar el intervalo de la hora real
        },
    created() {
        // Inicializa el intervalo para mostrar la hora real
        this.intervalRealTime = setInterval(() => {
            const options = {
                year: 'numeric', month: 'numeric', day: 'numeric',
                hour: 'numeric', minute: 'numeric', second: 'numeric',
                hour12: false,
            };
            this.time = Intl.DateTimeFormat(navigator.language, options).format();
        }, 1000);
    },
    mounted(){
        this.getRegistros(this.cantidadRegistros);
        this.getRecentSessions();
        this.fetchActiveAnnouncement();
        this.fetchDataCollection();
    },
    methods: {
        async notifyData(){
            this.showConfirmData = true;
        },
        async fetchDataCollection() {
            try {
                const { data } = await axios.get('/api/performance-mode', {
                    params: { user_id: this.userID }
                });
                console.log(data);
                this.notify = data.notify;
                this.researcher = data.researcher;
                // Limpia los intervalos previos
                clearInterval(this.intervalCountDown);
                
                if (data.mode.is_active && data.researcher) {

                    if(!data.notify){
                        this.activeAnnouncement = {
                            message: "AAAAAAAAAAA"
                        };
                    }

                    const deadline = new Date(data.mode.deadline).getTime(); // Convierte el deadline a timestamp
                    
                    // Intervalo para la cuenta regresiva
                    this.intervalCountDown = setInterval(() => {
                        const now = new Date().getTime(); // Obtiene el tiempo actual
                        const distance = deadline - now; // Calcula la diferencia

                        if (distance > 0) {
                            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                            this.timeCountdown = `${days}d ${hours}h ${minutes}m ${seconds}s`; // Muestra la cuenta regresiva
                        } else {
                            this.timeCountdown = "La cuenta regresiva ha terminado"; // Si pasó el tiempo
                            clearInterval(this.intervalCountDown); // Detiene el intervalo de cuenta regresiva
                        }
                    }, 1000); // Actualiza cada segundo
                    this.activeDataCollection = true;
                }
            } catch (error) {
                console.error('Error fetching the active announcement:', error);
            }
        },

        async fetchActiveAnnouncement() {
            try {
                const { data } = await axios.get('/api/active-announcement');
                if (data.message) {
                    this.activeAnnouncement = data; // Asigna el anuncio si existe
                }
            } catch (error) {
                console.error('Error fetching the active announcement:', error);
            }
        },
        async updateResearchLines() {
            try {
                // Llamada a la API usando Axios
                const response = await axios.get('/api/update-research-lines', {
                // Si necesitas enviar algún dato, agrégalo aquí
                }, {
                });
                
                // Manejar la respuesta
                console.log('Respuesta:', response.data);
                alert('Research Lines actualizadas con éxito.');
            } catch (error) {
                // Manejar errores
                console.error('Error al actualizar Research Lines:', error);
                alert('Hubo un error al actualizar las Research Lines.');
            }
        },
        // Método para obtener los registros de sesión recientes
        getRecentSessions() {
            axios.get('/api/session-logs')
            .then(response => {
                this.sessions = response.data;
            })
            .catch(error => {
                console.error('Error al cargar los registros de sesión:', error);
            });
        },
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
            if(this.userRoles.includes('Administrator')){
                axios.get(`api/getRegistros/${cantidad}`).then( response =>{
                    this.registros = response.data;
                    if (this.table != null){
                        this.table.clear();
                        this.table.destroy();
                    }
                    this.crearTabla('#myTableRegistros');
                    this.table.buttons().remove();
                }).catch(e=> console.log(e))
            }else{
                axios.get(`api/getRegistrosUser/${this.userID}/${cantidad}`).then( response =>{
                    this.registros = response.data;
                    if (this.table != null){
                        this.table.clear();
                        this.table.destroy();
                    }
                    this.crearTabla('#myTableRegistros');
                    this.table.buttons().remove();
                }).catch(e=> console.log(e))
            }
            
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
                this.buttonText2 = 'Export personal results';
                this.toast.success("Personal results have been downloaded successfully!", {
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
                }else if (error.response.status === 400) {
                  // Recurso no encontrado
                  this.toast.error(`An error occurred: The user has no associated publications.`, {
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
                }else {
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
            this.buttonText2 = 'Export personal results';
        },

    }

}
</script>

