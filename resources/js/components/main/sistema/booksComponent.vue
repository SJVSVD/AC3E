<template>
    <div class="row mt-4 mx-4">
      <div class="col-12">
        <div class="card mb-4">
          <div class="row pb-0 p-4">
            <div class="col-lg-10 col-md-12">
              <div class="info-box">
                Includes all books or book chapters published with authorship by AC3E researchers, during the current baseline year (Sep-present).
              </div>
            </div>
            <div class="col-lg-2 col-md-12 d-flex justify-content-lg-end justify-content-center align-items-center">
              <div class="d-flex">
                <button v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" @click="deleteSelected" class="btn btn-spacing btn-closed">
                  <i class="fa fa-fw fa-trash"></i>  Selected Records
                </button>
                <a v-if="!is('Staff') && !is('Anid')" class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewBook = true"><i class="fa-solid fa-add"></i></a>
                <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')">
                  <i class="fa-solid fa-rotate"></i>
                </a>
              </div>
            </div>
          </div>
  
          <!-- ProgressReport Filter -->
          <div class="row px-4 mb-2">
            <div class="col-lg-2 col-md-6">
              <label for="progressReportFilter" class="form-label">Filter By Progress Report Year:</label>
              <select
                id="progressReportFilter"
                class="form-select"
                v-model="selectedProgressReport"
                @change="filterByProgressReport"
              >
                <option value="">All</option>
                <option v-for="progress in uniqueProgressReports" :key="progress" :value="progress">
                  {{ progress }}
                </option>
              </select>
            </div>
          </div>
  
          <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
            <div class="container">
              <div class="table-responsive p-0">
                <div v-show="mostrarCarga" class="loader-sm"></div>
                <table v-show="mostrarTabla" class="table table-striped align-items-center mb-0" id="MyTableBooks">
                  <thead class="thead-light">
                    <tr >
                      <th style="width: 16px;"></th>
                      <th data-orderable="true" class="text-xs font-weight-bold text-left">ID</th>
                      <th data-orderable="false" class="text-xs font-weight-bold text-left">Actions</th>
                      <th data-orderable="false" class="text-xs font-weight-bold text-left">Status</th>
                      <th data-orderable="true" class="text-xs font-weight-bold text-left">User</th>
                      <th data-orderable="true" class="text-xs font-weight-bold text-left">Progress Report Year</th>
                      <th data-orderable="true" class="text-xs font-weight-bold text-left">Book Title</th>
                      <th data-orderable="true" class="text-xs font-weight-bold text-left">Book Authors</th>
                      <th data-orderable="true" class="text-xs font-weight-bold text-left">Work Type</th>
                      <th data-orderable="true" class="text-xs font-weight-bold text-left">Year</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="book in filteredBooks" :key="book.id">
                      <td></td>
                      <td>
                        <p class="text-sm font-weight-bold">{{ book.id }}</p>
                      </td>
                      <td class="text-left">
                        <div class="d-flex px-1 py-1 justify-content-start align-items-center">
                          <a v-if="!is('Staff') && !is('Anid') && (!is('Titular Researcher') || book.idUsuario == userID)" class="btn btn-alert btn-xs" title="Edit" @click="editBook(book)">
                            <i class="fa fa-fw fa-edit"></i>
                          </a>
                          &nbsp;
                          <a class="btn btn-success btn-xs" title="Details" @click="verBook(book)">
                            <i class="fa-regular fa-eye"></i>
                          </a>
                          &nbsp;
                          <a v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')|| book.idUsuario == userID"  
                            class="btn btn-closed btn-xs" title="Delete" @click="deleteBook(book.id)">
                            <i class="fa fa-fw fa-trash"></i>
                          </a>
                        </div>
                      </td>
                      <td class="text-start">
                        <span v-if="book.status == 'Draft'" class="badge bg-alert">Draft</span>
                        <span v-else-if="book.status == 'Finished'" class="badge bg-success">Registered</span>
                        <span v-else class="badge bg-secondary">No information</span>
                      </td>
                      <td class="text-sm text-nowrap" :title="book.usuario.name">
                        {{ truncateText(book.usuario.name, 60) }}
                      </td>
                      <td class="text-sm text-nowrap" :title="book.progressReport">
                        {{ truncateText(book.progressReport, 60) }}
                      </td>
                      <td class="text-sm text-start text-nowrap" :title="book.bookTitle || '---'">
                        {{ truncateText(book.bookTitle || '---', 60) }}
                      </td>
                      <td class="text-sm text-start text-nowrap" :title="book.bookAuthors || '---'">
                        {{ truncateText(book.bookAuthors || '---', 60) }}
                      </td>
                      <td class="text-sm text-start text-nowrap">{{ book.workType || '---' }}</td>
                      <td class="text-sm text-nowrap">{{ book.year || '---' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <modalconfirmacion ref="confirmation"></modalconfirmacion>
      <modalalerta ref="alert"></modalalerta>
      <modalver v-bind:book1="book" v-if="showDetailsBook" @close="showDetailsBook = false"></modalver>
      <modaleditar v-bind:book1="bookEdit" v-if="showEditBook" @close="showEditBook = false" @recarga="recargarTabla('General')"></modaleditar>
      <modalcrear v-if="showNewBook" @close="showNewBook = false" @recarga="recargarTabla('General')"></modalcrear>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import modalver from '../../snippets/sistema/books/detailsBooksModal.vue';
  import modalcrear from '../../snippets/sistema/books/createBookModal.vue';
  import modaleditar from '../../snippets/sistema/books/editBookModal.vue';
  import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue';
  import modalalerta from '../../snippets/sistema/alerts/alertModal.vue';
import {mixin} from '../../../mixins.js'

  export default {
    components: { modalver, modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data() {
      return {
        books: [],
        filteredBooks: [],
        uniqueProgressReports: [],
        selectedProgressReport: '',
        showDetailsBook: false,
        showNewBook: false,
        showEditBook: false,
        book: null,
        bookEdit: null,
        mostrarTabla: false,
        mostrarCarga: true,
        table: null,
      };
    },
    created() {
      this.getBooks(this.userID);
    },
    methods: {
      async deleteSelected() {
          // Obtener las filas seleccionadas
          let selectedData = this.table.rows({ selected: true }).data().toArray();
          let selectedIds = selectedData.map(row => {
              // Supongamos que el ID está en la segunda columna (índice 1)
              const tempDiv = document.createElement("div");
              tempDiv.innerHTML = row[1]; // Cambia el índice según dónde esté el ID
              return tempDiv.textContent.trim(); // Obtener el contenido de texto que es el ID
          });
          let module = 'books'; // Cambia este valor dinámicamente según el módulo
          if (selectedIds.length === 0) {
              this.toast.error("No records selected.");
              return;
          }
          const ok = await this.$refs.confirmation.show({
              title: 'Delete Multiple',
              message: `¿Are you sure you want to delete `+selectedIds.length+` records?.`,
              okButton: 'Delete',
              cancelButton: 'Return'
          })
          if (ok) { 
            axios.post('api/delete-records', {
                module: module,
                ids: selectedIds,
                user_id: this.userID // Asegúrate de que `this.currentUserId` contiene el ID del usuario actual
            })
            .then(response => {
                this.toast.success(`${response.data.deletedCount} records successfully deleted!`);
                this.recargarTabla(module);
            })
            .catch(error => {
                console.error('Error deleting records:', error);
                this.toast.error('Failed to delete records.');
            });
          }
      },
      verBook(book){
          this.book = book;
          this.showDetailsBook = true;
      },
      truncateText(text, maxLength) {
        if (!text) return '---';
        return text.length > maxLength ? `${text.substring(0, maxLength)}...` : text;
      },
      async getBooks(id) {
        try {
          const response = await axios.get(`api/books/${id}`);
          this.books = response.data;
          this.filteredBooks = [...this.books];
          this.uniqueProgressReports = [
            ...new Set(this.books.map(book => book.progressReport).filter(Boolean)),
          ].sort((a, b) => a - b);
          if (this.table != null) {
            this.table.destroy();
          }
          this.crearTabla('#MyTableBooks');
        } catch (error) {
          console.error('Error fetching books:', error);
        }
      },
      filterByProgressReport() {
        this.mostrarCarga = true;
        if (this.selectedProgressReport === '') {
          this.filteredBooks = [...this.books];
        } else {
          this.filteredBooks = this.books.filter(
            book => book.progressReport === this.selectedProgressReport
          );
        }
        if (this.table != null) {
          this.table.destroy();
        }
        setTimeout(() => {
          this.crearTabla('#MyTableBooks');
          this.mostrarCarga = false;
        }, 500);
      },
      recargarTabla(type) {
        this.mostrarCarga = true;
        if (type === 'General') {
          this.getBooks(this.userID);
        } else {
          this.crearTabla('#MyTableBooks');
        }
      },
      editBook(book) {
        this.bookEdit = book;
        this.showEditBook = true;
      },
      async deleteBook(id) {
        const ok = await this.$refs.confirmation.show({
          title: 'Delete Book',
          message: `Are you sure you want to delete this book?`,
          okButton: 'Delete',
          cancelButton: 'Cancel',
        });
        if (ok) {
          try {
            const userId = this.userID; // Asegúrate de que `currentUserId` tenga el ID del usuario logueado.
            await axios.delete(`api/books/${id}`, {
              data: { user_id: userId }, // Incluye el user_id en el cuerpo de la solicitud.
            });
            this.toast.success('Book successfully deleted.', {
              position: 'top-right',
              timeout: 3000,
              closeOnClick: true,
              pauseOnFocusLoss: true,
              pauseOnHover: true,
              draggable: true,
            });
            this.recargarTabla('General');
          } catch (error) {
            console.error('Error deleting book:', error);
          }
        }
      },
    },
  };
  </script>
  