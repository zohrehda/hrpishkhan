{{--<nav class="navbar navbar-expand-lg navbar-light bg-transparent">--}}

{{--    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"--}}
{{--            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">--}}
{{--        <span class="navbar-toggler-icon"></span>--}}
{{--    </button>--}}

{{--    <div class="collapse navbar-collapse" id="navbarSupportedContent">--}}
{{--        <ul class="navbar-nav mr-auto">--}}
{{--            <li class="nav-item dropdown">--}}
{{--                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"--}}
{{--                   aria-haspopup="true" aria-expanded="false">--}}
{{--                    Access--}}
{{--                </a>--}}
{{--                <div class="dropdown-menu" aria-labelledby="navbarDropdown">--}}
{{--                    <a class="dropdown-item" href="{{ Route('requisitions.create') }}">New requisition</a>--}}
{{--                    <div class="dropdown-divider"></div>--}}
{{--                    <a class="dropdown-item" href="{{ Route('requisitions.user.pending') }}">Pending requisitions</a>--}}
{{--                    <a class="dropdown-item" href="{{ Route('requisitions.user.accepted') }}">Accepted requisitions</a>--}}
{{--                    <div class="dropdown-divider"></div>--}}
{{--                    <a class="dropdown-item" href="{{ Route('requisitions.determiner.pending') }}">Determiner requisitions</a>--}}
{{--                    <a class="dropdown-item" href="{{ Route('requisitions.determiner.assigned') }}">Assigned requisitions</a>--}}
{{--                    <div class="dropdown-divider"></div>--}}
{{--                    <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>--}}
{{--                </div>--}}
{{--            </li>--}}
{{--        </ul>--}}
{{--    </div>--}}

{{--</nav>--}}
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0">Welcome, {{ Auth::user()->name }}</a>
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="{{ route('logout') }}">Sign out</a>
        </li>
    </ul>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li></li>
                    {{--    <li class="nav-item">
                            <a class="nav-link  {{ (request()->is('panel/dashboard')) ? 'active' : '' }}"
                               href="{{ Route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('panel/requisitions/create')) ? 'active' : '' }}"
                               href="{{ Route('requisitions.create') }}">+ New</a>
                            <div class="dropdown-divider"></div>
                        </li>--}}

                    <li class="nav-item">
                        <a class="nav-link top-link cursor-pointer "
                        >Requisitions </a>
                        <ul class="d-none">
                            <li>
                                <a class="nav-link bottom-link  {{ (request()->is('panel/dashboard')) ? 'active' : '' }}"
                                   href="{{ Route('dashboard') }}">Dashboard</a>
                            </li>

                            <li>
                                <a class="nav-link bottom-link  {{ (request()->is('panel/requisitions/create')) ? 'active' : '' }}"
                                   href="{{ Route('requisitions.create') }}">+ New</a></li>


                        </ul>

                    </li>
                    {{--@if(\Illuminate\Support\Facades\Auth::user()->role=='supervisor')
                        <li class="nav-item">
                            <a class="nav-link top-link cursor-pointer "
                            >Reservation </a>
                            <ul class="d-none">
                                <li>
                                    <a class="nav-link bottom-link  {{ (request()->route()->getName()=="FoodReservation.FoodReserve.index" ) ? 'active' : '' }}"
                                       href="{{ Route('FoodReservation.FoodReserve.index',\App\Http\Controllers\Controller::nextWeek()  ) }}">
                                        Food Reservation </a>

                                </li>

                                <li>
                                    <a class="nav-link bottom-link {{ (request()->route()->getName()=="FoodReservation.foodPlans.index" ) ? 'active' : '' }}"
                                       href="{{ Route('FoodReservation.foodPlans.index',\App\Http\Controllers\Controller::nextWeek()) }}">Food
                                        weekly plan</a></li>
                                <li>
                                    <a class="nav-link bottom-link   {{ (request()->is('panel/foods')) ? 'active' : '' }}"
                                       href="{{route('FoodReservation.foods.index')}}">food list </a></li>
                                <li class="nav-item">
                                    <a class="nav-link top-link cursor-pointer "
                                    >Report </a>
                                    <ul class="d-none">
                                        <li>
                                            <a class="nav-link bottom-link {{ (request()->route()->getName()=="FoodReservation.food-report.daily" ) ? 'active' : '' }}"
                                               href="{{ Route('FoodReservation.food-report.daily',\App\Http\Controllers\Controller::today() ) }}">
                                                daily report </a>

                                        </li>

                                        <li>
                                            <a class="nav-link bottom-link {{ (request()->route()->getName()=="FoodReservation.food-report.weekly" ) ? 'active' : '' }}"
                                               href="{{route('FoodReservation.food-report.weekly',\App\Http\Controllers\Controller::nextWeek())}}">weekly
                                                report </a>
                                        </li>


                                    </ul>

                                </li>


                            </ul>

                        </li>
                        @else

                        <li class="nav-item">
                            <a class="nav-link  {{ (request()->route()->getName()=="FoodReservation.FoodReserve.index" ) ? 'active' : '' }}"
                               href="{{ Route('FoodReservation.FoodReserve.index',\App\Http\Controllers\Controller::nextWeek()  ) }}">
                                Food Reservation </a>

                        </li>
                    @endif--}}








                    {{--    <li class="nav-item">
                            <a class="nav-link {{ (request()->route()->getName()=="FoodReservation.FoodReserve.index" ) ? 'active' : '' }}"
                               href="{{ Route('FoodReservation.FoodReserve.index',\App\Http\Controllers\Controller::nextWeek()  ) }}">
                                Food Reservation </a>

                        </li>
                        @if(\Illuminate\Support\Facades\Auth::user()->role=='supervisor')
                            <li class="nav-item">

                                <a class="nav-link {{ (request()->route()->getName()=="FoodReservation.foodPlans.index" ) ? 'active' : '' }}"
                                   href="{{ Route('FoodReservation.foodPlans.index',\App\Http\Controllers\Controller::nextWeek()) }}">Food
                                    weekly plan</a>


                            </li>

                            <li class="nav-item">
                                <a class="nav-link  "
                                   href="#">Report </a>
                                <ul>
                                    <li>
                                        <a class="nav-link {{ (request()->route()->getName()=="FoodReservation.food-report.daily" ) ? 'active' : '' }}"
                                           href="{{ Route('FoodReservation.food-report.daily',\App\Http\Controllers\Controller::today() ) }}">
                                            daily report </a>

                                    </li>

                                    <li>
                                        <a class="nav-link {{ (request()->route()->getName()=="FoodReservation.food-report.weekly" ) ? 'active' : '' }}"
                                           href="{{route('FoodReservation.food-report.weekly',\App\Http\Controllers\Controller::nextWeek())}}">weekly
                                            report </a>
                                    </li>


                                </ul>

                            </li>
                            <li>
                                <a class="nav-link {{ (request()->is('panel/foods')) ? 'active' : '' }}"
                                   href="{{route('FoodReservation.foods.index')}}">food list </a>
                            </li>
                        @endif--}}

                    {{--                    <li class="nav-item">--}}
                    {{--                        <a class="nav-link  {{ (request()->is('panel/requisitions/user_accepted')) ? 'active' : '' }}"--}}
                    {{--                           href="{{ Route('requisitions.user.accepted') }}">User--}}
                    {{--                            accepted</a>--}}
                    {{--                    </li>--}}
                    {{--                    <li class="nav-item">--}}
                    {{--                        <div class="dropdown-divider"></div>--}}
                    {{--                        <a class="nav-link  {{ (request()->is('panel/requisitions/determiner_pending')) ? 'active' : '' }}"--}}
                    {{--                           href="{{ Route('requisitions.determiner.pending') }}">Determiner--}}
                    {{--                            pending</a>--}}
                    {{--                    </li>--}}
                    {{--                    <li class="nav-item">--}}
                    {{--                        <a class="nav-link  {{ (request()->is('panel/requisitions/determiner_assigned')) ? 'active' : '' }}"--}}
                    {{--                           href="{{ Route('requisitions.determiner.assigned') }}">Determiner--}}
                    {{--                            assigned</a>--}}
                    {{--                        <div class="dropdown-divider"></div>--}}
                    {{--                    </li>--}}
                </ul>
            </div>
        </nav>
    </div>
</div>
