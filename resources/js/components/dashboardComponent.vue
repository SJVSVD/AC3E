<template>
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
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
                <div class="card-body p-3 text-white text-shadow-black background-size" style="background-image:linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('https://fondosmil.com/fondo/10790.jpg'); min-height: 160px;">
                    <div class="row">
                        <div class="col-12">
                            <div class="numbers" style="min-height: 80px; text-align: center; margin-top: 40px;">
                                <p class="text-sm mb-0 text-uppercase text-white fw-bold">Bienvenido</p>
                                <h5 style="color: #ffff;" class="fw-bolder">
                                    {{ userName }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card overflow-hidden" style="height: 160px;">
                <div v-show="mostrarCargaMoneda" class="loader"></div>
                <div class="card-body p-3 text-white text-shadow-black background-size" style="background-image:linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('https://e0.pxfuel.com/wallpapers/453/87/desktop-wallpaper-financial-graph-on-technology-abstract-background-stock-market.jpg')">
                    <div class="row">
                        <h6 class="fw-bolder text-white">Conversión desde $1.000 (CLP)</h6>
                        <div class="col-3">
                            <p class="text-sm mb-0 text-uppercase fw-bold">USD</p>
                            <div v-if="CLPRates[0].USD != null">{{ CLPRates[0].USD }}</div>
                            <div v-if="CLPRates[0].USD == null">---</div>
                            <p class="text-sm mb-0 text-uppercase fw-bold">EURO</p>
                            <div v-if="CLPRates[0].EUR != null">{{ CLPRates[0].EUR }}</div>
                            <div v-if="CLPRates[0].EUR == null">---</div>
                        </div>
                        <div class="col-3">
                            <p class="text-sm mb-0 text-uppercase fw-bold">JPY</p>
                            <div v-if="CLPRates[0].JPY != null">{{ CLPRates[0].JPY }}</div>
                            <div v-if="CLPRates[0].JPY == null">---</div>
                            <p class="text-sm mb-0 text-uppercase fw-bold">CNY</p>
                            <div v-if="CLPRates[0].CNY != null">{{ CLPRates[0].CNY }}</div>
                            <div v-if="CLPRates[0].CNY == null">---</div>
                        </div>
                        <div class="col-3">
                            <p class="text-sm mb-0 text-uppercase fw-bold">PEN</p>
                            <div v-if="CLPRates[0].PEN != null">{{ CLPRates[0].PEN }}</div>
                            <div v-if="CLPRates[0].PEN == null">---</div>
                            <p class="text-sm mb-0 text-uppercase fw-bold">ARS</p>
                            <div v-if="CLPRates[0].ARS != null">{{ CLPRates[0].ARS }}</div>
                            <div v-if="CLPRates[0].ARS == null">---</div>
                        </div>
                        <div class="col-3">
                            <p class="text-sm mb-0 text-uppercase fw-bold">BTC</p>
                            <div v-if="CLPRates[1].BTC != null">{{ CLPRates[1].BTC }}</div>
                            <div v-if="CLPRates[1].BTC == null">---</div>
                            <p class="text-sm mb-0 text-uppercase fw-bold">ETH</p>
                            <div v-if="CLPRates[1].ETH != null">{{ CLPRates[1].ETH }}</div>
                            <div v-if="CLPRates[1].ETH == null">---</div>
                            <!-- <p class="text-sm mb-0 text-uppercase fw-bold">IMPT</p>
                            <div v-if="CLPRates[1].IMPT != null">{{ CLPRates[1].IMPT }}</div>
                            <div v-if="CLPRates[1].IMPT == null">---</div>
                            <p class="text-sm mb-0 text-uppercase fw-bold">CCHG</p>
                            <div v-if="CLPRates[1].CCHG != null">{{ CLPRates[1].CCHG }}</div>
                            <div v-if="CLPRates[1].CCHG == null">---</div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4" >
            <div class="card overflow-hidden" style="height: 160px;">
                <div v-show="mostrarCargaClima" class="loader"></div>
                <div id="clima" class="card-body p-3 text-white text-shadow-black background-size" style="background-image:linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('https://i0.wp.com/asiescancun.mx/wp-content/uploads/2021/08/como-estara-el-clima-hoy.jpg');">
                    <div v-if="weather == 'No encontrado'" >
                        <h5 class="text-white" style="min-height: 80px; text-align: center; margin-top: 35px;">No se pudo obtener la ubicación.</h5>
                    </div>
                    <div v-else-if="geo" class="row">
                        <h6 class="fw-bolder text-white">{{ weather[3] }}, {{ weather[4] }}</h6>
                        <div class="col-auto">
                            <p class="text-sm mb-0 text-uppercase fw-bold" style="text-transform: capitalize;">{{ weather[0] }}</p>
                            <br>
                            <p class="text-sm mb-0 text-uppercase fw-bold">Temperatura: {{ Math.round(weather[2]) }}&deg;C</p>
                            <p class="text-sm mb-0 text-uppercase fw-bold">Sensación: {{ Math.round(weather[5]) }}&deg;C</p>
                        </div>
                        <div class="col-auto">
                            <p class="text-sm mb-0 text-uppercase fw-bold">Viento: {{ weather[7] }} m/s</p>
                            <br>
                            <p class="text-sm mb-0 text-uppercase fw-bold">Humedad: {{ weather[6] }}%</p>   
                            <p class="text-sm mb-0 text-uppercase fw-bold">Nubosidad: {{ weather[10] }}%</p>
                        </div>
                        <div class="col-auto">
                            <br>
                            <p></p>
                            <p class="text-sm mb-0 text-uppercase fw-bold">Lluvia: {{ weather[8] }} mm/h</p>
                            <p class="text-sm mb-0 text-uppercase fw-bold">Nieve: {{ weather[9] }} mm/h</p>
                        </div>
                    </div>
                    <div v-else>
                        <h5 class="text-white" style="min-height: 80px; text-align: center; margin-top: 35px;">No se pudo obtener la ubicación.</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card overflow-hidden" style="height: 160px;">
                <div class="card-body p-3 text-white text-shadow-black background-size" style="background-image:linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('https://fondosmil.com/fondo/10790.jpg'); min-height: 160px;">
                    <div class="row">
                        <div class="col-12">
                            <div class="numbers" style="min-height: 80px; text-align: center; margin-top: 40px;">
                                <p class="text-sm mb-0 text-uppercase text-white fw-bold">Fecha y hora actual</p>
                                <h5 style="color: #629ad1;" class="fw-bolder">
                                    {{ time }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card z-index-2 p-0" style="min-height: 200px; max-height: 650px;">
                <div class="p-3 mt-3" style="overflow-y: auto;">
                    <!-- Version (NUMERO VERSION REAL).(NUMERO VERSION BETA).(NUMERO VERSION ALPHA) -->
                    <!-- <h5> <span class="p-2 bg-light shadow" style="background-color: #16151f !important; border-radius: 8px; color: #629ad1;"> Version Beta 0.5.0</span> - 15 de Mayo, 2023</h5>
                    <ul class="mt-3">
                        <li class="list-title-items">¡Nuevos Sistemas!</li>
                        <ol style="list-style: inside; list-style-type: circle;">
                            <li class="list-subtitle-items">Gestión de Inventario Concesiones:</li>
                            <ol class="list-content-items">
                                <li><a class="list-content-description-items">Se implementó el sistema de 'gestión de inventario concesiones' mediante el cual se puede ver, modificar y eliminar inventarios, además de poder gestionar mediante correos automatizados el proceso de ajustes y contabilización de inventarios.</a></li>
                            </ol>
                        </ol>
                        <li class="list-subtitle-items">Bug Fixes:</li>
                        <ol class="list-content-items">
                            <li style="padding-bottom: 5px;"><a class="list-content-description-items">---</a></li>
                        </ol>
                    </ul> -->
                    <!-- <h5> <span class="p-2 bg-warning shadow" style="background-color: #16151f !important; border-radius: 8px; color: #629ad1;"> Version Beta 0.4.0</span> - 19 de Junio, 2023</h5>
                    <ul class="mt-3">
                        <li class="list-title-items">¡Nuevos Sistemas!</li>
                        <ol style="list-style: inside; list-style-type: circle;">
                            <li class="list-subtitle-items">Gestión de Inventario Concesiones:</li>
                            <ol class="list-content-items">
                                <li><a class="list-content-description-items">TEMPORAL.</a></li>
                            </ol>
                            <li class="list-subtitle-items">Conversión de Ventas:</li>
                            <ol class="list-content-items">
                                <li><a class="list-content-description-items">TEMPORAL.</a></li>
                            </ol>
                            <li class="list-subtitle-items">Muchas más herramientas!</li>
                            <ol class="list-content-items">
                                <li class="pb-2 fw-semibold">Sistema de Pagos de Facturas:</li>
                                <ol class="list-content-items">
                                    <li><a class="list-content-description-items">TEMPORAL.</a></li>
                                </ol>
                                <li class="pb-2 fw-semibold">Interconexión entre sistemas:</li>
                                <ol class="list-content-items">
                                    <li><a class="list-content-description-items">TEMPORAL.</a></li>
                                </ol>
                            </ol>
                        </ol>
                    </ul>
                    <br>
                    <hr size="4" class="separador"> -->
                    <h5> <span class="p-2 bg-warning shadow" style="background-color: #16151f !important; border-radius: 8px; color: #57ca5e;"> Version Beta 0.3.0</span> - 12 de Junio, 2023</h5>
                    <ul class="mt-3">
                        <li class="list-title-items">¡Lanzamiento Inicial!</li>
                        <ol style="list-style: inside; list-style-type: circle;">
                            <li class="list-subtitle-items">Autorización de Facturas:</li>
                            <ol class="list-content-items">
                                <li><a class="list-content-description-items">Se implementó el sistema de 'autorización de facturas' a través del cual se podrá hacer seguimiento de las facturas, subir facturas, subir sus referencias y/o autorizar.</a></li>
                            </ol>
                            <li class="list-subtitle-items">Gestión de Fondos Fijos:</li>
                            <ol class="list-content-items">
                                <li><a class="list-content-description-items">Se implementó el sistema de 'gestión de fondos fijos' donde el usuario podrá registrar, ver y eliminar sus gastos asociados a fondos fijos.</a></li>
                            </ol>
                            <li class="list-subtitle-items">Gestión de Rendiciones:</li>
                            <ol class="list-content-items">
                                <li><a class="list-content-description-items">Se implementó el sistema de 'gestión de rendiciones' en el cual se pueden solicitar, ver y rendir sus rendiciones.</a> </li>
                            </ol>
                            <li class="list-subtitle-items">Gestión de Robos:</li>
                            <ol class="list-content-items">
                                <li><a class="list-content-description-items">Se implementó el sistema de 'gestión de robos' en el cual se pueden informar y gestionar robos a las tiendas.</a> </li>
                            </ol>
                            <li class="list-subtitle-items">Gestión Tienda:</li>
                            <ol class="list-content-items">
                                <li><a class="list-content-description-items">Se implementó el sistema de 'gestión tienda' a través del cual cada tienda podrá hacer una gran diversidad de acciones:</a></li>
                                <ol class="list-content-items">
                                    <li class="pb-2 fw-semibold">Solicitar Precios: <a class="list-content-description-items">Se permite solicitar un indicador de precios con su respectiva etiqueta (normal, especial y liquidación). </a></li>
                                    <li class="pb-2 fw-semibold">Crear Vendedor: <a class="list-content-description-items">Se pueden ingresar los datos de nuevos vendedores para que puedan ingresar al sistema.</a></li>
                                    <li class="pb-2 fw-semibold">Cierre de Cajas: <a class="list-content-description-items">Se pueden ingresar cierres de caja. Además se muestra una tabla con el listado de los cierres de cajas, donde se pueden eliminar o ver sus detalles. </a></li>
                                    <li class="pb-2 fw-semibold">Notas de Crédito: <a class="list-content-description-items">Se permitira principalmente administrar notas de crédito, ya sea creando, eliminando o simplemente viendo su detalle.</a></li>
                                    <!-- <li class="pb-2 fw-semibold">Conversión de Ventas: <a class="list-content-description-items">Lleva un registro de las ventas/metas y conversión de personas que entran a las tiendas con la cantidad de ventas.</a></li> -->
                                </ol>
                            </ol>
                            <li class="list-subtitle-items">Muchas más herramientas!</li>
                            <ol class="list-content-items">
                                <li class="pb-2 fw-semibold">Dashboard: <a class="list-content-description-items">El Dashboard, o página de inicio, permite a los usuarios visualizar elementos importantes del sistema y de la vida cotidiana, así como los cambios que se van realizando en el sistema.</a></li>
                                <li class="pb-2 fw-semibold">Manuales de Sistema: <a class="list-content-description-items">Se agregó un apartado mediante el cual los usuarios podrán acceder a los manuales de los sistemas antes mencionados, en los cuales se detalla su funcionamiento y funcionalidades.</a></li>
                                <li class="pb-2 fw-semibold">Configuración de Perfil: <a class="list-content-description-items">Permite a los usuarios visualizar, almacenar y correguir su información relevante personal en la plataforma, así como visualizar su información empresarial.</a></li>
                            </ol>
                            <li class="list-subtitle-items">Otros cambios:</li>
                            <ol class="list-content-items">
                                <li class="pb-2 fw-semibold">Administración: <a class="list-content-description-items">Se agregaron nuevas opciones sobre el manejo general del sistema.</a></li>
                                <li class="pb-2 fw-semibold">Mejoras visuales y de rendimiento en 'Administración General'.</li>
                            </ol>
                            <li class="list-subtitle-items">Sistemas en Producción:</li>
                            <ol style="list-style: inside; list-style-type: circle;">
                                <li class="list-content-description-items fw-bold">Gestión de Inventario Concesiones.</li>
                                <li class="list-content-description-items fw-bold">Gestión Tiendas: <a class="list-content-description-items">Conversión de Ventas.</a></li>
                                <li class="list-content-description-items fw-bold">Autorización de Facturas: <a class="list-content-description-items">Sistema de Pagos.</a></li>
                                <li class="list-content-description-items">Interconexión entre sistemas de Facturas y Fondos Fijos.</li>
                            </ol>
                        </ol>
                    </ul>
                    <br>
                    <hr size="4" class="separador">
                    <h5> <span class="p-2 bg-warning shadow" style="background-color: #16151f !important; border-radius: 8px; color: #629ad1;"> Version Beta 0.2.0</span> - 27 de Marzo, 2023</h5>
                    <ul class="mt-3">
                        <li class="list-title-items">Primeras Pruebas</li>
                        <ol style="list-style: inside; list-style-type: circle;">
                            <a class="list-content-description-items">En esta primera etapa se logró avanzar en la construcción inicial de nuevos sistemas, además se pudieron sentar las bases de la rigurosidad, diseño y futuros sistemas a implementar.</a>
                            <li class="list-subtitle-items">Sistemas en Producción:</li>
                            <ol style="list-style: inside; list-style-type: circle;">
                                <li class="list-content-description-items">Autorización de Facturas.</li>
                                <li class="list-content-description-items">Gestión de Fondos Fijos.</li>
                                <li class="list-content-description-items">Gestión de Rendiciones.</li>
                                <li class="list-content-description-items">Gestión de Robos.</li>
                                <li class="list-content-description-items">Gestión de Tiendas.</li>
                                <li class="list-content-description-items">Entre otras herramientas!</li>
                            </ol>
                        </ol>
                    </ul>
                    <br>
                    <hr size="4" class="separador">
                    <h5> <span class="p-2 bg-warning shadow" style="background-color: #16151f !important; border-radius: 8px; color: #629ad1;"> Version Beta 0.1.0</span> - 20 de Febrero, 2023</h5>
                    <ul class="mt-3">
                        <li class="list-title-items">Sistema Base</li>
                        <ol style="list-style: inside; list-style-type: circle;">
                            <li class="list-subtitle-items">Creación del Proyecto.</li>
                            <li class="list-subtitle-items">Herramientas de Funcionamiento General:</li>
                            <ol class="list-content-items">
                                <a class="list-content-description-items">Se implementaron herramientas que permiten sentar las bases del sistema, entre ellas:</a>
                                <li class="pb-2 fw-semibold">Dashboard: <a class="list-content-description-items">El Dashboard, o página de inicio, conforma la página principal del sistema, ya que será aquella que se mostrará al entrar al sistema. Se añadirán más funcionalidades más adelante.</a></li>
                                <li class="pb-2 fw-semibold">Administración: <a class="list-content-description-items">Tomando en cuenta a la administración del sistema se agregaron herramientas de manejo general y contacto, lo que además servirá para futuro testeo de funcionalidades.</a></li>
                            </ol>
                        </ol>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-carousel p-0" style="background-color: grey; min-height: 200px; max-height: 1000px;">
                <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner border-radius-lg">
                        <div class="carousel-item active">
                            <img class="img-not-draggable" style="height: auto; width: 100%;" src="../../../public/img/AmphoraLogo(Dark).png">
                            <div class="carousel-caption d-md-block bottom-0 text-start start-0 ms-5" style="right: 5%;">
                                <h5 class="text-white mb-1">AMPHORA</h5>
                                <p>There’s nothing I really wanted to do in life that I wasn’t able to get good at.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img class="img-not-draggable" style="height: auto; width: 100%;" src="../../../public/img/ZiolLogo(Dark).png">
                            <div class="carousel-caption d-md-block bottom-0 text-start start-0 ms-5" style="right: 5%;">
                                <h5 class="text-white mb-1">ZIOL</h5>
                                <p>That’s my skill. I’m not really specifically talented at anything except for the ability to learn.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img class="img-not-draggable" style="height: auto; width: 100%;" src="../../../public/img/ScalpersLogo(Dark).png">
                            <div class="carousel-caption d-md-block bottom-0 text-start start-0 ms-5" style="right: 5%;">
                                <h5 class="text-white mb-1">SCALPERS</h5>
                                <p>Don’t be afraid to be wrong because you can’t learn anything from a compliment.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img class="img-not-draggable" style="height: auto; width: 100%;" src="../../../public/img/TrinusLogo(Dark).png">
                            <div class="carousel-caption d-md-block bottom-0 text-start start-0 ms-5" style="right: 5%;">
                                <h5 class="text-white mb-1">TRINUS</h5>
                                <p>There’s nothing I really wanted to do in life that I wasn’t able to get good at.</p>
                            </div>
                        </div>
                    </div>
                    <a class="btn carousel-control-prev w-5 me-3"
                        data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </a>
                    <a class="btn carousel-control-next w-5 me-3"
                        data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <!-- ############################################################################################################################ -->
        <!-- <div class="col-lg-7 mb-lg-0 mb-4">
            <div class="card ">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between" style="min-height: 10px">
                        <h6 class="mb-2">Tiendas con más Ventas:</h6>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center">
                        <tbody>
                            <tr>
                                <td class="w-30">
                                    <div class="d-flex px-2 py-1 align-items-center">
                                        <div>
                                            <img v-bind:src="'/img/icons/flags/US.png'" alt="Country flag">
                                        </div>
                                        <div class="ms-4" style="min-height: 20px">
                                            <p class="text-xs fw-bold mb-0">Country:</p>
                                            <h6 class="text-sm mb-0">United States</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <p class="text-xs fw-bold mb-0">Sales:</p>
                                        <h6 class="text-sm mb-0">2500</h6>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <p class="text-xs fw-bold mb-0">Value:</p>
                                        <h6 class="text-sm mb-0">$230,900</h6>
                                    </div>
                                </td>
                                <td class="align-middle text-sm">
                                    <div class="col text-center">
                                        <p class="text-xs fw-bold mb-0">Bounce:</p>
                                        <h6 class="text-sm mb-0">29.9%</h6>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-30">
                                    <div class="d-flex px-2 py-1 align-items-center">
                                        <div>
                                            <img v-bind:src="'/img/icons/flags/DE.png'" alt="Country flag">
                                        </div>
                                        <div class="ms-4" style="min-height: 20px">
                                            <p class="text-xs fw-bold mb-0">Country:</p>
                                            <h6 class="text-sm mb-0">Germany</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <p class="text-xs fw-bold mb-0">Sales:</p>
                                        <h6 class="text-sm mb-0">3.900</h6>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <p class="text-xs fw-bold mb-0">Value:</p>
                                        <h6 class="text-sm mb-0">$440,000</h6>
                                    </div>
                                </td>
                                <td class="align-middle text-sm">
                                    <div class="col text-center">
                                        <p class="text-xs fw-bold mb-0">Bounce:</p>
                                        <h6 class="text-sm mb-0">40.22%</h6>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-30">
                                    <div class="d-flex px-2 py-1 align-items-center">
                                        <div>
                                            <img v-bind:src="'/img/icons/flags/GB.png'" alt="Country flag">
                                        </div>
                                        <div class="ms-4" style="min-height: 20px">
                                            <p class="text-xs fw-bold mb-0">Country:</p>
                                            <h6 class="text-sm mb-0">Great Britain</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <p class="text-xs fw-bold mb-0">Sales:</p>
                                        <h6 class="text-sm mb-0">1.400</h6>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <p class="text-xs fw-bold mb-0">Value:</p>
                                        <h6 class="text-sm mb-0">$190,700</h6>
                                    </div>
                                </td>
                                <td class="align-middle text-sm">
                                    <div class="col text-center">
                                        <p class="text-xs fw-bold mb-0">Bounce:</p>
                                        <h6 class="text-sm mb-0">23.44%</h6>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-30">
                                    <div class="d-flex px-2 py-1 align-items-center">
                                        <div>
                                            <img v-bind:src="'/img/icons/flags/DE.png'" alt="Country flag">
                                        </div>
                                        <div class="ms-4" style="min-height: 20px">
                                            <p class="text-xs fw-bold mb-0">Country:</p>
                                            <h6 class="text-sm mb-0">Brasil</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <p class="text-xs fw-bold mb-0">Sales:</p>
                                        <h6 class="text-sm mb-0">562</h6>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <p class="text-xs fw-bold mb-0">Value:</p>
                                        <h6 class="text-sm mb-0">$143,960</h6>
                                    </div>
                                </td>
                                <td class="align-middle text-sm">
                                    <div class="col text-center">
                                        <p class="text-xs fw-bold mb-0">Bounce:</p>
                                        <h6 class="text-sm mb-0">32.14%</h6>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> -->
        <!-- ############################################################################################################################ -->
        <!-- <div class="col-lg-5">
            <div class="card overflow-hidden" style="height: 160px;">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">Categories</h6>
                </div>
                <div class="card-body p-3">
                    <ul class="list-group">
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                    <i class="ni ni-mobile-button text-white opacity-10"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">Devices</h6>
                                    <span class="text-xs">250 in stock, <span class="fw-bold">346+
                                            sold</span></span>
                                </div>
                            </div>
                            <div class="d-flex">
                                <a class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                        class="ni ni-bold-right" aria-hidden="true"></i></a>
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                    <i class="ni ni-tag text-white opacity-10"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">Tickets</h6>
                                    <span class="text-xs">123 closed, <span class="fw-bold">15
                                            open</span></span>
                                </div>
                            </div>
                            <div class="d-flex">
                                <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                        class="ni ni-bold-right" aria-hidden="true"></i></button>
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                    <i class="ni ni-box-2 text-white opacity-10"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">Error logs</h6>
                                    <span class="text-xs">1 is active, <span class="fw-bold">40
                                            closed</span></span>
                                </div>
                            </div>
                            <div class="d-flex">
                                <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                        class="ni ni-bold-right" aria-hidden="true"></i></button>
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                    <i class="ni ni-satisfied text-white opacity-10"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">Happy users</h6>
                                    <span class="text-xs fw-bold">+ 430</span>
                                </div>
                            </div>
                            <div class="d-flex">
                                <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                        class="ni ni-bold-right" aria-hidden="true"></i></button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>
import axios from 'axios'
import modalconfirmacion from './snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from './snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data() {
        return {
            mostrarCargaMoneda: true,
            mostrarCargaClima: true,
            mostrarClima: false,
            CLPRates: null,
            interval: null,
            time: null,
            weather: null,
            coords: null,
            usuariosActivos: [],
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
        this.getCLPRates();
    },
    mounted(){
        this.getWeather();
        this.carouselInterval();
    },
    methods: {
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
        getCLPRates(){
            setTimeout(() => {
                axios.get('api/currency').then( response =>{
                    this.CLPRates = response.data;
                    this.mostrarCargaMoneda = false;
                }).catch(e=> console.log(e))
            }, 100);
        },
        getWeather(){
            if(navigator.geolocation){
                navigator.geolocation.getCurrentPosition(success)
                this.geo = true;
            }else{
                this.geo = false;
            }
            var coords2 = [];
            function success(geolocationPosition){
                let coords = [geolocationPosition.coords.latitude,geolocationPosition.coords.longitude];
                coords2 = coords;
            }
            setTimeout(() => {
                axios.post("api/search",coords2).then( response =>{
                    this.weather = response.data;
                    this.mostrarCargaClima = false;
                }).catch(e=> console.log(e))
            }, 100);

        },
    }
}
</script>