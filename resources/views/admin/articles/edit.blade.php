@extends('layouts.admin')

@push('styles')
<style>
    .admin-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        background-color: #fff;
    }
    .form-label {
        font-weight: 700;
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .form-control, .form-select {
        border-radius: 8px;
        padding: 12px 16px;
        border: 1px solid #cbd5e1;
        background-color: #ffffff;
        font-size: 0.95rem;
        color: #334155;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02) inset;
    }
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        outline: none;
    }
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        cursor: pointer;
    }
    .btn-navy {
        background-color: #3E53A0;
        color: white;
        border: none;
        font-weight: 600;
        padding: 10px 24px;
    }
    .btn-navy:hover {
        background-color: #2c3e80;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(62, 83, 160, 0.2);
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" style="max-width: 900px;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">{{ __('Edit Article') }}</h4>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">{{ __('Update article content and information') }}</p>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-light border" style="border-radius: 8px; font-size: 0.85rem;">
            <i class="bi bi-arrow-left me-1"></i> {{ __('Back to List') }}
        </a>
    </div>

    <!-- Form Card -->
    <div class="admin-card card p-4">
        @if($errors->any())
            <div class="alert alert-danger" style="border-radius: 8px;">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.articles.update', $article->id) }}">
            @csrf
            @method('PUT')
            
            <div class="row g-4 mb-4">
                <div class="col-md-12">
                    <label class="form-label">{{ __('Article Title') }}</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $article->title) }}" placeholder="{{ __('Example: Impact of Red Sea Crisis...') }}" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">{{ __('Category') }}</label>
                    <input type="text" name="category" class="form-control" value="{{ old('category', $article->category) }}" placeholder="{{ __('Example: Shipping, Economy, Weather') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('Cover Image URL (Optional)') }}</label>
                    <input type="url" name="image" class="form-control" value="{{ old('image', $article->image) }}" placeholder="https://contoh.com/gambar.jpg">
                </div>

                <div class="col-md-12">
                    <label class="form-label">{{ __('Tags (Comma separated)') }}</label>
                    <input type="text" name="tags" class="form-control" value="{{ old('tags', implode(', ', $article->tags ?? [])) }}" placeholder="{{ __('Example: Red Sea, Logistics, Crisis') }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label">{{ __('Article Content') }}</label>
                    <textarea name="content" class="form-control" rows="8" placeholder="{{ __('Write your analysis here...') }}" required>{{ old('content', $article->content) }}</textarea>
                </div>

                <div class="col-md-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $article->is_published) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="is_published">{{ __('Publish Article Immediately') }}</label>
                    </div>
                    <div class="form-text" style="font-size: 0.75rem;">{{ __('If unchecked, the article will be saved as Draft.') }}</div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('admin.articles.index') }}" class="btn btn-light border px-4" style="border-radius: 8px;">{{ __('Cancel') }}</a>
                <button type="submit" class="btn btn-navy px-4" style="border-radius: 8px;">
                    <i class="bi bi-save me-1"></i> {{ __('Save Article') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
