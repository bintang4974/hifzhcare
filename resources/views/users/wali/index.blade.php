@extends('layouts.app-enhanced')

@section('title', 'Manajemen Wali')
@section('breadcrumb', 'Pengguna / Wali')

@section('content')
    <div class="wali-page">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Manajemen Wali</h1>
                <p class="page-subtitle">Kelola data wali santri dan monitor kontribusi</p>
            </div>
            @can('create_users')
                <a href="{{ route('users.wali.create') }}" class="btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Tambah Wali
                </a>
            @endcan
        </div>

        {{-- Stats Cards --}}
        <div class="stats-grid">
            <div class="stat-card stat-purple">
                <div class="stat-content">
                    <p class="stat-label">Total Wali</p>
                    <h3 class="stat-value" id="total-wali">—</h3>
                </div>
                <div class="stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                    </svg>
                </div>
            </div>

            <div class="stat-card stat-green">
                <div class="stat-content">
                    <p class="stat-label">Wali Aktif</p>
                    <h3 class="stat-value" id="active-wali">—</h3>
                </div>
                <div class="stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                </div>
            </div>

            <div class="stat-card stat-blue">
                <div class="stat-content">
                    <p class="stat-label">Total Santri</p>
                    <h3 class="stat-value" id="total-santri">—</h3>
                </div>
                <div class="stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                    </svg>
                </div>
            </div>

            <div class="stat-card stat-amber">
                <div class="stat-content">
                    <p class="stat-label">Total Donasi</p>
                    <h3 class="stat-value stat-value-sm" id="total-donations">—</h3>
                </div>
                <div class="stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <line x1="12" y1="1" x2="12" y2="23" />
                        <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Filter Card --}}
        <div class="card filter-card">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select id="filter-status" class="filter-input">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="pending">Pending</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Hubungan</label>
                    <select id="filter-relation" class="filter-input">
                        <option value="">Semua Hubungan</option>
                        <option value="ayah">Ayah</option>
                        <option value="ibu">Ibu</option>
                        <option value="wali">Wali</option>
                    </select>
                </div>
            </div>
            <div class="filter-actions">
                <button onclick="applyFilters()" class="btn-filter btn-apply">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M3 4h18l-7 8v5l-4 2V12z" />
                    </svg>
                    Filter
                </button>
                <button onclick="resetFilters()" class="btn-filter btn-reset">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M4 4v6h6M20 20v-6h-6" />
                        <path d="M4 10a8 8 0 0115.5-2.5M20 14a8 8 0 01-15.5 2.5" />
                    </svg>
                    Reset
                </button>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="card table-card">
            <div class="table-header">
                <h3 class="table-title">Daftar Wali</h3>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <div class="table-search">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" class="search-icon">
                            <circle cx="11" cy="11" r="8" />
                            <path d="M21 21l-4.35-4.35" />
                        </svg>
                        <input type="text" id="wali-search" placeholder="Cari wali, email..." class="search-input">
                    </div>
                    <button onclick="refreshTable()" class="refresh-btn" title="Refresh data">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M4 4v6h6M20 20v-6h-6" />
                            <path d="M4 10a8 8 0 0115.5-2.5M20 14a8 8 0 01-15.5 2.5" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="table-wrap">
                <table id="wali-table">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th class="col-wali">Wali</th>
                            <th class="col-email">Email</th>
                            <th class="col-hp">No HP</th>
                            <th class="col-hubungan">Hubungan</th>
                            <th class="col-santri-count">Santri</th>
                            <th class="col-santri-list">Daftar Santri</th>
                            <th class="col-donasi">Donasi</th>
                            <th class="col-status">Status</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Delete Modal --}}
    <div id="delete-modal" class="modal-overlay hidden">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-icon modal-icon-danger">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6l-1 14H6L5 6m5 0V4h4v2" />
                    </svg>
                </div>
                <div>
                    <h3 class="modal-title">Hapus Wali</h3>
                    <p class="modal-subtitle">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>
            <p class="modal-body-text">Apakah Anda yakin ingin menghapus data wali ini? Semua data terkait akan ikut
                terhapus.</p>
            <div class="modal-actions">
                <button type="button" onclick="closeDeleteModal()" class="btn-modal btn-modal-cancel">Batal</button>
                <button type="button" onclick="confirmDelete()" class="btn-modal btn-modal-danger">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6l-1 14H6L5 6m5 0V4h4v2" />
                    </svg>
                    Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div id="toast" class="toast hidden">
        <div class="toast-icon" id="toast-icon"></div>
        <span id="toast-message"></span>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <style>
        /* =============================================
       WALI PAGE — CONSISTENT WITH HAFALAN MODULE
       ============================================= */

        .wali-page {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1.5rem 1rem 3rem;
        }

        /* --- Page Header --- */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
            line-height: 1.2;
        }

        .page-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 4px 0 0;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            transition: background 0.15s;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            color: #fff;
            text-decoration: none;
        }

        /* --- Stats Grid --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 1.25rem;
        }

        @media (min-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .stat-card {
            border-radius: 12px;
            padding: 1.1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            border: 1px solid transparent;
        }

        .stat-content {
            min-width: 0;
            flex: 1;
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            margin: 0 0 4px;
            opacity: 0.85;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            line-height: 1;
        }

        .stat-value-sm {
            font-size: 1.15rem;
            word-break: break-all;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            opacity: 0.85;
        }

        .stat-purple {
            background: #ede9fe;
            color: #3b0764;
            border-color: #ddd6fe;
        }

        .stat-purple .stat-icon {
            background: #ddd6fe;
            color: #7c3aed;
        }

        .stat-green {
            background: #dcfce7;
            color: #14532d;
            border-color: #bbf7d0;
        }

        .stat-green .stat-icon {
            background: #bbf7d0;
            color: #15803d;
        }

        .stat-blue {
            background: #dbeafe;
            color: #1e3a8a;
            border-color: #bfdbfe;
        }

        .stat-blue .stat-icon {
            background: #bfdbfe;
            color: #1d4ed8;
        }

        .stat-amber {
            background: #fef3c7;
            color: #78350f;
            border-color: #fde68a;
        }

        .stat-amber .stat-icon {
            background: #fde68a;
            color: #d97706;
        }

        /* --- Card Base --- */
        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }

        /* --- Filter Card --- */
        .filter-card {
            padding: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 12px;
            max-width: 480px;
        }

        @media (max-width: 480px) {
            .filter-grid {
                grid-template-columns: 1fr;
                max-width: 100%;
            }
        }

        .filter-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 5px;
        }

        .filter-input {
            width: 100%;
            padding: 7px 10px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 0.8125rem;
            color: #374151;
            background: #fff;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .filter-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 8px;
        }

        .btn-filter {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 20px;
            border-radius: 7px;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: background 0.15s;
        }

        .btn-apply {
            background: #2563eb;
            color: #fff;
        }

        .btn-apply:hover {
            background: #1d4ed8;
        }

        .btn-reset {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .btn-reset:hover {
            background: #e5e7eb;
        }

        /* --- Table Card --- */
        .table-card {
            margin-bottom: 1.5rem;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e5e7eb;
            flex-wrap: wrap;
            gap: 10px;
        }

        .table-title {
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .table-search {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-icon {
            position: absolute;
            left: 10px;
            color: #9ca3af;
            pointer-events: none;
        }

        .search-input {
            padding: 7px 12px 7px 32px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 0.8125rem;
            color: #374151;
            width: 200px;
            transition: border-color 0.15s, box-shadow 0.15s, width 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            width: 240px;
        }

        .refresh-btn {
            width: 34px;
            height: 34px;
            border: 1px solid #e5e7eb;
            border-radius: 7px;
            background: #fff;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            transition: all 0.15s;
            flex-shrink: 0;
        }

        .refresh-btn:hover {
            background: #f3f4f6;
            color: #111827;
        }

        /* --- DataTable Overrides --- */
        .table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        #wali-table {
            width: 100% !important;
            border-collapse: collapse;
            font-size: 0.8125rem;
            min-width: 720px;
        }

        #wali-table thead th {
            padding: 10px 14px;
            background: #f9fafb;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
            border-top: none;
            white-space: nowrap;
        }

        #wali-table thead th.sorting,
        #wali-table thead th.sorting_asc,
        #wali-table thead th.sorting_desc {
            background-image: none !important;
            padding-right: 14px;
        }

        #wali-table tbody td {
            padding: 11px 14px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
            color: #374151;
            background: #fff;
        }

        #wali-table tbody tr:last-child td {
            border-bottom: none;
        }

        #wali-table tbody tr:hover td {
            background: #f9fafb;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            display: none !important;
        }

        .dataTables_processing {
            background: rgba(255, 255, 255, 0.9) !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 8px !important;
            font-size: 0.8125rem !important;
            color: #6b7280 !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
            padding: 12px 20px !important;
        }

        /* --- Wali Cell --- */
        .wali-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            flex-shrink: 0;
            letter-spacing: 0.3px;
        }

        .wali-name {
            font-weight: 600;
            color: #111827;
            font-size: 0.8125rem;
            line-height: 1.3;
        }

        .wali-sub {
            font-size: 0.7rem;
            color: #9ca3af;
            margin-top: 1px;
        }

        /* --- Badges --- */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.6875rem;
            font-weight: 600;
            letter-spacing: 0.2px;
            white-space: nowrap;
        }

        .badge-active {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-inactive {
            background: #f3f4f6;
            color: #6b7280;
        }

        /* Hubungan badges */
        .badge-ayah {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-ibu {
            background: #fce7f3;
            color: #9d174d;
        }

        .badge-wali {
            background: #ede9fe;
            color: #5b21b6;
        }

        /* --- Santri tags --- */
        .santri-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .santri-tag {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.6875rem;
            font-weight: 600;
            background: #f3f4f6;
            color: #374151;
            white-space: nowrap;
        }

        .santri-more {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.6875rem;
            font-weight: 600;
            background: #e5e7eb;
            color: #6b7280;
            white-space: nowrap;
        }

        .santri-empty {
            font-size: 0.75rem;
            color: #d1d5db;
        }

        /* --- Santri count --- */
        .count-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 24px;
            border-radius: 6px;
            background: #f3f4f6;
            color: #374151;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0 6px;
        }

        /* --- Donasi --- */
        .donasi-text {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #374151;
            white-space: nowrap;
        }

        .donasi-zero {
            color: #9ca3af;
            font-weight: 400;
        }

        /* --- Action Buttons --- */
        .action-group {
            display: flex;
            align-items: center;
            gap: 4px;
            justify-content: center;
        }

        .action-btn {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            border: 1px solid #e5e7eb;
            background: #fff;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
            text-decoration: none;
        }

        .action-btn:hover {
            border-color: transparent;
        }

        .action-btn.view {
            color: #2563eb;
        }

        .action-btn.view:hover {
            background: #eff6ff;
            border-color: #bfdbfe;
        }

        .action-btn.edit {
            color: #d97706;
        }

        .action-btn.edit:hover {
            background: #fffbeb;
            border-color: #fde68a;
        }

        .action-btn.del {
            color: #dc2626;
        }

        .action-btn.del:hover {
            background: #fef2f2;
            border-color: #fecaca;
        }

        /* --- Table Footer --- */
        .table-footer-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 1.25rem;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
            flex-wrap: wrap;
            gap: 8px;
        }

        .footer-text {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .pagination-custom {
            display: flex;
            gap: 4px;
        }

        .pg-btn {
            min-width: 30px;
            height: 30px;
            padding: 0 8px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: #fff;
            font-size: 0.75rem;
            color: #374151;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
        }

        .pg-btn:hover:not(.disabled):not(.active) {
            background: #f3f4f6;
        }

        .pg-btn.active {
            background: #2563eb;
            color: #fff;
            border-color: #2563eb;
        }

        .pg-btn.disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* --- Modal --- */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-overlay.hidden {
            display: none;
        }

        .modal-box {
            background: #fff;
            border-radius: 14px;
            padding: 1.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            animation: modalIn 0.2s ease;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(0.96) translateY(-8px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.25rem;
        }

        .modal-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .modal-icon-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .modal-title {
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .modal-subtitle {
            font-size: 0.8125rem;
            color: #6b7280;
            margin: 2px 0 0;
        }

        .modal-body-text {
            font-size: 0.875rem;
            color: #374151;
            line-height: 1.6;
            margin-bottom: 1.25rem;
        }

        .modal-actions {
            display: flex;
            gap: 8px;
            margin-top: 1.25rem;
        }

        .btn-modal {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: background 0.15s;
        }

        .btn-modal-cancel {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-modal-cancel:hover {
            background: #e5e7eb;
        }

        .btn-modal-danger {
            background: #dc2626;
            color: #fff;
        }

        .btn-modal-danger:hover {
            background: #b91c1c;
        }

        /* --- Toast --- */
        .toast {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            background: #111827;
            color: #fff;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 99999;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
            animation: slideUp 0.25s ease;
            max-width: 340px;
        }

        .toast.hidden {
            display: none;
        }

        .toast.success {
            background: #065f46;
        }

        .toast.error {
            background: #991b1b;
        }

        .toast-icon {
            font-size: 16px;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* --- Responsive --- */
        @media (max-width: 1024px) {
            .col-santri-list {
                display: none;
            }
        }

        @media (max-width: 768px) {

            .col-donasi,
            .col-santri-count {
                display: none;
            }
        }

        @media (max-width: 640px) {
            .col-email {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .col-hp {
                display: none;
            }

            .wali-page {
                padding: 1rem 0.75rem 2rem;
            }

            .page-title {
                font-size: 1.25rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        let table;
        let currentWaliId;

        const AVATAR_COLORS = [{
                bg: '#dbeafe',
                text: '#1e40af'
            },
            {
                bg: '#d1fae5',
                text: '#065f46'
            },
            {
                bg: '#fce7f3',
                text: '#9d174d'
            },
            {
                bg: '#fef3c7',
                text: '#92400e'
            },
            {
                bg: '#e0e7ff',
                text: '#3730a3'
            },
            {
                bg: '#ffedd5',
                text: '#9a3412'
            },
        ];

        function getAvatarColor(name) {
            let hash = 0;
            for (let i = 0; i < name.length; i++) hash = name.charCodeAt(i) + ((hash << 5) - hash);
            return AVATAR_COLORS[Math.abs(hash) % AVATAR_COLORS.length];
        }

        function getInitials(name) {
            return name.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase();
        }

        $(document).ready(function() {

            $('#wali-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            table = $('#wali-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('users.wali.index') }}",
                    data: function(d) {
                        d.status = $('#filter-status').val();
                        d.relation = $('#filter-relation').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'user.name'
                    },
                    {
                        data: 'email',
                        name: 'user.email'
                    },
                    {
                        data: 'phone',
                        name: 'user.phone'
                    },
                    {
                        data: 'relation_label',
                        name: 'relation',
                        orderable: false
                    },
                    {
                        data: 'children_count',
                        name: 'children_count',
                        orderable: false
                    },
                    {
                        data: 'children',
                        name: 'children',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'total_donations',
                        name: 'total_donations',
                        orderable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false
                    },
                    {
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                        targets: 0,
                        width: '40px',
                        className: 'col-no',
                        render: d => `<span style="color:#9ca3af;font-size:0.75rem">${d}</span>`
                    },
                    {
                        targets: 1,
                        className: 'col-wali',
                        render: function(d, t, row) {
                            const color = getAvatarColor(d);
                            const initials = getInitials(d);
                            const phone = row.phone || '';
                            return `<div class="wali-cell">
                        <div class="avatar" style="background:${color.bg};color:${color.text}">${initials}</div>
                        <div>
                            <div class="wali-name">${d}</div>
                            ${phone ? `<div class="wali-sub">${phone}</div>` : ''}
                        </div>
                    </div>`;
                        }
                    },
                    {
                        targets: 2,
                        className: 'col-email',
                        render: d => d ?
                            `<span style="font-size:0.8125rem;color:#374151">${d}</span>` :
                            '<span style="color:#d1d5db">—</span>'
                    },
                    {
                        targets: 3,
                        className: 'col-hp',
                        render: d => d ?
                            `<span style="font-size:0.8125rem;color:#374151">${d}</span>` :
                            '<span style="color:#d1d5db">—</span>'
                    },
                    {
                        targets: 4,
                        className: 'col-hubungan',
                        render: function(d) {
                            if (!d) return '<span style="color:#d1d5db">—</span>';
                            const lower = d.toLowerCase();
                            const cls = lower === 'ayah' ? 'badge-ayah' :
                                lower === 'ibu' ? 'badge-ibu' :
                                'badge-wali';
                            return `<span class="badge ${cls}">${d}</span>`;
                        }
                    },
                    {
                        targets: 5,
                        className: 'col-santri-count text-center',
                        render: d => `<span class="count-chip">${d ?? 0}</span>`
                    },
                    {
                        targets: 6,
                        className: 'col-santri-list',
                        render: function(d) {
                            // d can be an array of names, a comma-separated string, or HTML
                            if (!d || d === '' || d === '-') {
                                return '<span class="santri-empty">Belum ada santri</span>';
                            }

                            let names = [];
                            if (Array.isArray(d)) {
                                names = d;
                            } else if (typeof d === 'string' && !d.includes('<')) {
                                names = d.split(',').map(n => n.trim()).filter(Boolean);
                            } else {
                                // Already HTML from server — return as is
                                return d;
                            }

                            if (names.length === 0)
                            return '<span class="santri-empty">Belum ada santri</span>';

                            const shown = names.slice(0, 2);
                            const rest = names.length - 2;
                            let html = '<div class="santri-tags">';
                            shown.forEach(name => {
                                html += `<span class="santri-tag">${name}</span>`;
                            });
                            if (rest > 0) {
                                html += `<span class="santri-more">+${rest} lainnya</span>`;
                            }
                            html += '</div>';
                            return html;
                        }
                    },
                    {
                        targets: 7,
                        className: 'col-donasi text-right',
                        render: function(d) {
                            if (!d || d === 'Rp 0' || d === '0') {
                                return '<span class="donasi-zero">Rp 0</span>';
                            }
                            return `<span class="donasi-text">${d}</span>`;
                        }
                    },
                    {
                        targets: 8,
                        className: 'col-status text-center',
                        render: function(d) {
                            const map = {
                                active: {
                                    cls: 'badge-active',
                                    label: 'Aktif'
                                },
                                pending: {
                                    cls: 'badge-pending',
                                    label: 'Pending'
                                },
                                inactive: {
                                    cls: 'badge-inactive',
                                    label: 'Tidak Aktif'
                                },
                            };
                            const key = (d || '').toLowerCase();
                            const info = map[key] || {
                                cls: 'badge-inactive',
                                label: d
                            };
                            return `<span class="badge ${info.cls}">${info.label}</span>`;
                        }
                    },
                    {
                        targets: 9,
                        className: 'col-aksi',
                        render: function(d, t, row) {
                            const canEdit =
                                {{ auth()->user()->can('edit_users') ? 'true' : 'false' }};
                            const canDel =
                                {{ auth()->user()->can('delete_users') ? 'true' : 'false' }};
                            const showUrl = `/users/wali/${d}`;
                            const editUrl = `/users/wali/${d}/edit`;

                            let html = `<div class="action-group">
                        <a href="${showUrl}" class="action-btn view" title="Lihat Detail">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </a>`;

                            if (canEdit) {
                                html += `<a href="${editUrl}" class="action-btn edit" title="Edit">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </a>`;
                            }

                            if (canDel) {
                                html += `<button class="action-btn del" onclick="deleteWali(${d})" title="Hapus">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6m5 0V4h4v2"/>
                            </svg>
                        </button>`;
                            }

                            html += `</div>`;
                            return html;
                        }
                    }
                ],
                order: [
                    [1, 'asc']
                ],
                pageLength: 25,
                dom: 'tp',
                language: {
                    processing: 'Memuat data...'
                },
                drawCallback: function(settings) {
                    renderFooter(settings);
                    updateStats();
                }
            });

            // Load stats on page load
            updateStats();

            function renderFooter(settings) {
                const api = new $.fn.dataTable.Api(settings);
                const info = api.page.info();
                const start = info.start + 1;
                const end = Math.min(info.end, info.recordsDisplay);
                const total = info.recordsDisplay;
                const page = info.page;
                const pages = info.pages;

                let pagination =
                    `<button class="pg-btn ${page === 0 ? 'disabled' : ''}" onclick="goPage(${page - 1})">&lsaquo;</button>`;

                let startPage = Math.max(0, page - 2);
                let endPage = Math.min(pages - 1, startPage + 4);
                startPage = Math.max(0, endPage - 4);
                for (let i = startPage; i <= endPage; i++) {
                    pagination +=
                        `<button class="pg-btn ${i === page ? 'active' : ''}" onclick="goPage(${i})">${i + 1}</button>`;
                }
                pagination +=
                    `<button class="pg-btn ${page >= pages - 1 ? 'disabled' : ''}" onclick="goPage(${page + 1})">&rsaquo;</button>`;

                const infoText = total === 0 ?
                    'Tidak ada data ditemukan' :
                    `Menampilkan ${start}–${end} dari ${total} data`;

                $('#wali-table').closest('.table-card').find('.table-footer-info').remove();
                $('#wali-table').closest('.table-card').append(`
            <div class="table-footer-info">
                <span class="footer-text">${infoText}</span>
                <div class="pagination-custom">${pagination}</div>
            </div>
        `);
            }
        });

        window.goPage = function(page) {
            if (page < 0) return;
            table.page(page).draw('page');
        };

        // =====================
        // Filters
        // =====================
        window.applyFilters = function() {
            table.ajax.reload();
        };
        window.resetFilters = function() {
            $('#filter-status, #filter-relation').val('');
            table.ajax.reload();
        };
        window.refreshTable = function() {
            updateStats();
            table.ajax.reload(null, false);
        };

        // =====================
        // Stats
        // =====================
        function updateStats() {
            fetch("{{ route('users.wali.stats') }}", {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    $('#total-wali').text(data.total_wali ?? '—');
                    $('#active-wali').text(data.active_wali ?? '—');
                    $('#total-santri').text(data.total_santri ?? '—');
                    $('#total-donations').text(data.total_donations ?? 'Rp 0');
                })
                .catch(() => {
                    const total = table.page?.info?.()?.recordsTotal ?? '—';
                    $('#total-wali').text(total);
                });
        }

        // =====================
        // Toast
        // =====================
        function showToast(message, type = 'success') {
            const toast = $('#toast');
            toast.removeClass('hidden success error').addClass(type);
            $('#toast-icon').text(type === 'success' ? '✓' : '✕');
            $('#toast-message').text(message);
            toast.removeClass('hidden');
            setTimeout(() => toast.addClass('hidden'), 3500);
        }

        // =====================
        // Delete
        // =====================
        window.deleteWali = function(id) {
            currentWaliId = id;
            $('#delete-modal').removeClass('hidden');
        };
        window.closeDeleteModal = function() {
            $('#delete-modal').addClass('hidden');
        };
        window.confirmDelete = function() {
            $.ajax({
                url: `/users/wali/${currentWaliId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    closeDeleteModal();
                    table.ajax.reload(null, false);
                    updateStats();
                    showToast(response.message || 'Wali berhasil dihapus', 'success');
                },
                error: function(xhr) {
                    closeDeleteModal();
                    showToast(xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                }
            });
        };

        // Close modal on overlay click
        $('.modal-overlay').on('click', function(e) {
            if (e.target === this) $(this).addClass('hidden');
        });

        // Init stats
        updateStats();
    </script>
@endpush
