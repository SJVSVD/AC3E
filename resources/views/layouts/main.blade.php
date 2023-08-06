<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="user" content="{{ Auth::user() }}">
        @if (Auth::check())
            <meta name="roles" content="{{ Auth::user()->roles->pluck('name') }}">
        @endif
        {{-- @if (Auth::user() != null):
        @endif --}}
        <title>
            AC3E - Reporting System
        </title>
        <!-- Font Awesome Icons -->
        <script src="https://kit.fontawesome.com/1bd7af15cd.js" crossorigin="anonymous"></script>
        <link rel="icon" href="{{ asset('AC3E.png') }}" type="image/x-icon">
        <!-- CSS Files -->
        @vite('resources/css/app.css')
        @vite('resources/css/datatables.css')
        <link id="pagestyle" href="{{asset('assets/css/argon-dashboard.css')}}" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/vue@2.7.14/dist/vue.js"></script>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>

        <!-- DATATABLES JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/fixedcolumns/4.2.1/js/dataTables.fixedColumns.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/select/1.5.0/js/dataTables.select.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
        <link id="pagestyle" href="{{asset('assets/css/argon-dashboard.css')}}" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/vue@2.7.14/dist/vue.js"></script>

        <style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
        <!-- Js Files -->
        @vite('resources/js/app.js')
</head>

