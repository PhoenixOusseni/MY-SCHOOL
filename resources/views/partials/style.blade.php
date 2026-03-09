<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet" />
<link href="{{ asset('asset/css/styles.css') }}" rel="stylesheet" />
<link href="{{ asset('asset/css/dataTable.min.css') }}" rel="stylesheet" />
<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

<script data-search-pseudo-elements="" defer="" src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* DataTable toolbar hide */
    #datatablesSimple_filter,
    #datatablesSimple_info,
    #datatablesSimple_paginate {
        display: none;
    }

    /* ── Select2 custom theme ── */
    .select2-container--default .select2-selection--single {
        height: auto !important;
        padding: 9px 14px !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 10px !important;
        font-size: 13.5px !important;
        font-family: 'Inter', sans-serif !important;
        color: #0f172a !important;
        background-color: #fff !important;
        display: flex !important;
        align-items: center !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        padding: 0 !important;
        color: #0f172a !important;
        font-size: 13.5px !important;
        line-height: 1.6 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #64748b !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 50% !important;
        transform: translateY(-50%) !important;
        right: 10px !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #c41e3a !important;
        box-shadow: 0 0 0 3px rgba(196, 30, 58, 0.1) !important;
        outline: none !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #c41e3a !important;
    }

    .select2-dropdown {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 10px !important;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1) !important;
        font-size: 13.5px !important;
        font-family: 'Inter', sans-serif !important;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 6px !important;
        padding: 6px 10px !important;
        font-size: 13px !important;
        font-family: 'Inter', sans-serif !important;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: #c41e3a !important;
        outline: none !important;
    }

    .select2-container--default .select2-results__option {
        padding: 8px 14px !important;
    }

    .select2-container { width: 100% !important; }

    /* Align Select2 width to parent */
    .select2-container--default .select2-selection--single .select2-selection__clear {
        margin-right: 6px !important;
        color: #64748b !important;
        font-size: 16px !important;
    }
</style>
