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
        isEmpty(object) {
            return Object.keys(object).length === 0;
        },
        isValidURL(url) {
            try {
                new URL(url); // Verifica si es una URL válida
                return true;
            } catch (_) {
                return false;
            }
        },
        crearTabla(nombreTabla) {
            setTimeout(() => {
                this.table = $(nombreTabla).DataTable({});
                this.mostrarCarga = false;
                this.mostrarTabla = true;
                
                // Para el manejo de columnas visibles
                $("a.toggle-vis").on("click", (e) => {
                    e.preventDefault();
                    var column = this.table.column($(e.target).attr("data-column"));
                    column.visible(!column.visible());
                });
        
                // Checkbox para seleccionar filas
                this.table.on("click", "th.select-checkbox", () => {
                    if ($("th.select-checkbox").hasClass("selected")) {
                        this.table.rows().deselect();
                        $("th.select-checkbox").removeClass("selected");
                    } else {
                        this.table.rows().select();
                        $("th.select-checkbox").addClass("selected");
                    }
                }).on("select deselect", () => {
                    if (this.table.rows({ selected: true }).count() !== this.table.rows().count()) {
                        $("th.select-checkbox").removeClass("selected");
                    } else {
                        $("th.select-checkbox").addClass("selected");
                    }
                });
            }, 100);
        },
        truncateText(text, maxLength) {
            if (!text) return '';
            return text.length > maxLength ? `${text.substring(0, maxLength)}...` : text;
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
        thisDate($fecha, $tipoOrden) {
            if ($fecha == null) {
                return "---";
            } else {
                if ($tipoOrden) {
                    return moment($fecha).format("YYYY-MM-DD"); // Ordenar por año, mes, día
                } else {
                    return moment($fecha).format("DD-MM-YYYY"); // Ordenar por día, mes, año
                }
            }
        },
        formatMoneyCLP(value) {
            return new Intl.NumberFormat("en-EN", {
                currency: "CLP",
                currencyDisplay: "narrowSymbol",
                style: "currency",
            }).format(value);
        },
    },
};
