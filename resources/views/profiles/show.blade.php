@extends('adminlte::page')



@section('template_title')
    {{ $user->name }}'s Profile
@endsection


@php
    $currentUser = Auth::user()
@endphp

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">
                <div class="card">
                    <div class="card-header">
                        {{ trans('profile.showProfileTitle',['username' => $user->name]) }}
                    </div>
                    <div class="card-body">

                        <div class="text-center mb-3">
                            <img src="@if ($user->profile->avatar_status == 1) {{ $user->profile->avatar }} @else {{ asset('images/avatar.png') }} @endif" alt="{{ $user->name }}" class="user-avatar rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                        </div>

                        <dl class="user-info">
                            <dt>
                                {{ trans('profile.showProfileUsername') }}
                            </dt>
                            <dd>
                                {{ $user->name }}
                            </dd>

                            <dt>
                                {{ trans('profile.showProfileFirstName') }}
                            </dt>
                            <dd>
                                {{ $user->first_name ?? 'N/A' }}
                            </dd>

                            @if ($user->last_name && ($currentUser->id == $user->id || $currentUser->hasRole('admin')))
                                <dt>
                                    {{ trans('profile.showProfileLastName') }}
                                </dt>
                                <dd>
                                    {{ $user->last_name }}
                                </dd>
                            @endif

                            @if ($user->email && ($currentUser->id == $user->id || $currentUser->hasRole('admin')))
                                <dt>
                                    {{ trans('profile.showProfileEmail') }}
                                </dt>
                                <dd>
                                    {{ $user->email }}
                                </dd>
                            @endif

                            @if ($user->phone_number && ($currentUser->id == $user->id || $currentUser->hasRole('admin')))
                                <dt>
                                    Teléfono
                                </dt>
                                <dd>
                                    {{ $user->phone_number }}
                                </dd>
                            @endif

                            @if ($user->tipo_usuario && ($currentUser->id == $user->id || $currentUser->hasRole('admin')))
                                <dt>
                                    Tipo de Usuario
                                </dt>
                                <dd>
                                    {{ ucfirst($user->tipo_usuario) }}
                                </dd>
                            @endif

                            @if ($user->softland_user && ($currentUser->id == $user->id || $currentUser->hasRole('admin')))
                                <dt>
                                    Usuario Softland
                                </dt>
                                <dd>
                                    {{ $user->softland_user }}
                                </dd>
                            @endif

                            @if ($user->softland_store && ($currentUser->id == $user->id || $currentUser->hasRole('admin')))
                                <dt>
                                    Bodega Softland
                                </dt>
                                <dd>
                                    {{ $user->softland_store }}
                                </dd>
                            @endif

                            @if ($user->profile)
                                @if ($user->profile->theme_id && ($currentUser->id == $user->id || $currentUser->hasRole('admin')))
                                    <dt>
                                        {{ trans('profile.showProfileTheme') }}
                                    </dt>
                                    <dd>
                                        {{ $currentTheme->name }}
                                    </dd>
                                @endif

                                @if ($user->profile->location)
                                    <dt>
                                        {{ trans('profile.showProfileLocation') }}
                                    </dt>
                                    <dd>
                                        {{ $user->profile->location }} <br />

                                        @if(config('settings.googleMapsAPIStatus'))
                                            Latitude: <span id="latitude"></span> / Longitude: <span id="longitude"></span> <br />

                                            <div id="map-canvas"></div>
                                        @endif
                                    </dd>
                                @endif

                                @if ($user->profile->bio && ($currentUser->id == $user->id || $currentUser->hasRole('admin')))
                                    <dt>
                                        {{ trans('profile.showProfileBio') }}
                                    </dt>
                                    <dd>
                                        {{ $user->profile->bio }}
                                    </dd>
                                @endif

                                @if ($user->profile->twitter_username)
                                    <dt>
                                        {{ trans('profile.showProfileTwitterUsername') }}
                                    </dt>
                                    <dd>
                                        {!! HTML::link('https://twitter.com/'.$user->profile->twitter_username, $user->profile->twitter_username, array('class' => 'twitter-link', 'target' => '_blank')) !!}
                                    </dd>
                                @endif

                                @if ($user->profile->github_username)
                                    <dt>
                                        {{ trans('profile.showProfileGitHubUsername') }}
                                    </dt>
                                    <dd>
                                        {!! HTML::link('https://github.com/'.$user->profile->github_username, $user->profile->github_username, array('class' => 'github-link', 'target' => '_blank')) !!}
                                    </dd>
                                @endif
                            @endif

                        </dl>

                        @if ($user->profile)
                            @if ($currentUser->id == $user->id)
                                {!! HTML::icon_link(URL::to('/profile/'.$currentUser->name.'/edit'), 'fa fa-fw fa-cog', trans('titles.editProfile'), array('class' => 'btn btn-small btn-info btn-block')) !!}
                            @endif
                        @else
                            <p>
                                {{ trans('profile.noProfileYet') }}
                            </p>
                            {!! HTML::icon_link(URL::to('/profile/'.$currentUser->name.'/edit'), 'fa fa-fw fa-plus ', trans('titles.createProfile'), array('class' => 'btn btn-small btn-info btn-block')) !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

