@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', 'Seleccionar Empresa')

@section('auth_body')
    <div class="text-center mb-4">
        <i class="fa fa-building fa-3x text-primary"></i>
        <p class="mt-3">
            <strong>{{ Auth::user()->name }}</strong>, seleccione la empresa con la que desea trabajar:
        </p>
    </div>

    <form action="{{ route('post-select-conjunto') }}" method="post">
        @csrf

        <div class="form-group">
            <label for="conjunto">Empresa / Conjunto</label>
            <select class="form-control @error('conjunto') is-invalid @enderror" id="conjunto" name="conjunto" required>
                <option value="">Seleccione una empresa...</option>
                @foreach($conjuntos as $conjunto)
                    <option value="{{ $conjunto->CONJUNTO }}"
                        @if($conjunto->pivot && $conjunto->pivot->is_default) selected @endif>
                        {{ $conjunto->CONJUNTO }} - {{ $conjunto->NOMBRE }}
                        @if($conjunto->pivot && $conjunto->pivot->is_default)
                            (Predeterminada)
                        @endif
                    </option>
                @endforeach
            </select>
            @error('conjunto')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            <i class="fa fa-sign-in-alt"></i> Continuar
        </button>
    </form>

    <div class="text-center mt-3">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa fa-sign-out-alt"></i> Cerrar Sesión
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
@stop

@section('auth_footer')
    <p class="text-muted text-center">
        <i class="fa fa-info-circle"></i> Puede cambiar de empresa en cualquier momento desde el menú superior.
    </p>
@stop
