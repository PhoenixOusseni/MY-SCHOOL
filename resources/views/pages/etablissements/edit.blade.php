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
                            <div class="page-header-icon"><i data-feather="edit"></i></div>
                            Modifier l'établissement
                        </h1>
                        <p class="text-white">Mettre à jour les informations de l'établissement</p>
                    </div>
                    <div class="col-auto mt-4">
                        <a href="{{ route('gestion_etablissements.show', $etablissement->id) }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left"></i>&nbsp; Retour
                        </a>
                        <a href="{{ route('gestion_etablissements.index') }}" class="btn btn-dark btn-sm">
                            <i data-feather="align-left"></i>&nbsp; Liste des établissements
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header">Formulaire de modification</div>
                    <div class="card-body">
                        <form action="{{ route('gestion_etablissements.update', $etablissement->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="small mb-1">Code <span class="text-muted">(optionnel)</span></label>
                                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                           value="{{ old('code', $etablissement->code) }}" placeholder="Ex: ETB001">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-9">
                                    <label class="small mb-1">Nom de l'établissement <span class="text-danger">*</span></label>
                                    <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                           value="{{ old('nom', $etablissement->nom) }}" placeholder="Ex: Lycée Excellence" required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="small mb-1">Téléphone</label>
                                    <input type="tel" name="telephone" class="form-control @error('telephone') is-invalid @enderror"
                                           value="{{ old('telephone', $etablissement->telephone) }}" placeholder="Ex: +225 01 23 45 67 89">
                                    @error('telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="small mb-1">Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $etablissement->email) }}" placeholder="contact@etablissement.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="small mb-1">Nom du Directeur</label>
                                    <input type="text" name="nom_directeur" class="form-control @error('nom_directeur') is-invalid @enderror"
                                           value="{{ old('nom_directeur', $etablissement->nom_directeur) }}" placeholder="Ex: M. KOUASSI Jean">
                                    @error('nom_directeur')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-1">Adresse <span class="text-danger">*</span></label>
                                    <textarea name="adresse" rows="2" class="form-control @error('adresse') is-invalid @enderror"
                                              placeholder="Ex: Rue 123, Quartier ABC, Ville XYZ" required>{{ old('adresse', $etablissement->adresse) }}</textarea>
                                    @error('adresse')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1">Logo de l'établissement</label>
                                    <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror"
                                           accept="image/*" onchange="previewLogo(event)">
                                    <small class="text-muted">Formats acceptés: JPG, PNG, GIF (Max: 2MB)</small>
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 text-center">
                                    @if($etablissement->logo)
                                        <img id="logoPreview" src="{{ asset('storage/' . $etablissement->logo) }}" alt="Logo actuel"
                                             style="max-width: 150px; max-height: 150px; border: 2px solid #ddd; border-radius: 8px; padding: 5px;">
                                    @else
                                        <div id="logoPlaceholder" style="width: 150px; height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                                            <i data-feather="briefcase" style="width: 60px; height: 60px; color: white;"></i>
                                        </div>
                                        <img id="logoPreview" src="" alt="Aperçu du logo"
                                             style="max-width: 150px; max-height: 150px; display: none; border: 2px solid #ddd; border-radius: 8px; padding: 5px;">
                                    @endif
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="mt-3">
                                <button type="submit" class="btn btn-1">
                                    <i data-feather="save"></i>&thinsp;&thinsp; Mettre à jour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Fonction pour prévisualiser le logo
        function previewLogo(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('logoPreview');
            const placeholder = document.getElementById('logoPlaceholder');

            if (file) {
                // Vérifier la taille du fichier (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Le fichier est trop volumineux. Taille maximale : 2MB');
                    event.target.value = '';
                    preview.style.display = 'none';
                    if (placeholder) placeholder.style.display = 'flex';
                    return;
                }

                // Vérifier le type de fichier
                if (!file.type.match('image.*')) {
                    alert('Veuillez sélectionner une image valide');
                    event.target.value = '';
                    preview.style.display = 'none';
                    if (placeholder) placeholder.style.display = 'flex';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                if (placeholder) placeholder.style.display = 'flex';
            }
        }

        $(document).ready(function() {
            // Réinitialiser les icônes Feather
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Validation du formulaire
            $('form').on('submit', function(e) {
                const nom = $('input[name="nom"]').val().trim();
                const adresse = $('textarea[name="adresse"]').val().trim();

                if (!nom || !adresse) {
                    e.preventDefault();
                    alert('Veuillez remplir tous les champs obligatoires (*)');
                    return false;
                }
            });
        });
    </script>
@endsection

