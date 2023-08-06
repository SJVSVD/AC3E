<nav class="navbar px-0 mx-4 " id="navbarBlur" data-scroll="false">
    <div class="container-fluid" style="height: 24px">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Actually in...</a></li>
                <li class="breadcrumb-item text-sm text-white active" aria-current="page">{{ $title }}</li>
            </ol>
        </nav>
        <ul>
            <li class="nav-item px-3 d-flex align-items-center">
                <form  method="post" action="{{ route('logout') }}" id="logout-form"> @csrf </form>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link text-white font-weight-bold px-0 pb-0 pt-3">
                    <i class="fa-solid fa-user-large-slash"></i>
                    <span class="d-sm-inline d-none">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</nav>