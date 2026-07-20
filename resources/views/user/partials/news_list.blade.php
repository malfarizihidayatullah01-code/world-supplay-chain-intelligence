@if($news->isEmpty())
    <div class="empty-state text-center py-5 bg-white shadow-sm border" style="border-radius: 18px; border-color: #E5E7EB !important;">
        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-4" style="width: 80px; height: 80px;">
            <i class="bi bi-journal-x text-muted" style="font-size: 2.5rem;"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">{{ __('No news found') }}</h5>
        <p class="text-muted small mb-0">{{ __('Try another keyword or category.') }}</p>
    </div>
@else
    <div class="news-feed-container d-flex flex-column gap-4">
        @foreach($news as $index => $item)
            @php
                $imgUrl = 'https://picsum.photos/seed/'.$item->id.'/800/400';
                if($item->category === 'Shipping') $imgUrl = 'https://images.unsplash.com/photo-1494412685616-a5d310fbb07d?w=800&h=400&fit=crop';
                if($item->category === 'Logistics') $imgUrl = 'https://images.unsplash.com/photo-1586528116311-ad8ed7c663c0?w=800&h=400&fit=crop';
                if($item->category === 'Trade') $imgUrl = 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=800&h=400&fit=crop';
                if($item->category === 'Economy') $imgUrl = 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&h=400&fit=crop';
                
                // Modern soft color badges
                $catStyles = 'background-color: #F3F4F6; color: #374151;';
                if($item->category === 'Trade') $catStyles = 'background-color: #ECFDF5; color: #15803D;';
                if($item->category === 'Shipping') $catStyles = 'background-color: #EEF2FF; color: #4338CA;';
                if($item->category === 'Logistics') $catStyles = 'background-color: #FEF2F2; color: #DC2626;';
                if($item->category === 'Economy') $catStyles = 'background-color: #FFF7ED; color: #EA580C;';
            @endphp

            @if($index === 0)
                <!-- Headline Hero Section -->
                <div class="card border bg-white p-3 news-card-anim hero-card" style="border-color: #E5E7EB !important; border-radius: 18px;">
                    <div class="d-flex flex-column flex-md-row gap-4 align-items-md-center">
                        <div class="flex-shrink-0 mx-auto mx-md-0 position-relative overflow-hidden" style="width: 250px; height: 170px; border-radius: 16px;">
                            <img src="{{ $imgUrl }}" class="w-100 h-100 object-fit-cover hero-img" alt="Headline">
                        </div>
                        <div class="flex-grow-1 d-flex flex-column justify-content-center px-1">
                            <div class="d-flex align-items-center mb-2 gap-3">
                                <span class="badge rounded-pill px-3 py-1 fw-bold border-0" style="font-size: 0.75rem; {{ $catStyles }}">{{ __($item->category) }}</span>
                                <small class="text-muted fw-semibold" style="font-size: 0.8rem;"><i class="bi bi-clock me-1"></i>{{ $item->published_at ? $item->published_at->diffForHumans() : 'Recently' }}</small>
                                @if(isset($item->source_name) && $item->source_name)
                                    <small class="text-muted fw-medium" style="font-size: 0.8rem;"><i class="bi bi-building me-1"></i>{{ $item->source_name }}</small>
                                @endif
                            </div>
                            <h4 class="card-title fw-bold text-dark lh-sm mb-2" style="font-weight: 700;">
                                {{ $item->title }}
                            </h4>
                            <p class="card-text mb-3" style="font-size: 0.95rem; color: #6B7280; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ Str::limit($item->summary ?? $item->title, 180) }}
                            </p>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="text-muted small fw-medium">
                                    <i class="bi bi-geo-alt-fill text-primary me-1"></i>{{ $item->country ? $item->country->name : 'Global' }}
                                </span>
                                <a href="{{ $item->url }}" target="_blank" class="text-primary fw-bold text-decoration-none view-article-link">
                                    {{ __('Read More') }} <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Standard Horizontal List Item -->
                <div class="card border bg-white p-3 news-card-anim" style="border-color: #E5E7EB !important; border-radius: 18px;">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                        <div class="flex-shrink-0 mx-auto mx-sm-0 position-relative overflow-hidden" style="width: 140px; height: 125px; border-radius: 12px;">
                            <img src="{{ $imgUrl }}" class="w-100 h-100 object-fit-cover feed-img" alt="Thumbnail">
                        </div>
                        <div class="flex-grow-1 d-flex flex-column justify-content-center px-1">
                            <div class="d-flex flex-wrap align-items-center mb-2 gap-3">
                                <span class="badge rounded-pill px-3 py-1 fw-bold border-0" style="font-size: 0.7rem; {{ $catStyles }}">{{ __($item->category) }}</span>
                                <small class="text-muted fw-semibold" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i>{{ $item->published_at ? $item->published_at->diffForHumans() : 'Recently' }}</small>
                                @if(isset($item->source_name) && $item->source_name)
                                    <small class="text-muted fw-medium" style="font-size: 0.75rem;"><i class="bi bi-building me-1"></i>{{ $item->source_name }}</small>
                                @endif
                            </div>
                            <h6 class="card-title text-dark lh-sm mb-1" style="font-weight: 600; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $item->title }}
                            </h6>
                            <p class="card-text mb-2" style="font-size: 0.85rem; color: #6B7280; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ Str::limit($item->summary ?? $item->title, 120) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted fw-medium" style="font-size: 0.75rem;">
                                    <i class="bi bi-geo-alt-fill text-primary me-1"></i>{{ $item->country ? $item->country->name : 'Global' }}
                                </span>
                                <a href="{{ $item->url }}" target="_blank" class="text-primary fw-bold text-decoration-none view-article-link" style="font-size: 0.85rem;">
                                    {{ __('View Article') }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    
    <div class="mt-5 d-flex justify-content-center">
        {{ $news->links('pagination::bootstrap-5') }}
    </div>

    <style>
        .news-card-anim {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .hero-card {
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        }
        .news-card-anim:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 10px 20px rgba(0,0,0,0.06); 
            border-color: rgba(37, 99, 235, 0.2) !important; 
        }
        .hero-img, .feed-img {
            transition: transform 0.3s ease;
        }
        .news-card-anim:hover .hero-img, .news-card-anim:hover .feed-img {
            transform: scale(1.05);
        }
        .view-article-link {
            transition: opacity 0.2s;
        }
        .view-article-link:hover {
            opacity: 0.8;
            text-decoration: underline !important;
        }
    </style>
@endif
