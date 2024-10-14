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
                            <button @click="deleteSelected" class="btn btn-spacing btn-closed"><i class="fa fa-fw fa-trash"></i> Delete Selected</button>
                            <a class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewBook = true">New Entry</a>
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableBooks">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">User</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Book Title</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Book Authors</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Year</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="book in books" :key="book.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ book.id }}</p>
                                        </td>                                          
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a class="btn btn-alert btn-xs" title="Edit" @click="editBook(book)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verBook(book)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Delete" @click="deleteBook(book.id)"><i class="fa fa-fw fa-trash"></i></a>
                                            </div>
                                        </td>
                                        <td>
                                            <p v-if="book.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ book.status }}</p>
                                            <p v-if="book.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">Registered</p>
                                        </td>                                          
                                        <td>
                                            <p class="text-sm mb-0">{{ book.usuario.name }}</p>
                                        </td>
                                        <td>
                                            <p v-if="book.bookTitle == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0 truncate-text">{{ book.bookTitle }}</p>
                                        </td>
                                        <td>
                                            <p v-if="book.bookAuthors == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ book.bookAuthors }}</p>
                                        </td>
                                        <td>
                                            <p v-if="book.year == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ book.year }}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-3">
                                    <label style="font-weight: 500">
                                    These buttons use the elements selected in the table, if none exist, it will select all the records. </label>
                                </div>
                                <div class="col-auto">
                                    <label title="To select a single record from the table, just do &#013; Click on the box in the first column to select &#013; several consecutive hold SHIFT, to select several &#013; non-consecutive hold CTRL."><span class="badge bg-dark-grey fs-10">?</span></label>
                                </div>
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
    </div>
</template>

<script>
import axios from 'axios'
import modalver from '../../snippets/sistema/books/detailsBooksModal.vue'
import modalcrear from '../../snippets/sistema/books/createBookModal.vue'
import modaleditar from '../../snippets/sistema/books/editBookModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalver ,modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            books: null,
            book: null,
            showDetailsBook: false,
            showNewBook: false,
            showEditBook: false,
            bookEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
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
                    ids: selectedIds
                })
                .then(response => {
                    this.toast.success(response.data.success);
                    this.recargarTabla('General');
                })
                .catch(error => {
                    this.toast.error("An error occurred.");
                });
            }
        },
        verBook(book){
            this.book = book;
            this.showDetailsBook = true;
        },
        getBooks(id){
            axios.get(`api/books/${id}`).then( response =>{
                this.books = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#MyTableBooks');
            }).catch(e=> console.log(e))
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.books = null;
                this.getBooks(this.userID);
            }
            else{
                this.crearTabla("#MyTableBooks");
            }
        },
        editBook(book){
            this.bookEdit = book;
            this.showEditBook = true;
        },
        async deleteBook(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete Book',
                message: `¿Are you sure you want to delete this Book?.`,
                okButton: 'Delete',
                cancelButton: 'Return'
            })
            if (ok) {
                axios.delete(`api/books/${id}`).then( response =>{
                    this.toast.success("Book successfully removed!", {
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
                    this.recargarTabla('General');
                }).catch(e=> console.log(e))
            }
        },
    }
}
</script>