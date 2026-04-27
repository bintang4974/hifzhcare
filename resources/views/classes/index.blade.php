@extends('layouts.app-enhanced')
@section('title', 'Manajemen Kelas')
@section('breadcrumb', 'Kelas')

@section('content')
    <div class="kelas-page">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Manajemen Kelas</h1>
                <p class="page-subtitle">Kelola kelas dan anggota kelas</p>
            </div>
            @can('create_classes')
                <a href="{{ route('classes.create') }}" class="btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Tambah Kelas
                </a>
            @endcan
        </div>

        {{-- Stats Cards --}}
        <div class="stats-grid">
            <div class="stat-card stat-blue">
                <div class="stat-content">
                    <p class="stat-label">Total Kelas</p>
                    <h3 class="stat-value" id="total-classes">—</h3>
                </div>
                <div class="stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <rect x="2" y="3" width="20" height="14" rx="2" />
                        <path d="M8 21h8M12 17v4" />
                    </svg>
                </div>
            </div>

            <div class="stat-card stat-green">
                <div class="stat-content">
                    <p class="stat-label">Kelas Aktif</p>
                    <h3 class="stat-value" id="active-classes">—</h3>
                </div>
                <div class="stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                </div>
            </div>

            <div class="stat-card stat-purple">
                <div class="stat-content">
                    <p class="stat-label">Total Santri</p>
                    <h3 class="stat-value" id="total-students">—</h3>
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

            <div class="stat-card stat-amber">
                <div class="stat-content">
                    <p class="stat-label">Total Ustadz</p>
                    <h3 class="stat-value" id="total-teachers">—</h3>
                </div>
                <div class="stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="card table-card">
            <div class="table-header">
                <h3 class="table-title">Daftar Kelas</h3>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <div class="table-search">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" class="search-icon">
                            <circle cx="11" cy="11" r="8" />
                            <path d="M21 21l-4.35-4.35" />
                        </svg>
                        <input type="text" id="kelas-search" placeholder="Cari nama kelas, kode..." class="search-input">
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
                <table id="classes-table">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th class="col-nama">Nama Kelas</th>
                            <th class="col-kode">Kode</th>
                            <th class="col-ustadz">Ustadz</th>
                            <th class="col-kapasitas">Kapasitas</th>
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
                    <h3 class="modal-title">Hapus Kelas</h3>
                    <p class="modal-subtitle">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>
            <p class="modal-body-text">Apakah Anda yakin ingin menghapus kelas ini? Semua data anggota kelas terkait akan
                ikut terpengaruh.</p>
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

