
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Mis trofeos</h1>
            <p>Bienvenido, {{ auth()->user()->name }}</p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger">
                Cerrar sesión
            </button>
        </form>
    </div>

    <div class="card p-4">
        <h2>¡Tu aventura comienza aquí!</h2>
        <p>
            Aquí podrás consultar tus logros, experiencia y trofeos
            desbloqueados dentro de INFEST.
        </p>
    </div>
</div>
@endsection
