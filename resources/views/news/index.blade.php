@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Global Supply Chain News</h2>
        <form action="{{ route('news.sync') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-repeat"></i> Sync News API</button>
        </form>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Country</th>
                            <th>Headline</th>
                            <th>Source</th>
                            <th>Published At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($newsArticles as $index => $news)
                        <tr>
                            <td class="ps-4">{{ $newsArticles->firstItem() + $index }}</td>
                            <td class="fw-bold">{{ $news->country->country_name ?? '-' }}</td>
                            <td>
                                <strong>{{ $news->title }}</strong>
                                <p class="text-muted small mb-0">{{ Str::limit($news->summary, 80) }}</p>
                            </td>
                            <td><span class="badge bg-secondary">{{ $news->source }}</span></td>
                            <td><small class="text-muted">{{ \Carbon\Carbon::parse($news->published_at)->format('d M Y, H:i') }}</small></td>
                            <td>
                                <a href="{{ $news->url }}" target="_blank" class="btn btn-sm btn-outline-primary">Read More</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No news articles found. Please sync the API.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white border-top p-4" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            {{ $newsArticles->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
