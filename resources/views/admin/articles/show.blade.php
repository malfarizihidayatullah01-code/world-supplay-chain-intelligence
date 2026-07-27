@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary" style="border-radius: 8px;">
                    <i class="bi bi-arrow-left me-1"></i> Back to List
                </a>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-primary" style="border-radius: 8px;">
                        <i class="bi bi-pencil me-1"></i> Edit Artikel
                    </a>
                </div>
            </div>

            <div class="admin-card card p-4 p-md-5">
                <h6 class="fw-bold mb-4 text-muted border-bottom pb-2">{{ __('Article Detail') }}</h6>
                
                @if($article->image)
                    <img src="{{ $article->image }}" class="w-100 rounded mb-4" alt="Cover Image" style="max-height: 400px; object-fit: cover;">
                @else
                    <div class="w-100 rounded mb-4 bg-light d-flex align-items-center justify-content-center text-muted" style="height: 300px;">
                        <i class="bi bi-image" style="font-size: 4rem;"></i>
                    </div>
                @endif

                <div class="mb-3">
                    @if($article->is_published)
                        <span class="status-badge status-published">{{ __('Published') }}</span>
                    @else
                        <span class="status-badge status-draft">{{ __('Draft') }}</span>
                    @endif
                </div>

                <h2 class="fw-bold text-dark mb-3" style="line-height:1.4;">{{ $article->title }}</h2>
                
                <div class="d-flex gap-4 mb-4 text-muted border-bottom pb-3" style="font-size: 0.9rem;">
                    <div><i class="bi bi-person me-1"></i> {{ $article->admin->name ?? 'Admin' }}</div>
                    <div><i class="bi bi-calendar3 me-1"></i> {{ $article->created_at->format('d M Y, H:i') }} WIB</div>
                </div>

                <div class="mb-4" style="line-height: 1.8; font-size: 1rem; color: #444;">
                    {!! nl2br(e($article->content)) !!}
                </div>

                <div class="mb-4">
                    <div class="text-dark fw-bold mb-2" style="font-size:0.9rem;">{{ __('Category') }}</div>
                    <span class="category-badge px-3 py-2">{{ $article->category ?? 'General' }}</span>
                </div>

                <div class="mb-4">
                    <div class="text-dark fw-bold mb-2" style="font-size:0.9rem;">Tags</div>
                    <div class="d-flex flex-wrap gap-2">
                        @if($article->tags && is_array($article->tags) && count($article->tags) > 0)
                            @foreach($article->tags as $tag)
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">{{ $tag }}</span>
                            @endforeach
                        @else
                            <span class="text-muted" style="font-size:0.9rem;">{{ __('No tags') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
