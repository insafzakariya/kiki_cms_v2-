<?php   $loggedUser=Sentinel::getUser();?>
<ul class="nav metismenu" id="side-menu">
  <li class="nav-header">
    <div class="dropdown profile-element text-center" style="margin-left: 10px;">
        <span>
            <!-- @if($loggedUser->avatar == null)
            <img alt="image" style="width: 100px;" class="img-circle" src="{{asset('assets/back/img/avatar.png')}}" />
            @else
            <img alt="image" style="width: 100px;" class="img-circle" src="{{Config('constants.bucket.url'). $user->avatar}}" />
            @endif -->
            @if($loggedUser->avatar == null)
            <img alt="image" style="width: 100px;" src="{{asset('assets/back/img/avatar.png')}}" />
            @else
            <img alt="image" style="width: 100px;" src="{{Config('constants.bucket.url'). $user->avatar}}" />
            @endif
        </span>
        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
            <span class="clear">                     
                <span class="block m-t-xs"><strong class="font-bold">{{$loggedUser->first_name}} {{$loggedUser->last_name}}</strong></span>
                <span class="text-muted text-xs block">{{$loggedUser->username}}</span> 
            </span> 
        </a>
            <!-- <ul class="dropdown-menu animated fadeInRight m-t-xs">
                <li><a href="profile.html">Profile</a></li>
                <li><a href="contacts.html">Contacts</a></li>
                <li><a href="mailbox.html">Mailbox</a></li>
                <li class="divider"></li>
                <li><a href="login.html">Logout</a></li>
            </ul> -->
        </div>
        <div class="logo-element">
            &nbsp;            
        </div>
    </li>
    
    @if(isset($menu))
    {!!$menu!!}
    @endif
</ul>

