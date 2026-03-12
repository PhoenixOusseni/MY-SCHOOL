@extends('layouts.master')

@section('style')
    @include('partials.style')
@endsection

@section('content')
    <!-- Header -->
    <header class="page-header page-header-dark header-gradient pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            Gestion des Élèves
                        </h1>
                        <p class="text-muted">Gérer les élèves inscrits dans les établissements scolaires</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_eleves.create') }}" class="btn btn-light btn-sm"><i
                                data-feather="plus"></i>&thinsp;&thinsp;
                            Ajouter un élève</a>
                        <a href="{{ route('gestion_eleves.index') }}" class="btn btn-dark btn-sm"><i
                                data-feather="align-left"></i>&thinsp;&thinsp; Liste des élèves
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container-xl px-4 mt-n10">
        <!-- Example Charts for Dashboard Demo-->
        <div class="row">
            <div class="col-lg-12">
                <!-- Tabbed dashboard card example-->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatablesSimple">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Matricule</th>
                                        <th>Prénom</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Établissement</th>
                                        <th>Classe</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($eleves as $index => $eleve)
                                        <tr>
                                            <td><strong>{{ $index + 1 }}</strong></td>
                                            <td><span class="badge bg-dark">{{ $eleve->registration_number }}</span></td>
                                            <td>{{ $eleve->user->prenom ?? 'N/A' }}</td>
                                            <td>{{ $eleve->user->nom ?? 'N/A' }}</td>
                                            <td><small>{{ $eleve->user->email ?? 'N/A' }}</small></td>
                                            <td><small>{{ $eleve->user->telephone ?? 'N/A' }}</small></td>
                                            <td>
                                                @if ($eleve->etablissement)
                                                    <span class="badge bg-dark">{{ $eleve->etablissement->nom }}</span>
                                                @else
                                                    <span class="badge bg-light text-dark">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($eleve->inscriptions->isNotEmpty())
                                                    @foreach ($eleve->inscriptions as $inscription)
                                                        <span class="badge bg-dark">
                                                            {{ $inscription->classe->nom ?? 'N/A' }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="badge bg-warning">Pas encore inscrit</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('gestion_eleves.show', $eleve->id) }}"
                                                    class="text-danger">
                                                    <i class="fas fa-eye"></i>
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
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Réinitialiser les icônes Feather
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endsection
