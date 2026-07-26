@extends('layouts.admin')

@push('styles')
<style>
    /* SaaS Modern CSS */
    .saas-stats-strip {
        display: flex;
        gap: 24px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 32px;
        border-bottom: 1px solid #F1F5F9;
        padding-bottom: 16px;
    }
    .saas-stat-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        color: #475569;
        font-weight: 500;
    }
    .saas-stat-value {
        font-weight: 700;
        color: #0F172A;
    }
    .saas-stat-badge {
        padding: 4px 10px;
        border-radius: 99px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Toolbar */
    .saas-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .saas-search {
        position: relative;
        flex-grow: 1;
        max-width: 400px;
    }
    .saas-search input {
        width: 100%;
        padding: 12px 16px 12px 42px;
        border-radius: 16px;
        border: 1px solid #E2E8F0;
        background: #FFFFFF;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .saas-search input:focus {
        border-color: #2962FF;
        outline: none;
        box-shadow: 0 0 0 4px rgba(41, 98, 255, 0.1);
    }
    .saas-search i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94A3B8;
    }
    .saas-filters {
        display: flex;
        gap: 12px;
        align-items: center;
    }
    .saas-filters select {
        padding: 10px 16px;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        background: #FFFFFF;
        font-size: 0.85rem;
        color: #475569;
        font-weight: 500;
        cursor: pointer;
    }
    .saas-btn-filter {
        padding: 10px 20px;
        border-radius: 12px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        color: #0F172A;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    .saas-btn-filter:hover {
        background: #E2E8F0;
    }
    .saas-btn-add {
        padding: 12px 24px;
        border-radius: 14px;
        background: #2962FF;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
        box-shadow: 0 4px 12px rgba(41, 98, 255, 0.2);
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .saas-btn-add:hover {
        background: #1E4BD8;
        transform: translateY(-2px);
        color: white;
    }

    /* Table */
    .saas-table-container {
        background: transparent;
    }
    .saas-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }
    .saas-table th {
        font-size: 0.75rem;
        font-weight: 600;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0 24px 12px 24px;
        border: none;
    }
    .saas-table td {
        padding: 16px 24px;
        background: #FFFFFF;
        vertical-align: middle;
        font-size: 0.85rem;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s ease;
        border-top: 1px solid transparent;
        border-bottom: 1px solid transparent;
    }
    .saas-table tr td:first-child {
        border-top-left-radius: 16px;
        border-bottom-left-radius: 16px;
        border-left: 1px solid transparent;
    }
    .saas-table tr td:last-child {
        border-top-right-radius: 16px;
        border-bottom-right-radius: 16px;
        border-right: 1px solid transparent;
    }
    .saas-table tbody tr {
        transition: all 0.2s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.01);
    }
    .saas-table tbody tr:hover td {
        background: #F8FAFC;
    }
    .saas-table tbody tr.active-row td {
        background: #EFF6FF;
        border-color: rgba(41, 98, 255, 0.1);
    }

    /* Badges & Avatars */
    .saas-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        background: #F1F5F9;
        color: #0F172A;
    }
    .saas-badge-admin {
        background: rgba(139, 92, 246, 0.1);
        color: #8B5CF6;
    }
    .saas-badge-user {
        background: rgba(15, 23, 42, 0.05);
        color: #475569;
    }
    .saas-badge-active {
        background: rgba(34, 197, 94, 0.1);
        color: #22C55E;
    }
    .saas-badge-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    /* Actions */
    .saas-action-btn {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        color: #94A3B8;
        border: none;
        transition: all 0.2s;
    }
    .saas-action-btn:hover {
        background: #F1F5F9;
        color: #0F172A;
    }
    .saas-action-danger:hover {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    /* User Detail Panel */
    #userDetailPanel {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: 24px;
        padding: 32px;
        box-shadow: 0 10px 40px rgba(15, 23, 42, 0.05);
        transition: opacity 0.3s ease, transform 0.3s ease;
        position: sticky;
        top: 24px;
    }
    .detail-avatar {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0 auto 20px auto;
        background: #F8FAFC;
        color: #102A43;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .detail-section {
        margin-top: 24px;
    }
    .detail-section-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 12px;
    }
    .detail-info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #F1F5F9;
        font-size: 0.85rem;
    }
    .detail-info-row:last-child {
        border-bottom: none;
    }
    .detail-info-label {
        color: #64748B;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .detail-info-value {
        font-weight: 600;
        color: #0F172A;
    }
    
    .permission-item {
        font-size: 0.85rem;
        color: #475569;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .permission-item i {
        color: #22C55E;
        font-size: 1rem;
    }
    
    .saas-btn-full {
        width: 100%;
        padding: 14px;
        border-radius: 16px;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .saas-btn-edit {
        background: #F1F5F9;
        color: #0F172A;
        border: 1px solid transparent;
    }
    .saas-btn-edit:hover {
        background: #E2E8F0;
        color: #0F172A;
    }
    .saas-btn-delete {
        background: transparent;
        color: #EF4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }
    .saas-btn-delete:hover {
        background: rgba(239, 68, 68, 0.05);
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 px-xl-5 fade-in-up">
    
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h2 class="fw-bold text-dark mb-1">{{ __('Users') }}</h2>
            <p class="text-secondary mb-0" style="font-size: 1rem;">Manage team members and access control.</p>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}" class="saas-btn-add">
                <i data-lucide="plus" style="width: 18px;"></i> Add User
            </a>
        </div>
    </div>



    <!-- TOOLBAR -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="saas-toolbar">
        <div class="saas-search">
            <i data-lucide="search" style="width: 18px;"></i>
            <input type="text" name="search" placeholder="Search by name or email..." value="{{ request('search') }}">
        </div>
        <div class="saas-filters">
            <select name="role">
                <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>All Roles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
            </select>
            <select name="status">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="saas-btn-filter"><i data-lucide="filter" style="width: 16px; margin-right: 6px; margin-bottom: 2px;"></i> Filter</button>
        </div>
    </form>

    <!-- MAIN FULL WIDTH TABLE -->
    <div class="row g-5">
        <!-- 100% Table -->
        <div class="col-12">
            <div class="saas-table-container">
                <table class="saas-table" id="usersTable">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="saas-avatar">
                                        @php
                                            $words = explode(' ', trim($user->name));
                                            $initials = count($words) > 1 
                                                ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
                                                : strtoupper(substr($words[0], 0, 2));
                                        @endphp
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $user->name }}</div>
                                        <div class="text-muted d-flex align-items-center gap-2" style="font-size: 0.8rem;">
                                            <span>{{ $user->email }}</span>
                                            @if($user->country)
                                            <span style="color: #CBD5E1;">&bull;</span>
                                            <span class="d-flex align-items-center gap-1">
                                                <img src="https://flagcdn.com/w20/{{ strtolower($user->country->iso2_code) }}.png" alt="{{ $user->country->name }}" style="width:14px; border-radius:2px;">
                                                {{ $user->country->name }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $user->role == 'admin' ? 'saas-badge-admin' : 'saas-badge-user' }} rounded-pill px-3 py-1 text-capitalize">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle {{ $user->status == 'Active' ? 'bg-success' : 'bg-danger' }}" style="width: 8px; height: 8px;"></div>
                                    <span class="fw-semibold {{ $user->status == 'Active' ? 'text-success' : 'text-danger' }}" style="font-size: 0.8rem;">{{ $user->status }}</span>
                                </div>
                            </td>
                            <td>
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="saas-action-btn" title="Edit">
                                    <i data-lucide="edit-2" style="width: 16px;"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="saas-action-btn saas-action-danger" title="Delete">
                                        <i data-lucide="trash-2" style="width: 16px;"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted bg-white" style="border-radius: 16px;">
                                <i data-lucide="users" style="width: 48px; height: 48px; opacity: 0.2; margin-bottom: 12px;"></i>
                                <div class="fw-semibold">No users found.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection