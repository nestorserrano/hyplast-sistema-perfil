@extends('adminlte::page')

@section('template_title')
	{{ trans('titles.activation') }}
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-10 offset-md-1">
				<div class="card card-default">
					<div class="card-header">{{ trans('titles.activation') }}</div>
					<div class="card-body">
						<p>{{ trans('auth.regThanks') }}</p>
						<p>{{ trans('auth.anEmailWasSent',['email' => $email, 'date' => $date ] ) }}</p>
						<p>{{ trans('auth.clickInEmail') }}</p>
						<p><a href='/activation' class="btn btn-primary">{{ trans('auth.clickHereResend') }}</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
@if(session('status') && session('message'))
<script>
	$(document).ready(function() {
		@if(session('status') == 'success')
			Swal.fire({
				icon: 'success',
				title: '¡Éxito!',
				text: '{{ session('message') }}',
				confirmButtonText: 'Aceptar',
				confirmButtonColor: '#28a745'
			});
		@elseif(session('status') == 'error')
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: '{{ session('message') }}',
				confirmButtonText: 'Aceptar',
				confirmButtonColor: '#dc3545'
			});
		@elseif(session('status') == 'info')
			Swal.fire({
				icon: 'info',
				title: 'Información',
				text: '{{ session('message') }}',
				confirmButtonText: 'Aceptar',
				confirmButtonColor: '#17a2b8'
			});
		@endif
	});
</script>
@endif
@endsection
