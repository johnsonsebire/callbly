<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>{{ $profile->company_name ?? 'Company Profile' }}</title>
    <meta name="description" content="{{ $profile->description ?? 'Schedule a meeting with us' }}" />
    <meta name="keywords" content="meeting, scheduling, {{ $profile->company_name ?? '' }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $profile->company_name ?? 'Company Profile' }}" />
    <meta property="og:description" content="{{ $profile->description ?? 'Schedule a meeting with us' }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ $profile->company_name ?? 'Callbly Meeting Scheduler' }}" />
    <link rel="canonical" href="{{ url()->current() }}" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: {{ $profile->primary_color ?? '#3B82F6' }};
            --primary-rgb: {{ implode(',', sscanf($profile->primary_color ?? '#3B82F6', "#%02x%02x%02x")) }};
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        
        .logo-text {
            font-size: 20px;
            font-weight: bold;
            color: white;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: rgba(var(--primary-rgb), 0.9);
            border-color: rgba(var(--primary-rgb), 0.9);
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .border-primary {
            border-color: var(--primary-color) !important;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, rgba(var(--primary-rgb), 0.8) 100%);
            color: white;
            padding: 80px 0;
        }
        
        .event-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }
        
        .social-link:hover {
            background-color: rgba(var(--primary-rgb), 0.8);
            color: white;
            transform: translateY(-2px);
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background-color: rgba(var(--primary-rgb), 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                @if($profile->logo)
                    <img src="{{ Storage::url($profile->logo) }}" alt="{{ $profile->company_name }}" height="40" class="me-2">
                @endif
                <span style="font-size: 20px; font-weight: bold; color: var(--primary-color);">{{ $profile->company_name ?? 'Company' }}</span>
            </a>
            <div class="navbar-nav ms-auto">
                @if($profile->website)
                    <a class="nav-link" href="{{ $profile->website }}" target="_blank">
                        <i class="fas fa-external-link-alt me-1"></i>Website
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4">{{ $profile->company_name ?? 'Welcome' }}</h1>
                    @if($profile->description)
                        <p class="lead mb-4">{{ $profile->description }}</p>
                    @endif
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        @if($profile->email)
                            <div class="d-flex align-items-center">
                                <i class="fas fa-envelope me-2"></i>
                                <span>{{ $profile->email }}</span>
                            </div>
                        @endif
                        @if($profile->phone)
                            <div class="d-flex align-items-center">
                                <i class="fas fa-phone me-2"></i>
                                <span>{{ $profile->phone }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Social Links -->
                    @if($profile->linkedin_url || $profile->twitter_url || $profile->facebook_url || $profile->instagram_url)
                        <div class="mb-4">
                            @if($profile->linkedin_url)
                                <a href="{{ $profile->linkedin_url }}" target="_blank" class="social-link">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            @endif
                            @if($profile->twitter_url)
                                <a href="{{ $profile->twitter_url }}" target="_blank" class="social-link">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            @endif
                            @if($profile->facebook_url)
                                <a href="{{ $profile->facebook_url }}" target="_blank" class="social-link">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            @endif
                            @if($profile->instagram_url)
                                <a href="{{ $profile->instagram_url }}" target="_blank" class="social-link">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="col-lg-4 text-center">
                    @if($profile->logo)
                        <img src="{{ Storage::url($profile->logo) }}" alt="{{ $profile->company_name }}" 
                             class="img-fluid rounded-circle border border-white border-4" 
                             style="max-width: 200px; max-height: 200px; object-fit: cover;">
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Event Types Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Schedule a Meeting</h2>
                <p class="text-muted">Choose the type of meeting that works best for you</p>
            </div>

            @if($eventTypes && count($eventTypes) > 0)
                <div class="row g-4">
                    @foreach($eventTypes as $eventType)
                        <div class="col-md-6 col-lg-4">
                            <div class="card event-card h-100">
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-3">
                                        <div class="feature-icon mx-auto" style="background-color: {{ $eventType->color }}20;">
                                            <i class="fas fa-calendar" style="color: {{ $eventType->color }}; font-size: 24px;"></i>
                                        </div>
                                    </div>
                                    <h5 class="card-title text-center mb-3">{{ $eventType->name }}</h5>
                                    @if($eventType->description)
                                        <p class="card-text text-muted text-center mb-4">{{ $eventType->description }}</p>
                                    @endif
                                    
                                    <div class="d-flex justify-content-center gap-3 mb-4 text-sm">
                                        <span class="text-muted">
                                            <i class="fas fa-clock me-1"></i>{{ $eventType->duration }} min
                                        </span>
                                        <span class="text-muted">
                                            <i class="fas fa-video me-1"></i>Google Meet
                                        </span>
                                        @if($eventType->meeting_type === 'group')
                                            <span class="text-muted">
                                                <i class="fas fa-users me-1"></i>Group
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="mt-auto">
                                        <a href="{{ route('public.booking.show', ['brand' => $profile->brand_name, 'event' => $eventType->slug]) }}" 
                                           class="btn btn-primary w-100">
                                            <i class="fas fa-calendar-plus me-2"></i>Book Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <div class="feature-icon mx-auto mb-4" style="background-color: rgba(var(--primary-rgb), 0.1);">
                        <i class="fas fa-calendar-times text-primary fa-2x"></i>
                    </div>
                    <h4 class="mb-3">No Meeting Types Available</h4>
                    <p class="text-muted mb-4">This company hasn't set up any meeting types yet.</p>
                    <p class="text-muted">Please check back later or contact them directly.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-clock text-primary fa-2x"></i>
                    </div>
                    <h5 class="mb-3">Easy Scheduling</h5>
                    <p class="text-muted">Book meetings in just a few clicks with real-time availability</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-video text-primary fa-2x"></i>
                    </div>
                    <h5 class="mb-3">Google Meet Integration</h5>
                    <p class="text-muted">Automatic meeting links generated for all bookings</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-bell text-primary fa-2x"></i>
                    </div>
                    <h5 class="mb-3">Smart Reminders</h5>
                    <p class="text-muted">Automated email and SMS reminders for both parties</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    @if($profile->address || $profile->email || $profile->phone)
        <section class="py-5 bg-light">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h3 class="mb-4">Get in Touch</h3>
                        <div class="row g-4">
                            @if($profile->email)
                                <div class="col-md-4">
                                    <div class="feature-icon mx-auto">
                                        <i class="fas fa-envelope text-primary fa-2x"></i>
                                    </div>
                                    <h6 class="mb-2">Email</h6>
                                    <p class="text-muted">{{ $profile->email }}</p>
                                </div>
                            @endif
                            @if($profile->phone)
                                <div class="col-md-4">
                                    <div class="feature-icon mx-auto">
                                        <i class="fas fa-phone text-primary fa-2x"></i>
                                    </div>
                                    <h6 class="mb-2">Phone</h6>
                                    <p class="text-muted">{{ $profile->phone }}</p>
                                </div>
                            @endif
                            @if($profile->address)
                                <div class="col-md-4">
                                    <div class="feature-icon mx-auto">
                                        <i class="fas fa-map-marker-alt text-primary fa-2x"></i>
                                    </div>
                                    <h6 class="mb-2">Address</h6>
                                    <p class="text-muted">{{ $profile->address }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Footer -->
    <footer class="py-4 bg-dark text-white text-center">
        <div class="container">
            <p class="mb-0">© {{ date('Y') }} {{ $profile->company_name ?? 'Company' }}. Powered by 
                <a href="{{ url('/') }}" class="text-white text-decoration-none">
                    <span style="font-size: 20px; font-weight: bold;">Callbly</span>
                </a>
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>