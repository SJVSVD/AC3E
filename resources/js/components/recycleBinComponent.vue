<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
              <div class="row pb-0 p-4">
                  <div class="col-12">
                      <div class="d-flex justify-content-end">
                        <button @click="restaurarSeleccionados" class="btn btn-spacing btn-search-blue"><i class="fas fa-trash-restore"></i> Restore Selected</button>
                          <button @click="eliminarSeleccionados" class="btn btn-spacing btn-closed"><i class="fa fa-fw fa-trash"></i> Delete Selected</button>
                          <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                      </div>
                  </div>
              </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="myTableRecycleBin">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Deleted At</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">User</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Module</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="registro in indicators" :key="indicators.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ registro.id }}</p>
                                        </td>    
                                        <td v-if="registro.module == 'isiPublication'"><a class="btn btn-success btn-xs" title="Details" @click="verIsiPublication(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                        <td v-else-if="registro.module == 'scCollaborations' && registro.moduleType == '1'"><a class="btn btn-success btn-xs" title="Details" @click="verConjointProjects(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                        <td v-else-if="registro.module == 'fundingSources'"><a class="btn btn-success btn-xs" title="Details" @click="verFundingSources(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                        <td v-else-if="registro.module == 'organizationsScEvents'"><a class="btn btn-success btn-xs" title="Details" @click="verOrganizationScEvents(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                        <td v-else-if="registro.module == 'outreachActivities'"><a class="btn btn-success btn-xs" title="Details" @click="verOutreachActivities(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                        <td v-else-if="registro.module == 'participationScEvents'"><a class="btn btn-success btn-xs" title="Details" @click="verParticipationScEvents(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                        <td v-else-if="registro.module == 'patents'"><a class="btn btn-success btn-xs" title="Details" @click="verPatents(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                        <td v-else-if="registro.module == 'postDoc'"><a class="btn btn-success btn-xs" title="Details" @click="verPostDoc(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                        <td v-else-if="registro.module == 'publicPrivate'"><a class="btn btn-success btn-xs" title="Details" @click="verPublicPrivate(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                        <td v-else-if="registro.module == 'scCollaborations' && registro.moduleType == '0'"><a class="btn btn-success btn-xs" title="Details" @click="verScCollaborations(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                        <td v-else-if="registro.module == 'nonIsiPublication'"><a class="btn btn-success btn-xs" title="Details" @click="verNonIsiPublications(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                        <td v-else-if="registro.module == 'thesisStudent'"><a class="btn btn-success btn-xs" title="Details" @click="verThesisStudents(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                        <td v-else-if="registro.module == 'awards'"><a class="btn btn-success btn-xs" title="Details" @click="verAwards(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                        <td v-else-if="registro.module == 'books'"><a class="btn btn-success btn-xs" title="Details" @click="verBooks(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                        <td v-else-if="registro.module == 'technologyKnowledge'"><a class="btn btn-success btn-xs" title="Details" @click="verTechnologyKnowledge(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                        <td v-else>---</td> 
                                        <td>{{ this.thisDate(registro.deleted_at) }}</td>
                                        <td>{{ registro.usuario.name }}</td>
                                        <td v-if="registro.module == 'isiPublication'">WoS Publications</td>
                                        <td v-else-if="registro.module == 'scCollaborations' && registro.moduleType == '1'">Collaborative Projects</td>
                                        <td v-else-if="registro.module == 'fundingSources'">Funding Sources</td>
                                        <td v-else-if="registro.module == 'organizationsScEvents'">Organization Sc Events</td>
                                        <td v-else-if="registro.module == 'outreachActivities'">Outreach Activities</td>
                                        <td v-else-if="registro.module == 'participationScEvents'">Participation Sc Events</td>
                                        <td v-else-if="registro.module == 'patents'">Intellectual Property</td>
                                        <td v-else-if="registro.module == 'postDoc'">Postdoctoral Fellows</td>
                                        <td v-else-if="registro.module == 'publicPrivate'">Public-Private Connections</td>
                                        <td v-else-if="registro.module == 'scCollaborations' && registro.moduleType == '0'">Visits And Stays</td>
                                        <td v-else-if="registro.module == 'nonIsiPublication'">Non WoS Publications</td>
                                        <td v-else-if="registro.module == 'thesisStudent'">Thesis Students</td>
                                        <td v-else-if="registro.module == 'awards'">Awards</td>
                                        <td v-else-if="registro.module == 'books'">Books</td>
                                        <td v-else-if="registro.module == 'technologyKnowledge'">Tec. and Know Transfer</td>
                                        <td v-else>---</td> 
    
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-3">
                                    <label style="font-weight: 500">These buttons use the elements selected in the table, if none exist, it will select all the records. </label>
                                </div>
                                <div class="col-auto">
                                    <label title="To select a single record from the table, just do &#013; Click on the box in the first column to select &#013; several consecutive hold SHIFT, to select several &#013; non-consecutive hold CTRL."><span class="badge bg-dark-grey fs-10">?</span></label>
                                </div>
                            </div>
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
            <modalconfirmacion ref="confirmation"></modalconfirmacion>
            <modalalerta ref="alert"></modalalerta>
        </div>
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
  import {mixin} from './../mixins.js'
  
  export default {
    components: { modalconfirmacion,modalalerta,modalver,modalver1,modalver2,modalver3,modalver4,modalver5,modalver6,modalver7,modalver8,modalver9,modalver10,modalver11,modalver12,modalver13,modalver14 },
    mixins: [mixin],
    data(){
        return{
            indicators: null,
            usuarioeditar: null,
            mostrarTabla: false,
            mostrarCarga: true,
            permisos: null,
            table: null,
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
            progressNumbers: [],
        }
    },
    created(){
        this.getRecords();
    },
    methods: {
        obtenerRegistrosSeleccionados() {
            const datosSeleccionados = this.table
            .rows({ selected: true })
            .nodes() // obtener los nodos DOM seleccionados
            .toArray();

            // Mapeamos para extraer el ID dentro de la etiqueta <p> en la primera celda (o donde corresponda)
            return datosSeleccionados.map((row) => {
            // Buscamos la etiqueta <p> dentro de la primera celda de la fila
            const idText = $(row).find("td:nth-child(2) p").text(); // Ajusta esto si el ID está en otra columna
            const moduloText = $(row).find("td:nth-child(6)").text();
                console.log(moduloText);
            return {
                id: idText.trim(),
                modulo: moduloText.trim() // Asegúrate de que esta columna contenga el nombre del módulo
            };
            });
        },

        async restaurarSeleccionados() {
            const registros = this.obtenerRegistrosSeleccionados();

            if (registros.length === 0) {
                this.toast.error("No records selected.");
                return;
            }
            try {
                await axios.post("/api/recycle-bin/restore", { registros });
                alert("Registros restaurados con éxito.");
                this.toast.success("Records restored successfully.");
                this.recargarTabla("General");
            } catch (error) {
                this.toast.error("Error restoring records:", error);
            }
        },

        async eliminarSeleccionados() {
            const registros = this.obtenerRegistrosSeleccionados();

            console.log(registros);
            if (registros.length === 0) {
                this.toast.error("No records selected.");
                return;
            }
            try {
                await axios.post("/api/recycle-bin/delete", { registros });
                this.toast.success("Records permanently deleted successfully.");
                this.recargarTabla("General");
            } catch (error) {
                this.toast.error("Error deleting records:", error);
            }
        },
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

  
      // Recarga la tabla dependiendo del tipo de recarga.
      recargarTabla($tipoRecarga){
          this.mostrarCarga = true;
          if($tipoRecarga == 'General'){
              this.indicators = null;
              this.getRecords();
          }else{
              this.table.destroy();
              this.crearTabla('#myTableRecycleBin');
          }
      },
  
      // Obtiene los indicadores y actualiza la tabla.
      getRecords(){
          axios.get('api/recycle-bin').then( response =>{
              this.indicators = response.data;
              if (this.table != null){
                  this.table.clear();
                  this.table.destroy();
              }
              this.crearTabla("#myTableRecycleBin");
          }).catch(e=> console.log(e))
      },
    }
  }
  </script>