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
                                <td v-if="registro.modulo == 'isiPublication'">Isi Publications</td>
                                <td v-else-if="registro.modulo == 'scCollaborations' && registro.moduleType == '1'">Conjoint Projects</td>
                                <td v-else-if="registro.modulo == 'fundingSources'">Funding Sources</td>
                                <td v-else-if="registro.modulo == 'organizationsScEvents'">Organization Sc Events</td>
                                <td v-else-if="registro.modulo == 'outreachActivities'">Outreach Activities</td>
                                <td v-else-if="registro.modulo == 'participationScEvents'">Participation Sc Events</td>
                                <td v-else-if="registro.modulo == 'patents'">Patents</td>
                                <td v-else-if="registro.modulo == 'postDoc'">Postdoctoral Fellows</td>
                                <td v-else-if="registro.modulo == 'publicPrivate'">Public-Private Connections</td>
                                <td v-else-if="registro.modulo == 'scCollaborations' && registro.moduleType == '0'">Sc Collaborations</td>
                                <td v-else-if="registro.modulo == 'nonIsiPublication'">Non Isi Publications</td>
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
                    <div class="col-6">
                        <a class="btn btn-primary" v-if="is('Administrator')" @click="exportConsolidado()"><i class="fa fa-fw fa-lg fa-solid fa-download"></i> Export Consolidated</a>
                    </div>
                    <div class="col-6">
                        <a class="btn btn-primary" @click="exportIndividual()"><i class="fa fa-fw fa-lg fa-solid fa-download"></i> Export Individual Return</a>
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

export default {
    components: { modalconfirmacion,modalalerta,modalver,modalver1,modalver2,modalver3,modalver4,modalver5,modalver6,modalver7,modalver8,modalver9,modalver10,modalver11,modalver12,modalver13,modalver14 },
    mixins: [mixin],
    data() {
        return {
            cantidadRegistros: 10,
            registros: [],
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
        }
    },
    beforeDestroy() {
        clearInterval(this.interval);
    },
    created() {
        this.getUsuariosActivos();
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
        verConjointProjects(conjointProject) {
            this.conjointProjects = conjointProject;
            this.showDetailsConjointProjects = true;
        },
        verFundingSources(fundingSource) {
            this.fundingSources = fundingSource;
            this.showDetailsFundingSources = true;
        },
        verOrganizationScEvents(organizationScEvent) {
            this.organizationScEvents = organizationScEvent;
            this.showDetailsOrganizationScEvents = true;
        },
        verOutreachActivities(outreachActivity) {
            this.outreachActivities = outreachActivity;
            this.showDetailsOutreachActivities = true;
        },
        verParticipationScEvents(participationScEvent) {
            this.participationScEvents = participationScEvent;
            this.showDetailsParticipationScEvents = true;
        },
        verPatents(patent) {
            this.patents = patent;
            this.showDetailsPatents = true;
        },
        verPostDoc(postDoc) {
            this.postDoc = postDoc;
            this.showDetailsPostDoc = true;
        },
        verPublicPrivate(publicPrivate) {
            this.publicPrivate = publicPrivate;
            this.showDetailsPublicPrivate = true;
        },
        verScCollaborations(scCollaboration) {
            this.scCollaborations = scCollaboration;
            this.showDetailsScCollaborations = true;
        },
        verNonIsiPublications(nonIsiPublication) {
            this.nonIsiPublications = nonIsiPublication;
            this.showDetailsNonIsiPublications = true;
        },
        verThesisStudents(thesisStudent) {
            this.thesisStudents = thesisStudent;
            this.showDetailsThesisStudents = true;
        },
        verAwards(award) {
            this.awards = award;
            this.showDetailsAwards = true;
        },
        verBooks(book) {
            this.books = book;
            this.showDetailsBooks = true;
        },
        verTechnologyKnowledge(technologyKnowledge) {
            this.technologyKnowledge = technologyKnowledge;
            this.showDetailsTechnologyKnowledge = true;
        },
        verIsiPublication(isiPublication){
            this.isiPublication = isiPublication;
            this.showDetailsIsi = true;
        },
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
        exportConsolidado(){
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
                link.download = 'Consolidado.xlsx'
                link.click()
            })
        },
        exportIndividual(){
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
            })
        },
        carouselInterval(){
            $('.carousel').carousel({
                interval: 3000
            });
        },
        getUsuariosActivos(){
            axios.get('api/usuarios').then( response =>{
                this.usuariosActivos = response.data;
            }).catch(e=> console.log(e))
        },
    }
}
</script>