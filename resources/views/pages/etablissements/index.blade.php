@extends('layouts.master')

@section('style')
    @include('partials.style')
@endsection

@section('content')
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="list"></i></div>
                            Liste des établissements
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main page content-->

    <div class="container-xl px-4 mt-n10">
        <!-- Example Charts for Dashboard Demo-->
        <div class="row">
            <div class="col-lg-12">
                <!-- Tabbed dashboard card example-->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="col-sm-12 mb-3">
                            <a href="{{ route('gestion_etablissements.create') }}" class="btn btn-1 btn-sm"><i
                                    data-feather="plus"></i>&thinsp;&thinsp;
                                Ajouter un établissement</a>
                            <a href="{{ route('gestion_etablissements.index') }}" class="btn btn-dark btn-sm"><i
                                    data-feather="align-left"></i>&thinsp;&thinsp;
                                Liste des établissements</a>
                        </div>
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Nom établissement</th>
                                    <th>Téléphone</th>
                                    <th>Email</th>
                                    <th>Nom directeur</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($etablissements as $etablissement)
                                    <tr>
                                        <td>{{ $etablissement->id }}</td>
                                        <td>{{ $etablissement->code }}</td>
                                        <td>{{ $etablissement->nom }}</td>
                                        <td>{{ $etablissement->telephone }}</td>
                                        <td>{{ $etablissement->email }}</td>
                                        <td>{{ $etablissement->nom_directeur }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('gestion_etablissements.show', $etablissement->id) }}">
                                                <i class="me-2 text-danger" data-feather="eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
