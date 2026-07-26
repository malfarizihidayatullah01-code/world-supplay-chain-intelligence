@extends('layouts.admin')

@push('styles')
<style>
    .admin-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        background-color: #fff;
    }
    .kpi-card {
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .kpi-icon.primary { background: rgba(28, 85, 255, 0.1); color: #1C55FF; }
    .kpi-icon.success { background: rgba(16, 185, 129, 0.1); color: #10B981; }
    .kpi-icon.warning { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
    .kpi-icon.info { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }
    
    .table-container {
        overflow-x: auto;
    }
    .table {
        margin-bottom: 0;
        vertical-align: middle;
    }
    .table th {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #6B7280;
        font-weight: 600;
        border-bottom: 1px solid #E5E7EB;
        padding: 12px 16px;
    }
    .table td {
        padding: 12px 16px;
        font-size: 0.85rem;
        border-bottom: 1px solid #F3F4F6;
    }
    .table tbody tr {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .table tbody tr:hover {
        background-color: #F9FAFB;
    }
    .table tbody tr.active-row {
        background-color: #EFF6FF;
    }
    
    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .status-published { background: #D1FAE5; color: #059669; }
    .status-draft { background: #FEF3C7; color: #D97706; }
    
    .category-badge {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        background: #EEF2FF;
        color: #4F46E5;
    }
    
    .btn-navy {
        background-color: #3E53A0;
        color: white;
        border: none;
    }
    .btn-navy:hover {
        background-color: #2c3e80;
        color: white;
    }
    
    /* Side Panel Styles */
    #articleDetailPanel {
        transition: opacity 0.3s ease;
        opacity: 0.5;
        pointer-events: none;
    }
    #articleDetailPanel.active {
        opacity: 1;
        pointer-events: auto;
    }
    .article-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 15px;
    }
    
    .article-thumbnail {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 6px;
    }
    
    .tag-badge {
        border: 1px solid #E5E7EB;
        color: #4B5563;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.7rem;
        margin-right: 5px;
        margin-bottom: 5px;
        display: inline-block;
    }
</style>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">Analysis Articles</h4>
            <div class="d-flex align-items-center gap-3 mt-2">
                <p class="text-muted mb-0" style="font-size: 0.9rem;">{{ __('Manage analysis articles and insights related to global supply chain risks') }}</p>
                <div class="d-flex align-items-center gap-3 bg-white border rounded-pill px-3 py-1 shadow-sm">
                    <div class="d-flex align-items-center gap-1">
                        <i class="bi bi-file-text" style="color: #3E53A0;"></i>
                        <span class="fw-bold text-dark" style="font-size: 0.8rem;">{{ $totalArticles }}</span>
                        <span class="text-muted" style="font-size: 0.8rem;">{{ __('Total') }}</span>
                    </div>
                    <div class="vr" style="opacity: 0.2; height: 12px; align-self: center;"></div>
                    <div class="d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                        <i class="bi bi-check-circle" style="color: #10B981;"></i>
                        <span class="fw-bold text-success">{{ $publishedArticles }}</span>
                        <span class="text-muted" style="font-size: 0.8rem;">{{ __('Published') }}</span>
                    </div>
                    <div class="vr" style="opacity: 0.2; height: 12px; align-self: center;"></div>
                    <div class="d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                        <i class="bi bi-file-earmark-text" style="color: #F59E0B;"></i>
                        <span class="fw-bold text-warning">{{ $draftArticles }}</span>
                        <span class="text-muted" style="font-size: 0.8rem;">{{ __('Draft') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="admin-card card p-3 mb-4">
        <div class="d-flex flex-wrap gap-3 align-items-end justify-content-between">
            <form method="GET" action="{{ route('admin.articles.index') }}" class="d-flex flex-wrap gap-3 align-items-end">
                <div class="search-box">
                    <input type="text" name="search" class="form-control rounded-pill px-3" style="min-width: 250px;" placeholder="{{ __('Search articles...') }}" value="{{ request('search') }}">
                </div>
                
                <div class="d-flex flex-column">
                    <label style="font-size:0.7rem; color:#888; font-weight:600; margin-bottom:5px; padding-left:10px;">{{ __('Category') }}</label>
                    <select name="category" class="form-select rounded-pill px-3" style="min-width: 180px;">
                        <option value="all">{{ __('All Categories') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->category }}" {{ request('category') == $cat->category ? 'selected' : '' }}>{{ $cat->category }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="d-flex flex-column">
                    <label style="font-size:0.7rem; color:#888; font-weight:600; margin-bottom:5px; padding-left:10px;">{{ __('Status') }}</label>
                    <select name="status" class="form-select rounded-pill px-3" style="min-width: 180px;">
                        <option value="all">{{ __('All Status') }}</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>{{ __('Published') }}</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-navy rounded-pill px-4">
                    <i class="bi bi-funnel me-1"></i> {{ __('Filter') }}
                </button>
            </form>

            <a href="{{ route('admin.articles.create') }}" class="btn btn-navy rounded-pill px-4" style="height: 38px; display: flex; align-items: center;">
                <i class="bi bi-plus-lg me-2"></i> {{ __('New Article') }}
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Table Column -->
        <div class="col-lg-12">
            <div class="admin-card card h-100">
                <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">{{ __('Article List') }}</h6>
                </div>
                <div class="card-body p-0 table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width: 5%;">{{ __('No.') }}</th>
                                <th style="width: 35%;">{{ __('Article') }}</th>
                                <th style="width: 15%;">{{ __('Category') }}</th>
                                <th style="width: 15%;">{{ __('Author') }}</th>
                                <th style="width: 10%;">{{ __('Date') }}</th>
                                <th style="width: 10%;">{{ __('Status') }}</th>
                                <th style="width: 10%;" class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($articles as $index => $article)
                            <tr>
                                <td class="ps-4">{{ $articles->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($article->image)
                                            <img src="{{ $article->image }}" class="article-thumbnail">
                                        @else
                                            <div class="article-thumbnail bg-light d-flex align-items-center justify-content-center text-muted">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                        <div class="text-dark fw-medium" style="font-size:0.85rem; line-height: 1.3;">
                                            {{ \Illuminate\Support\Str::limit($article->title, 50) }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="category-badge">{{ $article->category ?? 'General' }}</span>
                                </td>
                                <td>{{ $article->admin->name ?? 'Admin' }}</td>
                                <td>
                                    <div style="font-size:0.8rem; color:#555;">{{ $article->created_at->format('d M Y') }}</div>
                                    <div style="font-size:0.75rem; color:#888;">{{ $article->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td>
                                    @if($article->is_published)
                                        <span class="status-badge status-published">{{ __('Published') }}</span>
                                    @else
                                        <span class="status-badge status-draft">{{ __('Draft') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center align-items-center">
                                        <a href="{{ route('admin.articles.show', $article->id) }}" class="btn btn-sm btn-outline-info d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; padding: 0;" title="{{ __('View') }}">
                                            <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                                        </a>
                                        <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; padding: 0;" title="{{ __('Edit') }}">
                                            <i data-lucide="edit-2" style="width: 16px; height: 16px;"></i>
                                        </a>
                                        <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="d-inline m-0 p-0" onsubmit="return confirm('{{ __('Are you sure you want to delete this article?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; padding: 0;" title="{{ __('Delete') }}">
                                                <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-file-earmark-x fs-1 d-block mb-2"></i>
                                    {{ __('No articles found.') }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($articles->hasPages())
                <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
                    <div class="text-muted" style="font-size: 0.8rem;">
                        {{ __('Showing') }} {{ $articles->firstItem() }} - {{ $articles->lastItem() }} {{ __('to') }} {{ $articles->total() }} {{ __('articles') }}
                    </div>
                    <div>
                        {{ $articles->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        </div>
    </div>
</div>
@endsection
