@extends('client.layouts.app')

@section('title', 'Dashboard')
@section('meta_description', 'Client dashboard for restaurant owners')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Client</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Restaurants</span>
                            <h4 class="mb-0">{{ number_format($stats['restaurants']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle font-size-24">
                                <i class="bx bx-store"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Approved</span>
                            <h4 class="mb-0">{{ number_format($stats['approved_restaurants']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="avatar-title bg-success-subtle text-success rounded-circle font-size-24">
                                <i class="bx bx-check-circle"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Pending Review</span>
                            <h4 class="mb-0">{{ number_format($stats['pending_restaurants']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="avatar-title bg-warning-subtle text-warning rounded-circle font-size-24">
                                <i class="bx bx-time-five"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Gallery Images</span>
                            <h4 class="mb-0">{{ number_format($stats['gallery_images']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="avatar-title bg-info-subtle text-info rounded-circle font-size-24">
                                <i class="bx bx-image"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="card-title mb-0">Recent Restaurants</h5>
                    </div>

                    @if ($recentRestaurants->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-nowrap align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>City</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentRestaurants as $restaurant)
                                        <tr>
                                            <td>
                                                <h6 class="mb-0">{{ $restaurant->name }}</h6>
                                                <span class="text-muted font-size-13">{{ $restaurant->email ?? 'No email added' }}</span>
                                            </td>
                                            <td>{{ $restaurant->category?->name ?? 'Not assigned' }}</td>
                                            <td>{{ $restaurant->city?->name ?? 'Not assigned' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $restaurant->status === 'approved' ? 'success' : 'warning' }}-subtle text-{{ $restaurant->status === 'approved' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($restaurant->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="avatar-md mx-auto mb-3">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-24">
                                    <i class="bx bx-store"></i>
                                </span>
                            </div>
                            <h5>No restaurants yet</h5>
                            <p class="text-muted mb-0">Restaurant records will appear here once they are created.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Quick Actions</h5>

                    <div class="d-grid gap-2">
                        <a href="javascript:void(0);" class="btn btn-primary">
                            <i class="bx bx-plus me-1"></i> Add Restaurant
                        </a>
                        <a href="javascript:void(0);" class="btn btn-light">
                            <i class="bx bx-image-add me-1"></i> Upload Gallery Image
                        </a>
                        <a href="{{ route('website.home') }}" class="btn btn-light">
                            <i class="bx bx-world me-1"></i> View Public Website
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
