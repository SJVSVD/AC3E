// Formato de las Fechas y Horas:
import moment from "moment/min/moment-with-locales";
moment.locale("es");

// Notificaciones:
import { useToast } from "vue-toastification";

// Usuario que accedió a la web:
let user = document.head.querySelector('meta[name="user"]');
let roles = document.head.querySelector('meta[name="roles"]');

// Formato de los Rut:
import { validateRut } from "@fdograph/rut-utilities";
import { formatRut, RutFormat } from "@fdograph/rut-utilities";

// EXPORT
export const mixin = {
    data: {
        toast: useToast(),
        userName: JSON.parse(user.content).name,
        userID: JSON.parse(user.content).id,
        userEmail: JSON.parse(user.content).email,
        userRoles: JSON.parse(roles.content),
    },
    created() {
        window.addEventListener("keydown", (e) => {
            if (e.key == "Escape") {
                // Colaboradores
                this.showCrearColaborados = false;
                this.showEditarColaborador = false;
                this.showDetalleColaborador = false;

                // Roles
                this.showCrearRol = false;
                this.showEditarRol = false;
                this.showCrearPermiso = false;

                // Usuarios
                this.showCrearUsuario = false;
                this.showEditarUsuario = false;

                // Administración General -> Empresa:
                this.showEditDepartamento = false;
                this.showCreateDepartamento = false;
                this.showEditArea = false;
                this.showCreateArea = false;
                // Administración General -> Ubicación:
                this.showCreateNacionalidad = false;
                this.showEditNacionalidad = false;
                this.showEditPais = false;
                this.showCreatePais = false;
                this.showCreateRegion = false;
                this.showEditRegion = false;
                this.showCreateComuna = false;
                this.showEditComuna = false;
                // Administración General -> Tiendas:
                this.showEditPromotor = false;
                this.showCreatePromotor = false;
                this.showEditSupervisor = false;
                this.showCreateSupervisor = false;
                this.showEditSucursal = false;
                this.showCreateSucursal = false;
                this.showDetallesSucursal = false;
                // Administración General -> Usuarios:
                this.showEditSistemaSalud = false;
                this.showCreateSistemaSalud = false;
                this.showCreateAFP = false;
                this.showEditAFP = false;
                this.showEditEstadoCivil = false;
                this.showCreateEstadoCivil = false;
                // Administración General -> Otros:

                // Robos
                this.showEditarUsuario = false;
                this.showCrearRobo = false;
                this.showDetallesRobo = false;

                // Rendiciones
                this.showCrearRendicion = false;
                if (this.showFotoRendicion == true) {
                } else {
                    this.showDetalleRendicion = false;
                }
                this.showRendir = false;
                this.showAutorizarPago = false;
                this.showAutorizarRendicion = false;

                // Fofis
                this.showCrearFofi = false;
                this.showAutorizarFondosFijos = false;
                if (this.showFotoFofi == true) {
                } else {
                    this.showDetalleFofi = false;
                }

                // Facturas
                this.showUploadDataFacturas = false;
                this.showUploadReferencesFacturas = false;
                this.showEditarFactura = false;
                this.showCrearFactura = false;
                this.showDetallesFactura = false;
                this.showAutorizarGastosFacturas = false;
                this.showAutorizarPagosFacturas = false;

                // Menú Gestion Tienda
                this.showConversionVentas = false;
                this.showPrecios = false;
                this.showIngresarCierreCaja = false;
                this.showIngresarDeposito = false;
                this.showDetalleCierreCaja = false;
            }
        });
    },
    computed: {
        todayDate() {
            return moment().format("DD-MM-YYYY");
        },
        todayDay() {
            return moment().format("DD");
        },
        todayMonth() {
            return moment().format("MM");
        },
        todayYear() {
            return moment().format("YYYY");
        },
        todayCalendar() {
            return moment().calendar();
        },
    },
    methods: {
        crearTabla(nombreTabla) {
            setTimeout(
                function () {
                    this.table = $(nombreTabla).DataTable({});
                    this.mostrarCarga = false;
                    this.mostrarTabla = true;
                    $("a.toggle-vis").on("click", function (e) {
                        e.preventDefault();
                        var column = this.table.column(
                            $(this).attr("data-column")
                        );
                        column.visible(!column.visible());
                    });
                    this.table
                        .on("click", "th.select-checkbox", function () {
                            if ($("th.select-checkbox").hasClass("selected")) {
                                this.table.rows().deselect();
                                $("th.select-checkbox").removeClass("selected");
                            } else {
                                this.table.rows().select();
                                $("th.select-checkbox").addClass("selected");
                            }
                        })
                        .on("select deselect", function () {
                            ("Some selection or deselection going on");
                            if (
                                this.table
                                    .rows({
                                        selected: true,
                                    })
                                    .count() !== this.table.rows().count()
                            ) {
                                $("th.select-checkbox").removeClass("selected");
                            } else {
                                $("th.select-checkbox").addClass("selected");
                            }
                        });
                }.bind(this),
                100
            );
        },
        agregarMinutos(date, minutes) {
            return new Date(date.getTime() + minutes * 60000);
        },
        quitarMinutos(date, minutes) {
            return new Date(date.getTime() - minutes * 60000);
        },
        validarRut(value) {
            var validado = validateRut(value);
            return validado;
        },
        formatoRut(value) {
            var formato = formatRut(value, RutFormat.DOTS_DASH);
            return formato;
        },
        clearInput(id) {
            var getValue = document.getElementById(id);
            if (getValue.value != "") {
                getValue.value = "";
            }
        },
        formatMoneyCLP(value) {
            return new Intl.NumberFormat("en-EN", {
                currency: "CLP",
                currencyDisplay: "narrowSymbol",
                style: "currency",
            }).format(value);
        },
        formatMoneyUSD(value) {
            return new Intl.NumberFormat("es-ES", {
                style: "currency",
                currency: "USD",
            }).format(value);
        },
        formatMoneyEUR(value) {
            return new Intl.NumberFormat("es-ES", {
                style: "currency",
                currency: "EUR",
            }).format(value);
        },
        formatMoneyGBP(value) {
            return new Intl.NumberFormat("es-ES", {
                style: "currency",
                currency: "GBP",
            }).format(value);
        },
        formatMoneyJPY(value) {
            return new Intl.NumberFormat("es-ES", {
                style: "currency",
                currency: "JPY",
            }).format(value);
        },
        thisDate($fecha) {
            if ($fecha == null) {
                return "Sin ingreso.";
            } else {
                return moment($fecha).format("DD-MM-YYYY");
            }
        },
        distanceFromNow($fecha) {
            var retorno = moment($fecha).fromNow();
            return retorno;
        },
        distanceFromOther($fecha1, $fecha2) {
            var retorno = moment($fecha2).diff(moment($fecha1), "days");
            return retorno;
        },
        daysInMonth($mes) {
            return moment().month($mes).daysInMonth();
        },
    },
};
