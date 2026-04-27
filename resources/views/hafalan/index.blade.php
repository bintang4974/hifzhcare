@extends('layouts.app-enhanced')

@section('title', 'Daftar Hafalan')

@section('content')
    <div class="hafalan-page">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Daftar Hafalan</h1>
                <p class="page-subtitle">Kelola dan monitor hafalan santri</p>
            </div>
            @can('create_hafalan')
                <a href="{{ route('hafalan.create') }}" class="btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Tambah Hafalan
                </a>
            @endcan
        </div>

        {{-- Filter Card --}}
        <div class="card filter-card">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select id="filter-status" class="filter-input">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="verified">Verified</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Jenis</label>
                    <select id="filter-type" class="filter-input">
                        <option value="">Semua Jenis</option>
                        <option value="setoran">Setoran</option>
                        <option value="murajah">Muraja'ah</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Kelas</label>
                    <select id="filter-class" class="filter-input">
                        <option value="">Semua Kelas</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Juz</label>
                    <select id="filter-juz" class="filter-input">
                        <option value="">Semua Juz</option>
                        @for ($i = 1; $i <= 30; $i++)
                            <option value="{{ $i }}">Juz {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Dari Tanggal</label>
                    <input type="date" id="filter-date-from" class="filter-input">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Sampai Tanggal</label>
                    <input type="date" id="filter-date-to" class="filter-input">
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
                <h3 class="table-title">Daftar Hafalan</h3>
                <div class="table-search">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" class="search-icon">
                        <circle cx="11" cy="11" r="8" />
                        <path d="M21 21l-4.35-4.35" />
                    </svg>
                    <input type="text" id="hafalan-search" placeholder="Cari santri, surah..." class="search-input">
                </div>
            </div>

            {{-- Mobile Card List (< 768px) --}}
            <div class="mobile-list" id="mobile-card-list">
                <div class="mobile-loading">Memuat data...</div>
            </div>

            <div class="table-wrap">
                <table id="hafalan-table">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th class="col-santri">Santri</th>
                            <th class="col-surah">Surah &amp; Ayat</th>
                            <th class="col-juz">Juz</th>
                            <th class="col-jenis">Jenis</th>
                            <th class="col-status">Status</th>
                            <th class="col-audio">Audio</th>
                            <th class="col-tanggal">Tanggal</th>
                            <th class="col-verifikasi">Verifikasi</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- DataTables will populate this --}}
                    </tbody>
                </table>
            </div>

            <div class="table-footer-custom" id="table-footer-info">
                {{-- Filled by DataTable callbacks --}}
            </div>
        </div>

    </div>

    {{-- Verify Modal --}}
    <div id="verify-modal" class="modal-overlay hidden">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-icon modal-icon-success">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M20 6L9 17l-5-5" />
                    </svg>
                </div>
                <div>
                    <h3 class="modal-title">Verifikasi Hafalan</h3>
                    <p class="modal-subtitle">Konfirmasi bahwa hafalan ini sudah benar</p>
                </div>
            </div>
            <form id="verify-form">
                <div class="form-group">
                    <label class="form-label">Catatan <span class="text-muted">(Opsional)</span></label>
                    <textarea id="verify-notes" rows="3" class="form-textarea" placeholder="Tambahkan catatan untuk santri..."></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" onclick="closeVerifyModal()" class="btn-modal btn-modal-cancel">Batal</button>
                    <button type="submit" class="btn-modal btn-modal-success">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path d="M20 6L9 17l-5-5" />
                        </svg>
                        Verifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div id="reject-modal" class="modal-overlay hidden">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-icon modal-icon-danger">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <h3 class="modal-title">Tolak Hafalan</h3>
                    <p class="modal-subtitle">Berikan alasan penolakan yang jelas</p>
                </div>
            </div>
            <form id="reject-form">
                <div class="form-group">
                    <label class="form-label">Alasan Penolakan <span class="text-required">*</span></label>
                    <textarea id="reject-reason" rows="3" required class="form-textarea"
                        placeholder="Masukkan alasan penolakan..."></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" onclick="closeRejectModal()" class="btn-modal btn-modal-cancel">Batal</button>
                    <button type="submit" class="btn-modal btn-modal-danger">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                        Tolak Hafalan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirm Modal --}}
    <div id="delete-modal" class="modal-overlay hidden">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-icon modal-icon-warning">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6l-1 14H6L5 6m5 0V4h4v2" />
                    </svg>
                </div>
                <div>
                    <h3 class="modal-title">Hapus Hafalan</h3>
                    <p class="modal-subtitle">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>
            <p class="modal-body-text">Apakah Anda yakin ingin menghapus data hafalan ini? Data yang sudah dihapus tidak
                dapat dikembalikan.</p>
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

    {{-- Toast Notification --}}
    <div id="toast" class="toast hidden">
        <div class="toast-icon" id="toast-icon"></div>
        <span id="toast-message"></span>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <style>
        /* =============================================
                       HAFALAN PAGE — MOBILE-FIRST REDESIGN
                       ============================================= */

        * {
            box-sizing: border-box;
        }

        .hafalan-page {
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
            grid-template-columns: repeat(2, 1fr);
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
            -webkit-appearance: auto;
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
            max-width: 200px;
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

        /* --- DESKTOP TABLE (≥768px) --- */
        .table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            display: none;
            /* hidden on mobile */
        }

        #hafalan-table {
            width: 100% !important;
            border-collapse: collapse;
            font-size: 0.8125rem;
        }

        #hafalan-table thead th {
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

        #hafalan-table thead th:after,
        #hafalan-table thead th:before {
            display: none;
        }

        #hafalan-table thead th.sorting,
        #hafalan-table thead th.sorting_asc,
        #hafalan-table thead th.sorting_desc {
            background-image: none !important;
            padding-right: 14px;
        }

        #hafalan-table tbody td {
            padding: 11px 14px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
            color: #374151;
            background: #fff;
        }

        #hafalan-table tbody tr:last-child td {
            border-bottom: none;
        }

        #hafalan-table tbody tr:hover td {
            background: #f9fafb;
        }

        /* --- MOBILE CARD LIST (< 768px) --- */
        .mobile-list {
            display: block;
        }

        .mobile-list-empty {
            padding: 2rem 1rem;
            text-align: center;
            color: #9ca3af;
            font-size: 0.875rem;
        }

        .hafalan-card-item {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .hafalan-card-item:last-child {
            border-bottom: none;
        }

        .hafalan-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 8px;
        }

        .hafalan-card-left {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            flex: 1;
            min-width: 0;
        }

        .hafalan-card-info {
            flex: 1;
            min-width: 0;
        }

        .hafalan-card-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: #111827;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .hafalan-card-class {
            font-size: 0.6875rem;
            color: #9ca3af;
        }

        .hafalan-card-badges {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .hafalan-card-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 6px;
        }

        .hafalan-card-surah {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #111827;
        }

        .hafalan-card-ayat {
            font-size: 0.6875rem;
            color: #9ca3af;
        }

        .hafalan-card-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .hafalan-card-date {
            font-size: 0.6875rem;
            color: #9ca3af;
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

        /* --- Shared Cell Components --- */
        .santri-cell {
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
        }

        .avatar-sm {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .santri-name {
            font-weight: 600;
            color: #111827;
            font-size: 0.8125rem;
            line-height: 1.3;
        }

        .santri-class {
            font-size: 0.7rem;
            color: #9ca3af;
            margin-top: 1px;
        }

        .surah-name {
            font-weight: 600;
            color: #111827;
            font-size: 0.8125rem;
        }

        .surah-meta {
            font-size: 0.7rem;
            color: #9ca3af;
            margin-top: 2px;
        }

        /* --- Badges --- */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.6875rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-setoran {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-murajah {
            background: #e0f2fe;
            color: #0369a1;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-verified {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .juz-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            background: #f3f4f6;
            font-size: 0.75rem;
            font-weight: 700;
            color: #374151;
        }

        .audio-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1px solid #e5e7eb;
            background: #fff;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
        }

        .audio-btn:hover,
        .audio-btn.playing {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: #16a34a;
        }

        .no-audio {
            color: #d1d5db;
            font-size: 0.875rem;
        }

        .date-text {
            font-size: 0.75rem;
            color: #6b7280;
            white-space: nowrap;
        }

        .verified-by {
            font-size: 0.75rem;
            font-weight: 600;
            color: #374151;
        }

        .verified-date {
            font-size: 0.6875rem;
            color: #9ca3af;
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
            text-decoration: none;
            min-width: 30px;
            /* prevent shrink on mobile */
        }

        .action-btn.view {
            color: #2563eb;
        }

        .action-btn.view:hover {
            background: #eff6ff;
            border-color: #bfdbfe;
        }

        .action-btn.verify {
            color: #16a34a;
        }

        .action-btn.verify:hover {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .action-btn.reject {
            color: #dc2626;
        }

        .action-btn.reject:hover {
            background: #fef2f2;
            border-color: #fecaca;
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

        /* --- Modal --- */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 9999;
            display: flex;
            align-items: flex-end;
            /* bottom sheet on mobile */
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
            max-width: 100%;
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

        .modal-icon-warning {
            background: #fef3c7;
            color: #92400e;
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

        .text-required {
            color: #dc2626;
        }

        .form-textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            color: #374151;
            resize: vertical;
            font-family: inherit;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
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
            /* touch target */
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

        /* --- Loading overlay for mobile list --- */
        .mobile-loading {
            padding: 2rem 1rem;
            text-align: center;
            color: #9ca3af;
            font-size: 0.875rem;
        }

        /* =============================================
                       BREAKPOINTS — TABLET & DESKTOP
                       ============================================= */

        @media (min-width: 640px) {
            .filter-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .btn-filter {
                flex: none;
            }

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
            .hafalan-page {
                padding: 1.5rem 1.25rem 3rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            /* Show table, hide mobile cards */
            .table-wrap {
                display: block;
            }

            .mobile-list {
                display: none !important;
            }

            .filter-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .hafalan-page {
                padding: 1.5rem 1rem 3rem;
            }

            .filter-grid {
                grid-template-columns: repeat(6, 1fr);
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
        let currentHafalanId;
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

        function formatDate(dateStr) {
            if (!dateStr) return '<span class="no-audio">—</span>';
            const d = new Date(dateStr);
            if (isNaN(d)) return dateStr;
            return d.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }

        $(document).ready(function() {

            // Connect custom search box to DataTable
            $('#hafalan-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            function renderMobileCards(rows) {
                const container = $('#mobile-card-list');
                if (!rows.length) {
                    container.html('<div class="mobile-loading">Tidak ada data ditemukan</div>');
                    return;
                }

                const canAct = {{ auth()->user()->can('verify_hafalan') ? 'true' : 'false' }};
                const canDel = {{ auth()->user()->can('delete_hafalan') ? 'true' : 'false' }};

                let html = '';
                rows.forEach(function(row, idx) {
                    const color = getAvatarColor(row.user_name || '');
                    const initials = getInitials(row.user_name || '?');
                    const kelas = row.class_name || '';
                    const status = (row.status || '').toLowerCase();
                    const type = (row.type || '').toLowerCase();
                    const typeLabel = type === 'murajah' ? "Muraja'ah" : 'Setoran';
                    const typeCls = type === 'murajah' ? 'badge-murajah' : 'badge-setoran';
                    const statusMap = {
                        pending: 'badge-pending',
                        verified: 'badge-verified',
                        rejected: 'badge-rejected'
                    };
                    const statusLabel = {
                        pending: 'Pending',
                        verified: 'Verified',
                        rejected: 'Rejected'
                    };
                    const surahInfo = row.surah_info || '—';
                    const ayat = row.ayat_range ? `Ayat ${row.ayat_range}` : '';
                    const count = row.ayat_count ? `${row.ayat_count} ayat` : '';
                    const ayatMeta = [ayat, count].filter(Boolean).join(' · ');
                    const dateStr = row.hafalan_date ? formatDate(row.hafalan_date) : '—';

                    let actions = `<a href="/hafalan/${row.id}" class="action-btn view" title="Lihat Detail">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
            </svg></a>`;

                    if (canAct && status === 'pending') {
                        actions += `<button class="action-btn verify" onclick="verifyHafalan(${row.id})" title="Verifikasi">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M20 6L9 17l-5-5"/></svg></button>
            <button class="action-btn reject" onclick="rejectHafalan(${row.id})" title="Tolak">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M18 6L6 18M6 6l12 12"/></svg></button>`;
                    }

                    if (canDel) {
                        actions += `<button class="action-btn del" onclick="deleteHafalan(${row.id})" title="Hapus">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6m5 0V4h4v2"/></svg></button>`;
                    }

                    html += `
        <div class="hafalan-card-item">
            <div class="hafalan-card-top">
                <div class="hafalan-card-left">
                    <div class="avatar-sm" style="background:${color.bg};color:${color.text}">${initials}</div>
                    <div class="hafalan-card-info">
                        <div class="hafalan-card-name">${row.user_name || '—'}</div>
                        <div class="hafalan-card-class">${kelas}</div>
                    </div>
                </div>
                <div class="hafalan-card-badges">
                    <span class="badge ${statusMap[status] || 'badge-pending'}">${statusLabel[status] || status}</span>
                </div>
            </div>
            <div class="hafalan-card-meta">
                <div>
                    <div class="hafalan-card-surah">${surahInfo}</div>
                    ${ayatMeta ? `<div class="hafalan-card-ayat">${ayatMeta}</div>` : ''}
                </div>
                <span class="badge ${typeCls}">${typeLabel}</span>
            </div>
            <div class="hafalan-card-meta">
                <span class="hafalan-card-date">${dateStr}</span>
                <div class="action-group">${actions}</div>
            </div>
        </div>`;
                });

                container.html(html);
            }

            table = $('#hafalan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('hafalan.index') }}",
                    data: function(d) {
                        d.status = $('#filter-status').val();
                        d.type = $('#filter-type').val();
                        d.class_id = $('#filter-class').val();
                        d.juz_number = $('#filter-juz').val();
                        d.date_from = $('#filter-date-from').val();
                        d.date_to = $('#filter-date-to').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_name',
                        name: 'user.name'
                    },
                    {
                        data: 'surah_info',
                        name: 'surah_number'
                    },
                    {
                        data: 'juz_number',
                        name: 'juz_number'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'audio_path',
                        name: 'audio_path',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'hafalan_date',
                        name: 'hafalan_date'
                    },
                    {
                        data: 'verified_at',
                        name: 'verified_at',
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
                        render: (d, t, r, m) =>
                            `<span style="color:#9ca3af;font-size:0.75rem">${d}</span>`
                    },
                    {
                        targets: 1,
                        className: 'col-santri',
                        render: function(d, t, row) {
                            const color = getAvatarColor(d);
                            const initials = getInitials(d);
                            const kelas = row.class_name || '';
                            return `<div class="santri-cell">
                        <div class="avatar" style="background:${color.bg};color:${color.text}">${initials}</div>
                        <div>
                            <div class="santri-name">${d}</div>
                            <div class="santri-class">${kelas}</div>
                        </div>
                    </div>`;
                        }
                    },
                    {
                        targets: 2,
                        className: 'col-surah',
                        render: function(d, t, row) {
                            const ayat = row.ayat_range || '';
                            const count = row.ayat_count || '';
                            const meta = [ayat ? `Ayat ${ayat}` : '', count ? `${count} ayat` : '']
                                .filter(Boolean).join(' · ');
                            return `<div class="surah-name">${d}</div>${meta ? `<div class="surah-meta">${meta}</div>` : ''}`;
                        }
                    },
                    {
                        targets: 3,
                        className: 'col-juz text-center',
                        render: d => d ? `<div class="juz-num">${d}</div>` :
                            '<span class="no-audio">—</span>'
                    },
                    {
                        targets: 4,
                        className: 'col-jenis text-center',
                        render: d => {
                            const map = {
                                setoran: 'badge-setoran',
                                murajah: 'badge-murajah',
                                "muraja'ah": 'badge-murajah'
                            };
                            const cls = map[(d || '').toLowerCase()] || 'badge-setoran';
                            const label = d === 'murajah' ? "Muraja'ah" : 'Setoran';
                            return `<span class="badge ${cls}">${label}</span>`;
                        }
                    },
                    {
                        targets: 5,
                        className: 'col-status text-center',
                        render: d => {
                            const map = {
                                pending: 'badge-pending',
                                verified: 'badge-verified',
                                rejected: 'badge-rejected'
                            };
                            const cls = map[(d || '').toLowerCase()] || 'badge-pending';
                            const label = {
                                pending: 'Pending',
                                verified: 'Verified',
                                rejected: 'Rejected'
                            } [(d || '').toLowerCase()] || d;
                            return `<span class="badge ${cls}">${label}</span>`;
                        }
                    },
                    {
                        targets: 6,
                        className: 'col-audio text-center',
                        render: function(d, t, row) {
                            if (!d) return '<span class="no-audio">—</span>';
                            return `<button class="audio-btn" onclick="playAudio('${d}', this)" title="Putar audio">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
                            <path d="M15.54 8.46a5 5 0 010 7.07"/>
                        </svg>
                    </button>`;
                        }
                    },
                    {
                        targets: 7,
                        className: 'col-tanggal',
                        render: d => `<div class="date-text">${formatDate(d)}</div>`
                    },
                    {
                        targets: 8,
                        className: 'col-verifikasi',
                        render: function(d, t, row) {
                            if (!d) return '<span class="no-audio">—</span>';
                            const name = row.verified_by_name || 'Admin';
                            return `<div class="verified-by">${name}</div><div class="verified-date">${formatDate(d)}</div>`;
                        }
                    },
                    {
                        targets: 9,
                        className: 'col-aksi text-center',
                        render: function(d, t, row) {
                            const status = (row.status || '').toLowerCase();
                            const canAct =
                                {{ auth()->user()->can('verify_hafalan') ? 'true' : 'false' }};
                            const canDel =
                                {{ auth()->user()->can('delete_hafalan') ? 'true' : 'false' }};
                            const showUrl = `/hafalan/${d}`;

                            let html = `<div class="action-group">
                        <a href="${showUrl}" class="action-btn view" title="Lihat Detail">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </a>`;

                            if (canAct && status === 'pending') {
                                html += `<button class="action-btn verify" onclick="verifyHafalan(${d})" title="Verifikasi">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M20 6L9 17l-5-5"/>
                            </svg>
                        </button>
                        <button class="action-btn reject" onclick="rejectHafalan(${d})" title="Tolak">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M18 6L6 18M6 6l12 12"/>
                            </svg>
                        </button>`;
                            }

                            if (canDel) {
                                html += `<button class="action-btn del" onclick="deleteHafalan(${d})" title="Hapus">
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
                    [7, 'desc']
                ],
                pageLength: 25,
                dom: 'tp', // processing + table only — pagination handled custom
                language: {
                    processing: "Memuat data..."
                },
                drawCallback: function(settings) {
                    renderFooter(settings);
                    renderMobileCards(this.api().rows({
                        page: 'current'
                    }).data().toArray());
                }
            });

            // Render custom footer
            function renderFooter(settings) {
                const api = new $.fn.dataTable.Api(settings);
                const info = api.page.info();
                const start = info.start + 1;
                const end = Math.min(info.end, info.recordsDisplay);
                const total = info.recordsDisplay;
                const page = info.page;
                const pages = info.pages;

                let paginationHtml = '';

                // Prev button
                paginationHtml +=
                    `<button class="pg-btn ${page === 0 ? 'disabled' : ''}" onclick="goPage(${page - 1})">&lsaquo;</button>`;

                // Page numbers — show up to 5 around current
                let startPage = Math.max(0, page - 2);
                let endPage = Math.min(pages - 1, startPage + 4);
                startPage = Math.max(0, endPage - 4);

                for (let i = startPage; i <= endPage; i++) {
                    paginationHtml +=
                        `<button class="pg-btn ${i === page ? 'active' : ''}" onclick="goPage(${i})">${i + 1}</button>`;
                }

                // Next button
                paginationHtml +=
                    `<button class="pg-btn ${page >= pages - 1 ? 'disabled' : ''}" onclick="goPage(${page + 1})">&rsaquo;</button>`;

                const infoText = total === 0 ?
                    'Tidak ada data ditemukan' :
                    `Menampilkan ${start}–${end} dari ${total} data`;

                $('#hafalan-table').closest('.table-card').find('.table-footer-info').remove();
                $('#hafalan-table').closest('.table-card').append(`
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
            $('#filter-status, #filter-type, #filter-class, #filter-juz').val('');
            $('#filter-date-from, #filter-date-to').val('');
            table.ajax.reload();
        };

        // =====================
        // Audio Player
        // =====================
        let currentAudio = null;
        window.playAudio = function(path, btn) {
            if (currentAudio) {
                currentAudio.pause();
                document.querySelectorAll('.audio-btn.playing').forEach(b => b.classList.remove('playing'));
                if (currentAudio._btn === btn) {
                    currentAudio = null;
                    return;
                }
            }
            currentAudio = new Audio(path);
            currentAudio._btn = btn;
            btn.classList.add('playing');
            btn.innerHTML =
                `<svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>`;
            currentAudio.play();
            currentAudio.onended = function() {
                btn.classList.remove('playing');
                btn.innerHTML =
                    `<svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 010 7.07"/></svg>`;
                currentAudio = null;
            };
        };

        // =====================
        // Toast
        // =====================
        function showToast(message, type = 'success') {
            const toast = $('#toast');
            const icon = type === 'success' ? '✓' : '✕';
            toast.removeClass('hidden success error').addClass(type);
            $('#toast-icon').text(icon);
            $('#toast-message').text(message);
            toast.removeClass('hidden');
            setTimeout(() => toast.addClass('hidden'), 3500);
        }

        // =====================
        // Verify
        // =====================
        window.verifyHafalan = function(id) {
            currentHafalanId = id;
            $('#verify-notes').val('');
            $('#verify-modal').removeClass('hidden');
        };

        window.closeVerifyModal = function() {
            $('#verify-modal').addClass('hidden');
        };

        $('#verify-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: `/hafalan/${currentHafalanId}/verify`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    notes: $('#verify-notes').val()
                },
                success: function(response) {
                    closeVerifyModal();
                    table.ajax.reload(null, false);
                    showToast(response.message || 'Hafalan berhasil diverifikasi', 'success');
                },
                error: function(xhr) {
                    showToast(xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                }
            });
        });

        // =====================
        // Reject
        // =====================
        window.rejectHafalan = function(id) {
            currentHafalanId = id;
            $('#reject-reason').val('');
            $('#reject-modal').removeClass('hidden');
        };

        window.closeRejectModal = function() {
            $('#reject-modal').addClass('hidden');
        };

        $('#reject-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: `/hafalan/${currentHafalanId}/reject`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    reason: $('#reject-reason').val()
                },
                success: function(response) {
                    closeRejectModal();
                    table.ajax.reload(null, false);
                    showToast(response.message || 'Hafalan berhasil ditolak', 'success');
                },
                error: function(xhr) {
                    showToast(xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                }
            });
        });

        // =====================
        // Delete
        // =====================
        window.deleteHafalan = function(id) {
            currentHafalanId = id;
            $('#delete-modal').removeClass('hidden');
        };

        window.closeDeleteModal = function() {
            $('#delete-modal').addClass('hidden');
        };

        window.confirmDelete = function() {
            $.ajax({
                url: `/hafalan/${currentHafalanId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    closeDeleteModal();
                    table.ajax.reload(null, false);
                    showToast(response.message || 'Hafalan berhasil dihapus', 'success');
                },
                error: function(xhr) {
                    closeDeleteModal();
                    showToast(xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                }
            });
        };

        // Close modals on overlay click
        $('.modal-overlay').on('click', function(e) {
            if (e.target === this) {
                $(this).addClass('hidden');
            }
        });
    </script>
@endpush
