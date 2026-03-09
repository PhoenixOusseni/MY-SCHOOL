<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
<script src="{{ asset('asset/js/scripts.js') }}"></script>
<script src="{{ asset('js/latest.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('asset/js/datatables/datatables-simple-demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js" crossorigin="anonymous"></script>
<script src="{{ asset('asset/js/litepicker.js') }}"></script>
<script src="{{ asset('js/jquery-3.5.1.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('js/jqueryDataTable.min.js') }}"></script>
<script src="{{ asset('js/jqueryDataTableButtons.min.js') }}"></script>
<script src="{{ asset('js/jszip.min.js') }}"></script>
<script src="{{ asset('js/pdfmake.min.js') }}"></script>
<script src="{{ asset('js/vfs_fonts.min.js') }}"></script>
<script src="{{ asset('js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('js/buttons.print.min.js') }}"></script>
<script src="{{ asset('js/buttons.colvis.min.js') }}"></script>

<script src="https://assets.startbootstrap.com/js/sb-customizer.js"></script>

<script>
    $(document).ready(function () {
        // ── Select2 : Élèves ──────────────────────────────────────
        $('select[name="eleve_id"]').select2({
            placeholder: '-- Sélectionner un élève --',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function () { return 'Aucun résultat trouvé'; },
                searching:  function () { return 'Recherche en cours…'; }
            }
        });

        // ── Select2 : Classes ─────────────────────────────────────
        $('select[name="classe_id"]').select2({
            placeholder: '-- Sélectionner une classe --',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function () { return 'Aucun résultat trouvé'; },
                searching:  function () { return 'Recherche en cours…'; }
            }
        });
    });
</script>