{{-- @push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <style>
        /* =============================================
       KELAS PAGE — CONSISTENT WITH HAFALAN MODULE
       ============================================= */

        .kelas-page {
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

        .stat-blue {
            background: #dbeafe;
            color: #1e3a8a;
            border-color: #bfdbfe;
        }

        .stat-blue .stat-icon {
            background: #bfdbfe;
            color: #1d4ed8;
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

        .stat-purple {
            background: #ede9fe;
            color: #3b0764;
            border-color: #ddd6fe;
        }

        .stat-purple .stat-icon {
            background: #ddd6fe;
            color: #7c3aed;
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

        #classes-table {
            width: 100% !important;
            border-collapse: collapse;
            font-size: 0.8125rem;
            min-width: 620px;
        }

        #classes-table thead th {
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

        #classes-table thead th.sorting,
        #classes-table thead th.sorting_asc,
        #classes-table thead th.sorting_desc {
            background-image: none !important;
            padding-right: 14px;
        }

        #classes-table tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
            color: #374151;
            background: #fff;
        }

        #classes-table tbody tr:last-child td {
            border-bottom: none;
        }

        #classes-table tbody tr:hover td {
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

        /* --- Kelas Name Cell --- */
        .kelas-name {
            font-weight: 600;
            color: #111827;
            font-size: 0.875rem;
        }

        /* --- Kode chip --- */
        .kode-chip {
            font-family: monospace;
            font-size: 0.7rem;
            font-weight: 600;
            background: #f3f4f6;
            color: #374151;
            padding: 3px 8px;
            border-radius: 5px;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        /* --- Ustadz cell --- */
        .ustadz-item {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 3px;
        }

        .ustadz-item:last-child {
            margin-bottom: 0;
        }

        .ustadz-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 0.6rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            letter-spacing: 0.2px;
        }

        .ustadz-name-text {
            font-size: 0.8125rem;
            color: #374151;
            font-weight: 500;
        }

        .ustadz-empty {
            font-size: 0.75rem;
            color: #d1d5db;
        }

        /* --- Capacity bar --- */
        .capacity-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .capacity-bar-bg {
            width: 72px;
            height: 5px;
            background: #e5e7eb;
            border-radius: 99px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .capacity-bar-fill {
            height: 100%;
            border-radius: 99px;
            background: #2563eb;
            transition: width 0.3s;
        }

        .capacity-bar-fill.full {
            background: #dc2626;
        }

        .capacity-bar-fill.high {
            background: #f59e0b;
        }

        .capacity-bar-fill.normal {
            background: #2563eb;
        }

        .capacity-text {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
            min-width: 36px;
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

        .badge-inactive {
            background: #f3f4f6;
            color: #6b7280;
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

        .action-btn.member {
            color: #16a34a;
        }

        .action-btn.member:hover {
            background: #f0fdf4;
            border-color: #bbf7d0;
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
        @media (max-width: 768px) {
            .col-kapasitas {
                display: none;
            }
        }

        @media (max-width: 640px) {
            .col-kode {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .kelas-page {
                padding: 1rem 0.75rem 2rem;
            }

            .page-title {
                font-size: 1.25rem;
            }
        }
    </style>
@endpush --}}
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        .kelas-page {
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

        .stat-blue {
            background: #dbeafe;
            color: #1e3a8a;
            border-color: #bfdbfe;
        }

        .stat-blue .stat-icon {
            background: #bfdbfe;
            color: #1d4ed8;
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

        .stat-purple {
            background: #ede9fe;
            color: #3b0764;
            border-color: #ddd6fe;
        }

        .stat-purple .stat-icon {
            background: #ddd6fe;
            color: #7c3aed;
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
            /* hidden on mobile, shown via breakpoint */
        }

        #classes-table {
            width: 100% !important;
            border-collapse: collapse;
            font-size: 0.8125rem;
        }

        #classes-table thead th {
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

        #classes-table thead th.sorting,
        #classes-table thead th.sorting_asc,
        #classes-table thead th.sorting_desc {
            background-image: none !important;
            padding-right: 14px;
        }

        #classes-table tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
            color: #374151;
            background: #fff;
        }

        #classes-table tbody tr:last-child td {
            border-bottom: none;
        }

        #classes-table tbody tr:hover td {
            background: #f9fafb;
        }

        /* DataTable hidden controls */
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

        .kelas-card-item {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .kelas-card-item:last-child {
            border-bottom: none;
        }

        .kelas-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 8px;
        }

        .kelas-card-name {
            font-weight: 700;
            font-size: 0.9375rem;
            color: #111827;
            line-height: 1.3;
        }

        .kelas-card-code {
            font-family: monospace;
            font-size: 0.6875rem;
            font-weight: 600;
            background: #f3f4f6;
            color: #374151;
            padding: 2px 7px;
            border-radius: 5px;
            margin-top: 3px;
            display: inline-block;
        }

        .kelas-card-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .kelas-card-ustadz {
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1;
            min-width: 0;
        }

        .kelas-card-cap {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .kelas-card-actions {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #f3f4f6;
        }

        /* --- Shared Cell Components --- */
        .kelas-name {
            font-weight: 600;
            color: #111827;
            font-size: 0.875rem;
        }

        .kode-chip {
            font-family: monospace;
            font-size: 0.7rem;
            font-weight: 600;
            background: #f3f4f6;
            color: #374151;
            padding: 3px 8px;
            border-radius: 5px;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .ustadz-item {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 3px;
        }

        .ustadz-item:last-child {
            margin-bottom: 0;
        }

        .ustadz-avatar {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            font-size: 0.6rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .ustadz-name-text {
            font-size: 0.8125rem;
            color: #374151;
            font-weight: 500;
        }

        .ustadz-empty {
            font-size: 0.75rem;
            color: #d1d5db;
        }

        .capacity-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .capacity-bar-bg {
            width: 60px;
            height: 5px;
            background: #e5e7eb;
            border-radius: 99px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .capacity-bar-fill {
            height: 100%;
            border-radius: 99px;
        }

        .capacity-bar-fill.full {
            background: #dc2626;
        }

        .capacity-bar-fill.high {
            background: #f59e0b;
        }

        .capacity-bar-fill.normal {
            background: #2563eb;
        }

        .capacity-text {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
        }

        /* --- Badges --- */
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

        .badge-inactive {
            background: #f3f4f6;
            color: #6b7280;
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
            flex-shrink: 0;
        }

        .action-btn.view {
            color: #2563eb;
        }

        .action-btn.view:hover {
            background: #eff6ff;
            border-color: #bfdbfe;
        }

        .action-btn.member {
            color: #16a34a;
        }

        .action-btn.member:hover {
            background: #f0fdf4;
            border-color: #bbf7d0;
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
            .kelas-page {
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

            /* Show desktop table, hide mobile cards */
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
            .kelas-page {
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
        let currentClassId;

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

        function renderCapacity(data) {
            if (!data) return '<span style="color:#d1d5db">—</span>';

            // data expected as "5/30" string or object {current, max}
            let current = 0,
                max = 0;
            if (typeof data === 'string' && data.includes('/')) {
                const parts = data.split('/');
                current = parseInt(parts[0]) || 0;
                max = parseInt(parts[1]) || 0;
            } else if (typeof data === 'object') {
                current = data.current || 0;
                max = data.max || 0;
            } else {
                return `<span class="capacity-text">${data}</span>`;
            }

            const pct = max > 0 ? Math.min((current / max) * 100, 100) : 0;
            const cls = pct >= 100 ? 'full' : pct >= 80 ? 'high' : 'normal';

            return `<div class="capacity-wrap">
        <div class="capacity-bar-bg">
            <div class="capacity-bar-fill ${cls}" style="width:${pct}%"></div>
        </div>
        <span class="capacity-text">${current}/${max}</span>
    </div>`;
        }

        $(document).ready(function() {

            $('#kelas-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            function renderMobileCards(rows) {
                const container = $('#mobile-card-list');
                if (!rows.length) {
                    container.html('<div class="mobile-loading">Tidak ada data ditemukan</div>');
                    return;
                }

                const canEdit = {{ auth()->user()->can('edit_classes') ? 'true' : 'false' }};
                const canDel = {{ auth()->user()->can('delete_classes') ? 'true' : 'false' }};

                let html = '';
                rows.forEach(function(row) {
                    // --- status badge ---
                    const statusLower = (row.status || '').toLowerCase();
                    let statusBadge;
                    if (typeof row.status === 'string' && row.status.includes('<')) {
                        statusBadge = row.status;
                    } else {
                        const label = statusLower === 'active' ? 'Aktif' :
                            statusLower === 'inactive' ? 'Tidak Aktif' :
                            row.status;
                        const cls = statusLower === 'active' ? 'badge-active' : 'badge-inactive';
                        statusBadge = `<span class="badge ${cls}">${label}</span>`;
                    }

                    // --- ustadz list ---
                    let ustadzHtml = '<span class="ustadz-empty">Belum ada ustadz</span>';
                    const d = row.ustadz;
                    if (d && d !== 'Belum ada ustadz' && d !== '-' && d !== '') {
                        let names = [];
                        if (Array.isArray(d)) {
                            names = d;
                        } else if (typeof d === 'string' && !d.includes('<')) {
                            names = d.split(',').map(n => n.trim()).filter(Boolean);
                        }
                        if (names.length) {
                            ustadzHtml = names.map(name => {
                                const color = getAvatarColor(name);
                                const initials = getInitials(name);
                                return `<div class="ustadz-item">
                        <div class="ustadz-avatar" style="background:${color.bg};color:${color.text}">${initials}</div>
                        <span class="ustadz-name-text">${name}</span>
                    </div>`;
                            }).join('');
                        }
                    }

                    // --- capacity ---
                    let capHtml = '';
                    const cap = row.capacity;
                    if (cap) {
                        let current = 0,
                            max = 0;
                        if (typeof cap === 'string' && cap.includes('/')) {
                            const parts = cap.split('/');
                            current = parseInt(parts[0]) || 0;
                            max = parseInt(parts[1]) || 0;
                        } else if (typeof cap === 'object') {
                            current = cap.current || 0;
                            max = cap.max || 0;
                        }
                        if (max > 0) {
                            const pct = Math.min((current / max) * 100, 100);
                            const cls = pct >= 100 ? 'full' : pct >= 80 ? 'high' : 'normal';
                            capHtml = `<div class="capacity-wrap">
                    <div class="capacity-bar-bg">
                        <div class="capacity-bar-fill ${cls}" style="width:${pct}%"></div>
                    </div>
                    <span class="capacity-text">${current}/${max}</span>
                </div>`;
                        }
                    }

                    // --- action buttons ---
                    let actions = `
            <a href="/classes/${row.id}" class="action-btn view" title="Lihat Detail">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                </svg>
            </a>
            <a href="/classes/${row.id}/members" class="action-btn member" title="Anggota Kelas">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                </svg>
            </a>`;

                    if (canEdit) {
                        actions += `
            <a href="/classes/${row.id}/edit" class="action-btn edit" title="Edit Kelas">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
            </a>`;
                    }

                    if (canDel) {
                        actions += `
            <button class="action-btn del" onclick="deleteClass(${row.id})" title="Hapus Kelas">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6m5 0V4h4v2"/>
                </svg>
            </button>`;
                    }

                    html += `
        <div class="kelas-card-item">
            <div class="kelas-card-top">
                <div>
                    <div class="kelas-card-name">${row.name || '—'}</div>
                    ${row.code ? `<span class="kelas-card-code">${row.code}</span>` : ''}
                </div>
                ${statusBadge}
            </div>
            <div class="kelas-card-ustadz">${ustadzHtml}</div>
            ${capHtml ? `<div class="kelas-card-meta">${capHtml}</div>` : ''}
            <div class="kelas-card-actions">
                <div class="action-group">${actions}</div>
            </div>
        </div>`;
                });

                container.html(html);
            }

            table = $('#classes-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('classes.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'ustadz',
                        name: 'ustadz',
                        orderable: false
                    },
                    {
                        data: 'capacity',
                        name: 'capacity',
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
                        className: 'col-nama',
                        render: d => `<span class="kelas-name">${d}</span>`
                    },
                    {
                        targets: 2,
                        className: 'col-kode',
                        render: d => d ?
                            `<span class="kode-chip">${d}</span>` :
                            '<span style="color:#d1d5db">—</span>'
                    },
                    {
                        targets: 3,
                        className: 'col-ustadz',
                        render: function(d) {
                            if (!d || d === 'Belum ada ustadz' || d === '-' || d === '') {
                                return '<span class="ustadz-empty">Belum ada ustadz</span>';
                            }

                            // d could be: array of names, comma-sep string, or plain string
                            let names = [];
                            if (Array.isArray(d)) {
                                names = d;
                            } else if (typeof d === 'string' && !d.includes('<')) {
                                names = d.split(',').map(n => n.trim()).filter(Boolean);
                            } else {
                                return d; // already HTML from server
                            }

                            return names.map(name => {
                                const color = getAvatarColor(name);
                                const initials = getInitials(name);
                                return `<div class="ustadz-item">
                            <div class="ustadz-avatar" style="background:${color.bg};color:${color.text}">${initials}</div>
                            <span class="ustadz-name-text">${name}</span>
                        </div>`;
                            }).join('');
                        }
                    },
                    {
                        targets: 4,
                        className: 'col-kapasitas',
                        render: d => renderCapacity(d)
                    },
                    {
                        targets: 5,
                        className: 'col-status text-center',
                        render: function(d) {
                            // d may be HTML badge from server or plain string
                            if (typeof d === 'string' && d.includes('<')) return d;
                            const lower = (d || '').toLowerCase();
                            const label = lower === 'active' ? 'Aktif' : lower === 'inactive' ?
                                'Tidak Aktif' : d;
                            const cls = lower === 'active' ? 'badge-active' : 'badge-inactive';
                            return `<span class="badge ${cls}">${label}</span>`;
                        }
                    },
                    {
                        targets: 6,
                        className: 'col-aksi',
                        render: function(d, t, row) {
                            const canEdit =
                                {{ auth()->user()->can('edit_classes') ? 'true' : 'false' }};
                            const canDel =
                                {{ auth()->user()->can('delete_classes') ? 'true' : 'false' }};

                            let html = `<div class="action-group">
                        <a href="/classes/${d}" class="action-btn view" title="Lihat Detail">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </a>
                        <a href="/classes/${d}/members" class="action-btn member" title="Anggota Kelas">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                            </svg>
                        </a>`;

                            if (canEdit) {
                                html += `<a href="/classes/${d}/edit" class="action-btn edit" title="Edit Kelas">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </a>`;
                            }

                            if (canDel) {
                                html += `<button class="action-btn del" onclick="deleteClass(${d})" title="Hapus Kelas">
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
                    `Menampilkan ${start}–${end} dari ${total} kelas`;

                $('#classes-table').closest('.table-card').find('.table-footer-info').remove();
                $('#classes-table').closest('.table-card').append(`
            <div class="table-footer-info">
                <span class="footer-text">${infoText}</span>
                <div class="pagination-custom">${pagination}</div>
            </div>
        `);
            }

            // Load stats
            loadStats();
        });

        window.goPage = function(page) {
            if (page < 0) return;
            table.page(page).draw('page');
        };

        window.refreshTable = function() {
            loadStats();
            table.ajax.reload(null, false);
        };

        // =====================
        // Stats
        // =====================
        function loadStats() {
            $.getJSON("{{ route('classes.index') }}", {
                stats: 1
            }, function(data) {
                if (data.stats) {
                    $('#total-classes').text(data.stats.total ?? '—');
                    $('#active-classes').text(data.stats.active ?? '—');
                    $('#total-students').text(data.stats.students ?? '—');
                    $('#total-teachers').text(data.stats.teachers ?? '—');
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
        // Delete
        // =====================
        window.deleteClass = function(id) {
            currentClassId = id;
            $('#delete-modal').removeClass('hidden');
        };
        window.closeDeleteModal = function() {
            $('#delete-modal').addClass('hidden');
        };
        window.confirmDelete = function() {
            $.ajax({
                url: `/classes/${currentClassId}`,
                method: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    closeDeleteModal();
                    table.ajax.reload(null, false);
                    loadStats();
                    showToast(response.message || 'Kelas berhasil dihapus', 'success');
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
    </script>
@endpush
