<!-- DESKTOP NAV --->
<button type="button" class="navbar-toggle menu-txt-toggle" style="">
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
</button>

<!-- MOBILE NAV -->
<button type="button" id="mobile-toggle" class="mobile-toggle mobile-nav" data-toggle="offcanvas"
        data-target="#myNavmenu">
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
</button>

<div class="navbar navbar-default navbar-top">

        <div class="navbar-icons__wrapper">


            <div id="nav-toggle col-sm-6">
                <search></search>
            </div>
     <div class="topbar-user__wrapper">
            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="#" class="dropdown-toggle topbar-user__head " data-toggle="dropdown" style="padding: 10px !important;">
                    @php
                     $followUpToday = App\Models\Lead::followUpToday();   
                    @endphp
                        <span class="">
                        Notifications <span class="badge badge-light {{$followUpToday->count() > 0 ? 'danger' :'' }}">{{$followUpToday->count()}}</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu topbar-user__dropdown-menu">
                        <ul class="topbar-user__list-wrapper">
                        @foreach($followUpToday as  $lead)
                            <li class="topbar-user__list">
                                <a href="{{route('leads.show',$lead->external_id)}}" class="topbar-user__list-link">
                                    <div class="user__list-text row" style="width:100%">
                                        <span class="col-xs-8">
                                            {{ Illuminate\Support\Str::limit($lead->name,20)}} 
                                        </span>
                                        <span class="col-xs-4">
                                             {{Carbon\Carbon::parse($lead->deadline)->toTimeString()}}
                                        </span>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                    </ul>
                </li>
            </ul>
        </div>


            @if(Entrust::hasRole('administrator') || Entrust::hasRole('owner'))
                <div id="nav-toggle col-sm-4">
                    <a href="{{route('settings.index')}}" style="text-decoration: none;">
                        <span class="top-bar-toggler">
                            <i class="flaticon-gear"></i>
                        </span>
                    </a>
                </div>
            @endif
            @include('navigation.topbar.user-profile')
            <div id="nav-toggle col-sm-2">
                <a id="grid-action" role="button" data-toggle="dropdown">
                    <span class="top-bar-toggler">
                        <i class="flaticon-grid"></i>
                    </span>
                </a>
            </div>
        </div>

    @include('partials.action-panel._panel')
<!--NOTIFICATIONS END-->

</div>