<body style="zoom: 0.9; background-color:#d3d3d3;">
    <div class="sidebar close" id="sidebarMain">
        <div class="logo-details">
            <i class="fa-solid fa-bars"></i>
            <span class="logo_name"><img src="/img/AC3E.png" width="65"></span>
        </div>
        <ul class="nav-links">
            <li>
                <a href="/dashboard">
                    <i class="fa-solid fa-chart-line"></i>
                    <span class="link_name">Main Page</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="/dashboard">Main Page</a></li>
                </ul>
            </li>
            @role('Administrador')
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                        <span class="link_name">Administration</span>
                    </a>
                    <i class="fa-solid fa-sort-down arrow" id="arrow1"></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name" href="#">Administration</a></li>
                    <li><a href="/admin-usuarios">of Users</a></li>
                    <li><a href="/admin-roles">of Roles and Permissions</a></li>
                </ul>
            </li>
            @endrole
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class="fa-solid fa-cubes"></i>
                        <span class="link_name">Modules</span>
                    </a>
                    <i class="fa-solid fa-sort-down arrow" id="arrow2"></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name" href="#">Modules</a></li>
                    <li><a href="/isi-publications">ISI Publications</a></li>
                    <li><a href="/thesis-students">Thesis Students</a></li>
                </ul>
            </li>
            <!-- @role('Super-Administrador')
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class="fa-regular fa-window-restore"></i>
                        <span class="link_name">Otras</span>
                    </a>
                    <i class="fa-solid fa-sort-down arrow"></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name" href="#">Otras</a></li>
                    <li><a href="/billing">Facturación</a></li>
                    <li><a href="/tables">Tablas</a></li>
                </ul>
            </li>
            @endrole -->
            <hr size="4" class="separador">
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class="fa-solid fa-circle-info"></i>
                        <span class="link_name">Help</span>
                    </a>
                    <i class="fa-solid fa-sort-down arrow" id="arrow3"></i>
                </div>
            </li>
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class="fa-solid fa-circle-user"></i>
                        <span class="link_name">My Account</span>
                    </a>
                    <i class="fa-solid fa-sort-down arrow" id="arrow4"></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name" href="#">My Account</a></li>
                    @role('Usuario')
                    <li><a href="/profile">Mi Perfil</a></li>
                    <hr size="4" class="separador-sm">
                    @endrole
                    <li>
                        <form  method="post" action="{{ route('logout') }}" id="logout-form"> @csrf </form>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <span class="d-sm-inline d-none">Logout</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    {{-- <div class="home-section home-content"> --}}
    <div style="margin-left:75px">
    @guest
        @yield('content')
    @endguest

    @auth
        @if (in_array(request()->route()->getName(), ['login', 'recover-password', 'rtl']))
            @yield('content')
        @else
            @if (!in_array(request()->route()->getName(), ['profile']))
                <div class="min-height-300 bg-primary position-absolute" style="width: -webkit-fill-available"></div>
            @elseif (in_array(request()->route()->getName(), ['profile']))
                <div class="position-absolute w-100 min-height-300 top-0" style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg'); background-position-y: 50%;">
                    <span class="mask bg-primary opacity-6"></span>
                </div>
            @endif
                <main class="main-content border-radius-lg">
                    @yield('content')
                </main>
            @include('components.fixed-plugin')
        @endif
    @endauth
    </div>
    <!--   Core JS Files   -->
    <script src="{{asset('assets/js/core/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>

    @stack('js');
    <a onclick="topFunction()" id="topButton" style="display: none; position: fixed; bottom: 20px; right: 30px;" class="btn btn-warning"><i class="fas fa-arrow-alt-up">▲</i></a>
    
    <script type="text/javascript">
        window.Laravel = {
            csrfToken: "{{ csrf_token() }}",
            jsPermissions: {!! auth()->check()?auth()->user()->jsPermissions():null !!}
        }
    </script>
    
    {{-- <!-- Multiple Select TomSelect -->
    <script>
        new TomSelect("#select-state",{
            maxItems: 3
        });
    </script>

    <!-- Multiple Select Chosen -->
    <link href="https://harvesthq.github.io/chosen/chosen.css" rel="stylesheet"/>
    <script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>
    <script>
        $(".chosen-select").chosen();

        $(".chosen-select").on('change', function (event,el) {
        var selected_element = $(".chosen-select option:contains("+el.selected+")");
        var selected_value  = selected_element.val();
        var parent_optgroup = selected_element.closest('optgroup').attr('label');
        var parent_optgroup_class = selected_element.closest('optgroup').attr('class');
        $(".search-choice:last > span").css("background-color", parent_optgroup_class);
        selected_element.text(parent_optgroup+' - '+selected_value);
        
        //$(selected_element).addClass(`${parent_optgroup_class}`);
        });
    </script> --}}

    <script>
        window.sessionStorage;
        window.onload = function() {
            var estadoSidebar = sessionStorage.getItem('estadoSidebar');
            if (estadoSidebar == 'abierta'){
                sidebar.classList.toggle("close");
            }
            let arrow = document.querySelectorAll(".arrow");
            var subMenuAbierto = '';
            for (var i = 0; i < arrow.length; i++) {
                if (sessionStorage.getItem('arrow'+[i+1]) == 'abierta'){
                    subMenuAbierto = document.getElementById('arrow'+[i+1]);
                    subMenuAbierto.parentElement.parentElement.classList.toggle("showMenu");
                }
            }  
        };
        // Boton para subir
        let mybutton = document.getElementById("topButton");
        window.onscroll = function() {scrollFunction()};
        function scrollFunction() {
        if (document.body.scrollTop > 400 || document.documentElement.scrollTop > 400) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
        }
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }

        // SideBar
        let arrow = document.querySelectorAll(".arrow");
        for (var i = 0; i < arrow.length; i++) {
            arrow[i].addEventListener("click", (e)=>{
                let arrowParent = e.target.parentElement.parentElement;
                if (arrowParent.classList == 'showMenu'){
                    sessionStorage.setItem(e.target.id,'cerrada');
                }else{
                    sessionStorage.setItem(e.target.id,'abierta');
                }
                arrowParent.classList.toggle("showMenu");
            });
        }
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".fa-bars");
        sidebarBtn.addEventListener("click", ()=>{
            if (sidebar.classList == 'sidebar'){
                sessionStorage.setItem('estadoSidebar','cerrada');
            }else{
                sessionStorage.setItem('estadoSidebar','abierta');
            }
            sidebar.classList.toggle("close");
        });
    </script>
    <script>
        $.extend( true, $.fn.dataTable.defaults, {
            /*
            "fixedColumns":   {
                left: 4,
                right: 1
            },
            */
            "dom": 'fltripB',
            "buttons": [
                'csv',
                // {
                // text: 'Importar CSV',
                //     action: function () {
                //         uploadEditor.create( {
                //             title: 'CSV file import'
                //         } );
                //     }
                // },
                'excel',
                'pdf',
                'copy',
                'print',
            ],
            "columnDefs": [ {
                "orderable": false,
                "className": 'select-checkbox',
                "targets":   0,
            } ],
            "select": {
                "style":    'os',
                "selector": 'td:first-child'
            },
            "order": [[ 1, 'asc' ]],
            "pageLength": 20,
            "lengthMenu": [ [5, 10, 20, 50, 100, 200, 500, -1], [5, 10, 20, 50, 100, 200, 500, "Todo"] ],
            "search": {
                "regex": true
                // (?=admin) (?=1) : AND expression.
                // admin|1 : OR expression.
            },
            "language": {
                "lengthMenu": "Show:&nbsp _MENU_",
                "paginate": {
                    "first": "first",
                    "last": "last",
                    "next": ">",
                    "previous": "<"
                },
            }
        });
    </script>
  </body>
</html>