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
        /* =============================================
           USTADZ PAGE — CONSISTENT WITH HAFALAN MODULE
           ============================================= */

        .ustadz-page {
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

        /* Color variants — flat, no gradient */
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
            padding: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 10px;
            margin-bottom: 12px;
        }

        @media (min-width: 480px) {
            .filter-grid {
                grid-template-columns: repeat(2, 1fr);
                max-width: 480px;
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

        #ustadz-table {
            width: 100% !important;
            border-collapse: collapse;
            font-size: 0.8125rem;
            min-width: 680px;
        }

        #ustadz-table thead th {
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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
            padding: 12px 20px !important;
        }

        /* --- Ustadz Cell --- */
        .ustadz-cell {
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

        .ustadz-name {
            font-weight: 600;
            color: #111827;
            font-size: 0.8125rem;
            line-height: 1.3;
        }

        .ustadz-phone {
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

        /* --- NIP chip --- */
        .nip-chip {
            font-family: monospace;
            font-size: 0.75rem;
            background: #f3f4f6;
            color: #374151;
            padding: 3px 8px;
            border-radius: 5px;
            letter-spacing: 0.5px;
        }

        /* --- Kelas tag --- */
        .kelas-tag {
            display: inline-flex;
            align-items: center;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 0.6875rem;
            font-weight: 600;
            background: #dbeafe;
            color: #1e40af;
            margin: 2px 2px 2px 0;
            white-space: nowrap;
        }

        .kelas-empty {
            font-size: 0.75rem;
            color: #d1d5db;
        }

        /* --- Verified count --- */
        .verified-count {
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
            color: #6b7280;
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
            transition: border-color 0.15s, box-shadow 0.15s;
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
            .col-hp {
                display: none;
            }
        }

        @media (max-width: 768px) {

            .col-email,
            .col-verified {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .col-nip {
                display: none;
            }

            .ustadz-page {
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
                                const toggleIcon = status === 'active' 
                                    ? `<svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/></svg>`
                                    : `<svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/></svg>`;
                                const toggleTitle = status === 'active' ? 'Nonaktifkan' : 'Aktifkan';
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
