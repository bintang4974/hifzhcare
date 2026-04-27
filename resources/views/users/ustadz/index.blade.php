@extends('layouts.app-enhanced')

@section('title', 'Manajemen Ustadz')
@section('breadcrumb', 'Pengguna / Ustadz')

@section('content')
    <div class="ustadz-page">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Manajemen Ustadz</h1>
                <p class="page-subtitle">Kelola data ustadz dan monitor aktivitas mengajar</p>
            </div>
            @can('create_users')
                <a href="{{ route('users.ustadz.create') }}" class="btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Tambah Ustadz
                </a>
            @endcan
        </div>

        {{-- Stats Cards --}}
        <div class="stats-grid">
            <div class="stat-card stat-green">
                <div class="stat-content">
                    <p class="stat-label">Total Ustadz</p>
                    <h3 class="stat-value" id="total-ustadz">—</h3>
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

            <div class="stat-card stat-blue">
                <div class="stat-content">
                    <p class="stat-label">Ustadz Aktif</p>
                    <h3 class="stat-value" id="active-ustadz">—</h3>
                </div>
                <div class="stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                </div>
            </div>

            <div class="stat-card stat-amber">
                <div class="stat-content">
                    <p class="stat-label">Pending</p>
                    <h3 class="stat-value" id="pending-ustadz">—</h3>
                </div>
                <div class="stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
            </div>

            <div class="stat-card stat-purple">
                <div class="stat-content">
                    <p class="stat-label">Total Kelas</p>
                    <h3 class="stat-value" id="total-classes">—</h3>
                </div>
                <div class="stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" />
                        <rect x="14" y="3" width="7" height="7" />
                        <rect x="14" y="14" width="7" height="7" />
                        <rect x="3" y="14" width="7" height="7" />
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
                        <option value="pending">Pending</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
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
                <h3 class="table-title">Daftar Ustadz</h3>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <div class="table-search">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" class="search-icon">
                            <circle cx="11" cy="11" r="8" />
                            <path d="M21 21l-4.35-4.35" />
                        </svg>
                        <input type="text" id="ustadz-search" placeholder="Cari ustadz, email..."
                            class="search-input">
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

            {{-- Mobile Card List (< 768px) --}}
            <div class="mobile-list" id="mobile-card-list">
                <div class="mobile-loading">Memuat data...</div>
            </div>

            <div class="table-wrap">
                <table id="ustadz-table">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th class="col-ustadz">Ustadz</th>
                            <th class="col-nip">NIP</th>
                            <th class="col-email">Email</th>
                            <th class="col-hp">No HP</th>
                            <th class="col-kelas">Kelas</th>
                            <th class="col-verified">Verified</th>
                            <th class="col-status">Status</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Activate Modal --}}
    <div id="activate-modal" class="modal-overlay hidden">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-icon modal-icon-success">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M20 6L9 17l-5-5" />
                    </svg>
                </div>
                <div>
                    <h3 class="modal-title">Aktivasi Akun Ustadz</h3>
                    <p class="modal-subtitle">Akun akan diaktifkan dan dapat login ke sistem</p>
                </div>
            </div>
            <form id="activate-form">
                <div class="form-group">
                    <label class="form-label">Password <span class="text-muted">(Opsional)</span></label>
                    <input type="password" id="activate-password" class="form-input"
                        placeholder="Kosongkan untuk generate otomatis">
                    <p class="form-hint">Jika dikosongkan, password akan di-generate otomatis</p>
                </div>
                <div class="modal-actions">
                    <button type="button" onclick="closeActivateModal()"
                        class="btn-modal btn-modal-cancel">Batal</button>
                    <button type="submit" class="btn-modal btn-modal-success">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path d="M20 6L9 17l-5-5" />
                        </svg>
                        Aktivasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirm Modal --}}
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
                    <h3 class="modal-title">Hapus Ustadz</h3>
                    <p class="modal-subtitle">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>
            <p class="modal-body-text">Apakah Anda yakin ingin menghapus data ustadz ini? Semua data terkait akan ikut
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
        * {
            box-sizing: border-box;
        }

        .ustadz-page {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1rem 0.875rem 3rem;
        }

        /* --- Page Header --- */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
            line-height: 1.2;
        }

        .page-subtitle {
            font-size: 0.8125rem;
            color: #6b7280;
            margin: 3px 0 0;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            flex-shrink: 0;
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
            gap: 10px;
            margin-bottom: 1rem;
        }

        .stat-card {
            border-radius: 12px;
            padding: 0.875rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            border: 1px solid transparent;
            min-width: 0;
        }

        .stat-content {
            min-width: 0;
            flex: 1;
        }

        .stat-label {
            font-size: 0.6875rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            margin: 0 0 4px;
            opacity: 0.85;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            line-height: 1;
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            opacity: 0.85;
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

        .stat-purple {
            background: #ede9fe;
            color: #3b0764;
            border-color: #ddd6fe;
        }

        .stat-purple .stat-icon {
            background: #ddd6fe;
            color: #7c3aed;
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
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
            margin-bottom: 10px;
        }

        .filter-label {
            display: block;
            font-size: 0.6875rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 4px;
        }

        .filter-input {
            width: 100%;
            padding: 7px 10px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 0.8125rem;
            color: #374151;
            background: #fff;
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
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 7px;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
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
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            flex-wrap: wrap;
            gap: 8px;
        }

        .table-title {
            font-size: 0.9375rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .table-search {
            position: relative;
            display: flex;
            align-items: center;
            flex: 1;
            max-width: 180px;
        }

        .search-icon {
            position: absolute;
            left: 9px;
            color: #9ca3af;
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            padding: 7px 10px 7px 30px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 0.8125rem;
            color: #374151;
        }

        .search-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .refresh-btn {
            width: 32px;
            height: 32px;
            border: 1px solid #e5e7eb;
            border-radius: 7px;
            background: #fff;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            flex-shrink: 0;
        }

        .refresh-btn:hover {
            background: #f3f4f6;
            color: #111827;
        }

        /* --- DESKTOP TABLE (≥ 768px) --- */
        .table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            display: none;
        }

        #ustadz-table {
            width: 100% !important;
            border-collapse: collapse;
            font-size: 0.8125rem;
        }

        #ustadz-table thead th {
            padding: 10px 14px;
            background: #f9fafb;
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
            border-top: none;
            white-space: nowrap;
        }

        #ustadz-table thead th.sorting,
        #ustadz-table thead th.sorting_asc,
        #ustadz-table thead th.sorting_desc {
            background-image: none !important;
            padding-right: 14px;
        }

        #ustadz-table tbody td {
            padding: 11px 14px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
            color: #374151;
            background: #fff;
        }

        #ustadz-table tbody tr:last-child td {
            border-bottom: none;
        }

        #ustadz-table tbody tr:hover td {
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
            padding: 12px 20px !important;
        }

        /* --- MOBILE CARD LIST (< 768px) --- */
        .mobile-list {
            display: block;
        }

        .mobile-loading {
            padding: 2rem 1rem;
            text-align: center;
            color: #9ca3af;
            font-size: 0.875rem;
        }

        .ustadz-card-item {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .ustadz-card-item:last-child {
            border-bottom: none;
        }

        .ustadz-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 8px;
        }

        .ustadz-card-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            min-width: 0;
        }

        .ustadz-card-info {
            flex: 1;
            min-width: 0;
        }

        .ustadz-card-name {
            font-weight: 700;
            font-size: 0.9rem;
            color: #111827;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ustadz-card-nip {
            font-family: monospace;
            font-size: 0.6875rem;
            color: #9ca3af;
            margin-top: 2px;
        }

        .ustadz-card-contact {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 6px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .ustadz-card-contact span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ustadz-card-kelas {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-top: 8px;
        }

        .ustadz-card-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-top: 8px;
            flex-wrap: wrap;
        }

        .ustadz-card-verified {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.75rem;
            color: #6b7280;
        }

        .ustadz-card-actions {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #f3f4f6;
        }

        /* --- Shared Components --- */
        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .ustadz-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ustadz-name {
            font-weight: 600;
            color: #111827;
            font-size: 0.8125rem;
            line-height: 1.3;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.6875rem;
            font-weight: 600;
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

        .nip-chip {
            font-family: monospace;
            font-size: 0.75rem;
            background: #f3f4f6;
            color: #374151;
            padding: 3px 8px;
            border-radius: 5px;
            letter-spacing: 0.5px;
        }

        .kelas-tag {
            display: inline-flex;
            align-items: center;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 0.6875rem;
            font-weight: 600;
            background: #dbeafe;
            color: #1e40af;
            white-space: nowrap;
        }

        .kelas-empty {
            font-size: 0.75rem;
            color: #d1d5db;
        }

        .verified-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 26px;
            height: 22px;
            border-radius: 6px;
            background: #f3f4f6;
            color: #374151;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0 6px;
        }

        /* --- Action Buttons --- */
        .action-group {
            display: flex;
            align-items: center;
            gap: 4px;
            justify-content: center;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 7px;
            border: 1px solid #e5e7eb;
            background: #fff;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #6b7280;
            flex-shrink: 0;
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

        .action-btn.activate {
            color: #16a34a;
        }

        .action-btn.activate:hover {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .action-btn.toggle {
            color: #0ea5e9;
        }

        .action-btn.toggle:hover {
            background: #f0f9ff;
            border-color: #bae6fd;
        }

        .action-btn.del {
            color: #dc2626;
        }

        .action-btn.del:hover {
            background: #fef2f2;
            border-color: #fecaca;
        }

        /* --- Table Footer / Pagination --- */
        .table-footer-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 1rem;
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

        /* --- Modal — bottom sheet on mobile --- */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 9999;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding: 0;
        }

        .modal-overlay.hidden {
            display: none;
        }

        .modal-box {
            background: #fff;
            border-radius: 14px 14px 0 0;
            padding: 1.5rem 1.25rem;
            width: 100%;
            box-shadow: 0 -8px 30px rgba(0, 0, 0, 0.12);
            animation: slideUp 0.25s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
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

        .modal-icon-success {
            background: #d1fae5;
            color: #065f46;
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

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .text-muted {
            color: #9ca3af;
            font-weight: 400;
        }

        .form-input {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            color: #374151;
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-hint {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-top: 5px;
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
            padding: 11px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            min-height: 44px;
        }

        .btn-modal-cancel {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-modal-cancel:hover {
            background: #e5e7eb;
        }

        .btn-modal-success {
            background: #16a34a;
            color: #fff;
        }

        .btn-modal-success:hover {
            background: #15803d;
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
            bottom: 1rem;
            left: 0.75rem;
            right: 0.75rem;
            background: #111827;
            color: #fff;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 99999;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
            animation: toastIn 0.25s ease;
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

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* =============================================
                                   BREAKPOINTS
                                   ============================================= */

        @media (min-width: 480px) {
            .filter-grid {
                max-width: 320px;
            }
        }

        @media (min-width: 640px) {
            .modal-overlay {
                align-items: center;
                padding: 1rem;
            }

            .modal-box {
                border-radius: 14px;
                max-width: 440px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            }

            .toast {
                left: auto;
                right: 1.5rem;
                max-width: 340px;
            }
        }

        @media (min-width: 768px) {
            .ustadz-page {
                padding: 1.5rem 1.25rem 3rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .stat-value {
                font-size: 1.75rem;
            }

            .stat-label {
                font-size: 0.75rem;
            }

            .stat-icon {
                width: 44px;
                height: 44px;
            }

            .stat-card {
                padding: 1.1rem 1.25rem;
            }

            /* Show table, hide mobile cards */
            .table-wrap {
                display: block;
            }

            .mobile-list {
                display: none !important;
            }

            .table-search {
                max-width: 220px;
            }
        }

        @media (min-width: 1024px) {
            .ustadz-page {
                padding: 1.5rem 1rem 3rem;
            }

            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
            }

            .table-search {
                max-width: 240px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        let table;
        let currentUstadzId;

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

            $('#ustadz-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            function renderMobileCards(rows) {
                const container = $('#mobile-card-list');
                if (!rows.length) {
                    container.html('<div class="mobile-loading">Tidak ada data ditemukan</div>');
                    return;
                }

                const canEdit = {{ auth()->user()->can('edit_users') ? 'true' : 'false' }};
                const canDel = {{ auth()->user()->can('delete_users') ? 'true' : 'false' }};

                let html = '';
                rows.forEach(function(row) {
                    const color = getAvatarColor(row.name || '');
                    const initials = getInitials(row.name || '?');

                    // --- status badge ---
                    const statusMap = {
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
                    const sKey = (row.status || '').toLowerCase();
                    const sInfo = statusMap[sKey] || {
                        cls: 'badge-inactive',
                        label: row.status
                    };
                    const statusBadge = `<span class="badge ${sInfo.cls}">${sInfo.label}</span>`;

                    // --- kelas tags ---
                    let kelasHtml = '<span class="kelas-empty">Belum ada kelas</span>';
                    const d = row.classes;
                    if (d && d !== 'Belum ada kelas' && d !== '' && d !== '-') {
                        if (typeof d === 'string' && !d.includes('<')) {
                            const tags = d.split(',').map(k => `<span class="kelas-tag">${k.trim()}</span>`)
                                .join('');
                            kelasHtml = tags || kelasHtml;
                        } else if (typeof d === 'string') {
                            kelasHtml = d;
                        }
                    }

                    // --- contact info ---
                    const email = row.email ? `<span>${row.email}</span>` : '';
                    const phone = row.phone ? `<span>${row.phone}</span>` : '';
                    const contactHtml = (email || phone) ?
                        `<div class="ustadz-card-contact">${email}${phone}</div>` :
                        '';

                    // --- verified count ---
                    const verifiedHtml = `<div class="ustadz-card-verified">
            <span class="verified-count">${row.verified_today ?? 0}</span>
            <span>diverifikasi hari ini</span>
        </div>`;

                    // --- action buttons ---
                    const showUrl = `/users/ustadz/${row.id}`;
                    const editUrl = `/users/ustadz/${row.id}/edit`;

                    let actions = `
            <a href="${showUrl}" class="action-btn view" title="Lihat Detail">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                </svg>
            </a>`;

                    if (canEdit) {
                        const toggleTitle = sKey === 'active' ? 'Nonaktifkan' : 'Aktifkan';
                        const toggleIcon = sKey === 'active' ?
                            `<svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/></svg>` :
                            `<svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/></svg>`;

                        actions += `
            <a href="${editUrl}" class="action-btn edit" title="Edit">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
            </a>
            <button class="action-btn toggle" onclick="toggleUstadzStatus(${row.id})" title="${toggleTitle}">
                ${toggleIcon}
            </button>`;
                    }

                    if (sKey === 'pending') {
                        actions += `
            <button class="action-btn activate" onclick="activateUstadz(${row.id})" title="Aktivasi">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
            </button>`;
                    }

                    if (canDel) {
                        actions += `
            <button class="action-btn del" onclick="deleteUstadz(${row.id})" title="Hapus">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6m5 0V4h4v2"/>
                </svg>
            </button>`;
                    }

                    html += `
        <div class="ustadz-card-item">
            <div class="ustadz-card-top">
                <div class="ustadz-card-left">
                    <div class="avatar" style="background:${color.bg};color:${color.text}">${initials}</div>
                    <div class="ustadz-card-info">
                        <div class="ustadz-card-name">${row.name || '—'}</div>
                        ${row.nip ? `<div class="ustadz-card-nip">${row.nip}</div>` : ''}
                    </div>
                </div>
                ${statusBadge}
            </div>
            ${contactHtml}
            <div class="ustadz-card-kelas">${kelasHtml}</div>
            <div class="ustadz-card-meta">
                ${verifiedHtml}
            </div>
            <div class="ustadz-card-actions">
                <div class="action-group">${actions}</div>
            </div>
        </div>`;
                });

                container.html(html);
            }

            table = $('#ustadz-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('users.ustadz.index') }}",
                    data: function(d) {
                        d.status = $('#filter-status').val();
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
                        data: 'nip',
                        name: 'nip'
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
                        data: 'classes',
                        name: 'classes',
                        orderable: false
                    },
                    {
                        data: 'verified_today',
                        name: 'verified_today'
                    },
                    {
                        data: 'status',
                        name: 'status'
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
                        className: 'col-ustadz',
                        render: function(d, t, row) {
                            const color = getAvatarColor(d);
                            const initials = getInitials(d);
                            return `<div class="ustadz-cell">
                        <div class="avatar" style="background:${color.bg};color:${color.text}">${initials}</div>
                        <div>
                            <div class="ustadz-name">${d}</div>
                        </div>
                    </div>`;
                        }
                    },
                    {
                        targets: 2,
                        className: 'col-nip',
                        render: d => d ? `<span class="nip-chip">${d}</span>` :
                            '<span style="color:#d1d5db;font-size:0.75rem">—</span>'
                    },
                    {
                        targets: 3,
                        className: 'col-email',
                        render: d => d ? `<span style="font-size:0.8125rem;color:#374151">${d}</span>` :
                            '<span style="color:#d1d5db">—</span>'
                    },
                    {
                        targets: 4,
                        className: 'col-hp',
                        render: d => d ? `<span style="font-size:0.8125rem;color:#374151">${d}</span>` :
                            '<span style="color:#d1d5db">—</span>'
                    },
                    {
                        targets: 5,
                        className: 'col-kelas',
                        render: function(d) {
                            if (!d || d === '' || d === 'Belum ada kelas') {
                                return '<span class="kelas-empty">Belum ada kelas</span>';
                            }
                            // d could be HTML string of badges already, or plain text
                            // If it's plain text with commas, wrap each in badge
                            if (typeof d === 'string' && !d.includes('<')) {
                                return d.split(',').map(k =>
                                    `<span class="kelas-tag">${k.trim()}</span>`).join('');
                            }
                            return d;
                        }
                    },
                    {
                        targets: 6,
                        className: 'col-verified text-center',
                        render: d => `<span class="verified-count">${d ?? 0}</span>`
                    },
                    {
                        targets: 7,
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
                        targets: 8,
                        className: 'col-aksi',
                        render: function(d, t, row) {
                            const status = (row.status || '').toLowerCase();
                            const canEdit =
                                {{ auth()->user()->can('edit_users') ? 'true' : 'false' }};
                            const canDel =
                                {{ auth()->user()->can('delete_users') ? 'true' : 'false' }};
                            const showUrl = `/users/ustadz/${d}`;
                            const editUrl = `/users/ustadz/${d}/edit`;

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

                                // Toggle status button
                                const toggleIcon = status === 'active' ?
                                    `<svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/></svg>` :
                                    `<svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/></svg>`;
                                const toggleTitle = status === 'active' ? 'Nonaktifkan' :
                                    'Aktifkan';
                                html += `<button class="action-btn toggle" onclick="toggleUstadzStatus(${d})" title="${toggleTitle}">
                            ${toggleIcon}
                        </button>`;
                            }

                            if (status === 'pending') {
                                html += `<button class="action-btn activate" onclick="activateUstadz(${d})" title="Aktivasi">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M20 6L9 17l-5-5"/>
                            </svg>
                        </button>`;
                            }

                            if (canDel) {
                                html += `<button class="action-btn del" onclick="deleteUstadz(${d})" title="Hapus">
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
                    renderMobileCards(this.api().rows({
                        page: 'current'
                    }).data().toArray());
                }
            });

            function renderFooter(settings) {
                const api = new $.fn.dataTable.Api(settings);
                const info = api.page.info();
                const start = info.start + 1;
                const end = Math.min(info.end, info.recordsDisplay);
                const total = info.recordsDisplay;
                const page = info.page;
                const pages = info.pages;

                let paginationHtml =
                    `<button class="pg-btn ${page === 0 ? 'disabled' : ''}" onclick="goPage(${page - 1})">&lsaquo;</button>`;

                let startPage = Math.max(0, page - 2);
                let endPage = Math.min(pages - 1, startPage + 4);
                startPage = Math.max(0, endPage - 4);
                for (let i = startPage; i <= endPage; i++) {
                    paginationHtml +=
                        `<button class="pg-btn ${i === page ? 'active' : ''}" onclick="goPage(${i})">${i + 1}</button>`;
                }
                paginationHtml +=
                    `<button class="pg-btn ${page >= pages - 1 ? 'disabled' : ''}" onclick="goPage(${page + 1})">&rsaquo;</button>`;

                const infoText = total === 0 ?
                    'Tidak ada data ditemukan' :
                    `Menampilkan ${start}–${end} dari ${total} data`;

                $('#ustadz-table').closest('.table-card').find('.table-footer-info').remove();
                $('#ustadz-table').closest('.table-card').append(`
            <div class="table-footer-info">
                <span class="footer-text">${infoText}</span>
                <div class="pagination-custom">${paginationHtml}</div>
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
            $('#filter-status').val('');
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
            $.ajax({
                url: "{{ route('users.ustadz.stats') }}",
                success: function(data) {
                    $('#total-ustadz').text(data.total ?? '—');
                    $('#active-ustadz').text(data.active ?? '—');
                    $('#pending-ustadz').text(data.pending ?? '—');
                    $('#total-classes').text(data.total_classes ?? '—');
                }
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
        // Activate
        // =====================
        window.activateUstadz = function(id) {
            currentUstadzId = id;
            $('#activate-password').val('');
            $('#activate-modal').removeClass('hidden');
        };
        window.closeActivateModal = function() {
            $('#activate-modal').addClass('hidden');
        };

        $('#activate-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: `/users/ustadz/${currentUstadzId}/activate`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    password: $('#activate-password').val()
                },
                success: function(response) {
                    closeActivateModal();
                    table.ajax.reload(null, false);
                    updateStats();
                    const pwd = response.password ? ` | Password: ${response.password}` : '';
                    showToast((response.message || 'Akun berhasil diaktifkan') + pwd, 'success');
                },
                error: function(xhr) {
                    showToast(xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                }
            });
        });

        // =====================
        // Toggle Status
        // =====================
        window.toggleUstadzStatus = function(id) {
            $.ajax({
                url: `/users/ustadz/${id}/toggle-status`,
                method: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    table.ajax.reload(null, false);
                    updateStats();
                    showToast(response.message || 'Status berhasil diubah', 'success');
                },
                error: function(xhr) {
                    showToast(xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                }
            });
        };

        // =====================
        // Delete
        // =====================
        window.deleteUstadz = function(id) {
            currentUstadzId = id;
            $('#delete-modal').removeClass('hidden');
        };
        window.closeDeleteModal = function() {
            $('#delete-modal').addClass('hidden');
        };
        window.confirmDelete = function() {
            $.ajax({
                url: `/users/ustadz/${currentUstadzId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    closeDeleteModal();
                    table.ajax.reload(null, false);
                    updateStats();
                    showToast(response.message || 'Ustadz berhasil dihapus', 'success');
                },
                error: function(xhr) {
                    closeDeleteModal();
                    showToast(xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                }
            });
        };

        // Close modals on overlay click
        $('.modal-overlay').on('click', function(e) {
            if (e.target === this) $(this).addClass('hidden');
        });

        // Init stats on load
        updateStats();
    </script>
@endpush
