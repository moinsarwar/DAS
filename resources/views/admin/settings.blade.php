@extends('layouts.app')

@section('title', 'Clinic Settings')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-gear-fill text-primary me-2"></i> Clinic Settings & Homepage Editor
                        </h5>
                        <small class="text-muted">Manage all header, footer, brand, and content parameters</small>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="settings-form">
                        @csrf
                        @method('PUT')

                        {{-- Hidden inputs to serialize JSON lists --}}
                        <input type="hidden" name="features" id="features-hidden-input">
                        <input type="hidden" name="faqs" id="faqs-hidden-input">

                        {{-- Nav Tabs --}}
                        <ul class="nav nav-tabs bg-light px-4 pt-3 border-bottom" id="settingsTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold text-secondary" id="header-tab" data-bs-toggle="tab" data-bs-target="#header" type="button" role="tab" aria-controls="header" aria-selected="true">
                                    <i class="bi bi-layout-header-sidebar me-1"></i> Header Settings
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-secondary" id="footer-tab" data-bs-toggle="tab" data-bs-target="#footer" type="button" role="tab" aria-controls="footer" aria-selected="false">
                                    <i class="bi bi-layout-sidebar-reverse me-1"></i> Footer Settings
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-secondary" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero" type="button" role="tab" aria-controls="hero" aria-selected="false">
                                    <i class="bi bi-window-sidebar me-1"></i> Hero Section
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-secondary" id="features-tab" data-bs-toggle="tab" data-bs-target="#features" type="button" role="tab" aria-controls="features" aria-selected="false">
                                    <i class="bi bi-grid-fill me-1"></i> Highlights & Features
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-secondary" id="theme-tab" data-bs-toggle="tab" data-bs-target="#theme" type="button" role="tab" aria-controls="theme" aria-selected="false">
                                    <i class="bi bi-palette me-1"></i> Theme Layout
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-secondary" id="typography-tab" data-bs-toggle="tab" data-bs-target="#typography" type="button" role="tab" aria-controls="typography" aria-selected="false">
                                    <i class="bi bi-fonts me-1"></i> Typography
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-secondary" id="faqs-tab" data-bs-toggle="tab" data-bs-target="#faqs" type="button" role="tab" aria-controls="faqs" aria-selected="false">
                                    <i class="bi bi-question-circle me-1"></i> FAQs Editor
                                </button>
                            </li>
                        </ul>

                        {{-- Tab Content --}}
                        <div class="tab-content p-4" id="settingsTabsContent">
                            
                            {{-- Tab 1: Header Settings --}}
                            <div class="tab-pane fade show active" id="header" role="tabpanel" aria-labelledby="header-tab">
                                <div class="row align-items-center mb-4 pb-3 border-bottom">
                                    <div class="col-md-3 text-center mb-3 mb-md-0">
                                        <label class="form-label d-block fw-bold mb-2">Navbar Logo</label>
                                        @if($settings && $settings->logo_path)
                                            <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo" class="img-fluid rounded border p-2 shadow-sm" style="max-height: 100px;">
                                        @else
                                            <div class="bg-light border rounded p-4 text-muted fst-italic shadow-sm">
                                                <i class="bi bi-image fs-1 d-block mb-1 text-secondary"></i> No logo
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-label fw-semibold">Upload New Logo</label>
                                        <input type="file" name="logo" class="form-control mb-2" accept="image/*">
                                        <div class="form-text small">Recommended: Height 50-80px (PNG/JPG format). Maximum size: 2MB.</div>
                                    </div>
                                </div>

                                <div class="row align-items-center mb-4 pb-3 border-bottom">
                                    <div class="col-md-3 text-center mb-3 mb-md-0">
                                        <label class="form-label d-block fw-bold mb-2">Favicon</label>
                                        @if($settings && $settings->favicon_path)
                                            <img src="{{ asset('storage/' . $settings->favicon_path) }}" alt="Favicon" class="img-fluid rounded border p-2 shadow-sm" style="max-height: 50px;">
                                        @else
                                            <div class="bg-light border rounded p-2 text-muted fst-italic shadow-sm">
                                                <i class="bi bi-globe fs-3 d-block mb-1 text-secondary"></i> No Favicon
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-label fw-semibold">Upload New Favicon</label>
                                        <input type="file" name="favicon" class="form-control mb-2" accept=".ico,image/png,image/jpeg,image/svg+xml">
                                        <div class="form-text small">Recommended: 32x32px or 64x64px (ICO/PNG format).</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Clinic Display Name</label>
                                    <input type="text" name="clinic_name" class="form-control" value="{{ old('clinic_name', $settings->clinic_name ?? 'Multan Cancer Clinic') }}" placeholder="e.g. Multan Cancer Clinic">
                                    <div class="form-text">Used on navbar title, site copyright headers, and search bot prompts.</div>
                                </div>

                                <hr class="my-4">
                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-people-fill text-primary me-2"></i> Our Doctors Page Header</h6>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Doctors Page Title</label>
                                    <input type="text" name="doctors_title" class="form-control" value="{{ old('doctors_title', $settings->doctors_title ?? 'Meet Our Specialist Oncologists') }}" placeholder="e.g. Meet Our Specialist Oncologists">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Doctors Page Description Subtitle</label>
                                    <textarea name="doctors_description" class="form-control" rows="2" placeholder="Highly qualified and experienced consultants dedicated to your care.">{{ old('doctors_description', $settings->doctors_description ?? '') }}</textarea>
                                </div>
                            </div>

                            {{-- Tab: Theme Layout --}}
                            <div class="tab-pane fade" id="theme" role="tabpanel" aria-labelledby="theme-tab">
                                <div class="mb-4">
                                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-palette me-2"></i>Appearance & Theme</h5>
                                    <p class="text-muted small mb-4">Customize the structural layout and core color palette of your public pages.</p>
                                    <label class="form-label fw-semibold mb-3">UI Theme Layout</label>
                                    
                                    <style>
                                        .theme-selector {
                                            display: grid;
                                            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                                            gap: 1.5rem;
                                        }
                                        .theme-card-input {
                                            display: none;
                                        }
                                        .theme-card {
                                            border: 2px solid #e2e8f0;
                                            border-radius: 0.75rem;
                                            padding: 1rem;
                                            cursor: pointer;
                                            transition: all 0.2s ease;
                                            position: relative;
                                            background: white;
                                        }
                                        .theme-card:hover {
                                            border-color: #cbd5e1;
                                            transform: translateY(-2px);
                                        }
                                        .theme-card-input:checked + .theme-card {
                                            border-color: #0d6efd;
                                            background-color: #f8fbff;
                                            box-shadow: 0 4px 6px -1px rgba(13, 110, 253, 0.1);
                                        }
                                        .theme-card-input:checked + .theme-card::after {
                                            content: '\F26A';
                                            font-family: 'bootstrap-icons';
                                            position: absolute;
                                            top: -10px;
                                            right: -10px;
                                            background: #0d6efd;
                                            color: white;
                                            border-radius: 50%;
                                            width: 24px;
                                            height: 24px;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            font-size: 14px;
                                        }
                                        .theme-preview {
                                            height: 100px;
                                            border-radius: 0.5rem;
                                            margin-bottom: 1rem;
                                            border: 1px solid rgba(0,0,0,0.05);
                                            display: flex;
                                            flex-direction: column;
                                            overflow: hidden;
                                        }
                                        .theme-header { height: 20%; width: 100%; }
                                        .theme-body { height: 80%; width: 100%; display: flex; gap: 4px; padding: 4px; }
                                        .theme-sidebar { width: 30%; height: 100%; border-radius: 2px; }
                                        .theme-main { width: 70%; height: 100%; border-radius: 2px; }
                                    </style>

                                    <div class="theme-selector">
                                        <!-- Classic Blue (Default) -->
                                        <label>
                                            <input type="radio" name="ui_theme" value="default" class="theme-card-input" {{ ($settings->ui_theme ?? 'default') == 'default' ? 'checked' : '' }}>
                                            <div class="theme-card">
                                                <div class="theme-preview" style="background: #f8fbff;">
                                                    <div class="theme-header" style="background: white; border-bottom: 1px solid #e2e8f0;"></div>
                                                    <div class="theme-body" style="flex-direction: column;">
                                                        <div style="background: #0d6efd; height: 30%; border-radius: 2px;"></div>
                                                        <div style="background: white; height: 70%; border-radius: 2px;"></div>
                                                    </div>
                                                </div>
                                                <h6 class="fw-bold mb-1">Classic Blue</h6>
                                                <small class="text-muted d-block">Default Professional</small>
                                            </div>
                                        </label>

                                        <!-- Midnight Tech -->
                                        <label>
                                            <input type="radio" name="ui_theme" value="modern_dark" class="theme-card-input" {{ ($settings->ui_theme ?? '') == 'modern_dark' ? 'checked' : '' }}>
                                            <div class="theme-card">
                                                <div class="theme-preview" style="background: #0f172a;">
                                                    <div class="theme-body">
                                                        <div class="theme-sidebar" style="background: #1e293b; border-right: 1px solid #334155;"></div>
                                                        <div class="theme-main" style="background: transparent; display: flex; flex-direction: column; gap: 4px;">
                                                            <div style="background: rgba(59, 130, 246, 0.2); height: 40%; border-radius: 2px;"></div>
                                                            <div style="background: #1e293b; height: 60%; border-radius: 2px;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h6 class="fw-bold mb-1">Midnight Tech</h6>
                                                <small class="text-muted d-block">Modern Dark Sidebar</small>
                                            </div>
                                        </label>

                                        <!-- Eco Green -->
                                        <label>
                                            <input type="radio" name="ui_theme" value="nature_green" class="theme-card-input" {{ ($settings->ui_theme ?? '') == 'nature_green' ? 'checked' : '' }}>
                                            <div class="theme-card">
                                                <div class="theme-preview" style="background: #f0fdf4;">
                                                    <div class="theme-header" style="background: white; border-bottom: 2px solid #10b981; display: flex; justify-content: center; align-items: center;">
                                                        <div style="width: 40%; height: 40%; background: #d1fae5; border-radius: 10px;"></div>
                                                    </div>
                                                    <div class="theme-body" style="justify-content: center; align-items: center; padding: 10px;">
                                                        <div style="background: white; width: 80%; height: 80%; border-radius: 10px; border: 1px solid #d1fae5;"></div>
                                                    </div>
                                                </div>
                                                <h6 class="fw-bold mb-1">Eco Green</h6>
                                                <small class="text-muted d-block">Centered Organic</small>
                                            </div>
                                        </label>

                                        <!-- Deep Ocean -->
                                        <label>
                                            <input type="radio" name="ui_theme" value="ocean_blue" class="theme-card-input" {{ ($settings->ui_theme ?? '') == 'ocean_blue' ? 'checked' : '' }}>
                                            <div class="theme-card">
                                                <div class="theme-preview" style="background: #f8fafc;">
                                                    <div class="theme-header" style="background: #0f172a; height: 15%;"></div>
                                                    <div class="theme-header" style="background: white; height: 25%; border-bottom: 1px solid #e2e8f0;"></div>
                                                    <div class="theme-body" style="padding: 0;">
                                                        <div style="background: #0284c7; width: 100%; height: 50%;"></div>
                                                    </div>
                                                </div>
                                                <h6 class="fw-bold mb-1">Deep Ocean</h6>
                                                <small class="text-muted d-block">Wide Clinical Blocks</small>
                                            </div>
                                        </label>

                                        <!-- Sunset Orange -->
                                        <label>
                                            <input type="radio" name="ui_theme" value="sunset_orange" class="theme-card-input" {{ ($settings->ui_theme ?? '') == 'sunset_orange' ? 'checked' : '' }}>
                                            <div class="theme-card">
                                                <div class="theme-preview" style="background: #fff7ed;">
                                                    <div class="theme-header" style="background: white;"></div>
                                                    <div class="theme-body" style="padding: 0;">
                                                        <div style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); width: 100%; height: 100%; border-radius: 0 0 50% 50% / 20px;"></div>
                                                    </div>
                                                </div>
                                                <h6 class="fw-bold mb-1">Sunset Orange</h6>
                                                <small class="text-muted d-block">Warm Vibrant Curves</small>
                                            </div>
                                        </label>

                                        <!-- Royal Purple -->
                                        <label>
                                            <input type="radio" name="ui_theme" value="royal_purple" class="theme-card-input" {{ ($settings->ui_theme ?? '') == 'royal_purple' ? 'checked' : '' }}>
                                            <div class="theme-card">
                                                <div class="theme-preview" style="background: #faf5ff;">
                                                    <div class="theme-header" style="background: #581c87; height: 30%;"></div>
                                                    <div class="theme-body" style="justify-content: center; padding-top: 10px;">
                                                        <div style="background: white; width: 80%; height: 90%; border: 2px solid #e9d5ff;"></div>
                                                    </div>
                                                </div>
                                                <h6 class="fw-bold mb-1">Royal Purple</h6>
                                                <small class="text-muted d-block">Elegant Premium</small>
                                            </div>
                                        </label>

                                        <!-- Clean Minimal -->
                                        <label>
                                            <input type="radio" name="ui_theme" value="clean_minimal" class="theme-card-input" {{ ($settings->ui_theme ?? '') == 'clean_minimal' ? 'checked' : '' }}>
                                            <div class="theme-card">
                                                <div class="theme-preview" style="background: #ffffff; border: 1px solid #f1f5f9;">
                                                    <div class="theme-header" style="background: transparent; border-bottom: 1px solid #f8fafc;"></div>
                                                    <div class="theme-body" style="padding: 10px; display: flex; gap: 10px;">
                                                        <div style="background: #f8fafc; width: 50%; height: 100%;"></div>
                                                        <div style="background: #f8fafc; width: 50%; height: 100%;"></div>
                                                    </div>
                                                </div>
                                                <h6 class="fw-bold mb-1">Clean Minimal</h6>
                                                <small class="text-muted d-block">Ultra Modern White</small>
                                            </div>
                                        </label>

                                        <!-- Luxury Gold -->
                                        <label>
                                            <input type="radio" name="ui_theme" value="luxury_gold" class="theme-card-input" {{ ($settings->ui_theme ?? '') == 'luxury_gold' ? 'checked' : '' }}>
                                            <div class="theme-card">
                                                <div class="theme-preview" style="background: #171717;">
                                                    <div class="theme-header" style="background: #262626; border-bottom: 1px solid #d4af37;"></div>
                                                    <div class="theme-body" style="padding: 15px;">
                                                        <div style="background: transparent; border: 1px solid #d4af37; width: 100%; height: 100%;"></div>
                                                    </div>
                                                </div>
                                                <h6 class="fw-bold mb-1">Luxury Gold</h6>
                                                <small class="text-muted d-block">Exclusive Dark Gold</small>
                                            </div>
                                        </label>

                                        <!-- Soft Rose -->
                                        <label>
                                            <input type="radio" name="ui_theme" value="soft_rose" class="theme-card-input" {{ ($settings->ui_theme ?? '') == 'soft_rose' ? 'checked' : '' }}>
                                            <div class="theme-card">
                                                <div class="theme-preview" style="background: #fff1f2;">
                                                    <div class="theme-header" style="background: white; border-radius: 0 0 10px 10px;"></div>
                                                    <div class="theme-body" style="justify-content: space-around; align-items: center;">
                                                        <div style="background: #fda4af; width: 25%; height: 50%; border-radius: 5px;"></div>
                                                        <div style="background: #fda4af; width: 25%; height: 50%; border-radius: 5px;"></div>
                                                        <div style="background: #fda4af; width: 25%; height: 50%; border-radius: 5px;"></div>
                                                    </div>
                                                </div>
                                                <h6 class="fw-bold mb-1">Soft Rose</h6>
                                                <small class="text-muted d-block">Gentle Pastel Layout</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="form-text mt-3">Select the structural layout and color palette for your public pages. This completely changes the website's appearance.</div>
                                </div>
                            </div>

                            {{-- Tab: Typography --}}
                            <div class="tab-pane fade" id="typography" role="tabpanel" aria-labelledby="typography-tab">
                                <div class="mb-4">
                                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-fonts me-2"></i>Typography</h5>
                                    <p class="text-muted small mb-4">Select the primary font family used across the public website.</p>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Global Font Style</label>
                                            <select name="font_family" class="form-select">
                                                <option value="Inter" {{ ($settings->font_family ?? 'Inter') == 'Inter' ? 'selected' : '' }}>Inter (Clean, Modern, Default)</option>
                                                <option value="Roboto" {{ ($settings->font_family ?? '') == 'Roboto' ? 'selected' : '' }}>Roboto (Geometric, Professional)</option>
                                                <option value="Poppins" {{ ($settings->font_family ?? '') == 'Poppins' ? 'selected' : '' }}>Poppins (Rounded, Friendly)</option>
                                                <option value="'Playfair Display'" {{ ($settings->font_family ?? '') == "'Playfair Display'" ? 'selected' : '' }}>Playfair Display (Serif, Elegant, Luxury)</option>
                                                <option value="Montserrat" {{ ($settings->font_family ?? '') == 'Montserrat' ? 'selected' : '' }}>Montserrat (Wide, Contemporary)</option>
                                                <option value="Lora" {{ ($settings->font_family ?? '') == 'Lora' ? 'selected' : '' }}>Lora (Serif, Classic, Readable)</option>
                                                <option value="Nunito" {{ ($settings->font_family ?? '') == 'Nunito' ? 'selected' : '' }}>Nunito (Soft, Welcoming)</option>
                                                <option value="Raleway" {{ ($settings->font_family ?? '') == 'Raleway' ? 'selected' : '' }}>Raleway (Thin, Elegant)</option>
                                                <option value="Merriweather" {{ ($settings->font_family ?? '') == 'Merriweather' ? 'selected' : '' }}>Merriweather (Serif, Traditional)</option>
                                                <option value="Ubuntu" {{ ($settings->font_family ?? '') == 'Ubuntu' ? 'selected' : '' }}>Ubuntu (Tech, Modern)</option>
                                            </select>
                                            <div class="form-text">This will automatically import the required fonts from Google Fonts and apply them to the site.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tab 2: Footer Settings --}}
                            <div class="tab-pane fade" id="footer" role="tabpanel" aria-labelledby="footer-tab">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Footer About Summary Description</label>
                                        <textarea name="about_short" class="form-control" rows="3" placeholder="Brief about clinic... ">{{ old('about_short', $settings->about_short ?? '') }}</textarea>
                                        <div class="form-text">Displayed on footer left column.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Clinic Display Address</label>
                                        <textarea name="address" class="form-control" rows="3" required>{{ old('address', $settings->address ?? '') }}</textarea>
                                    </div>
                                </div>

                                <hr class="my-3">

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Mobile Phone Number</label>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $settings->phone ?? '') }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Landline Number</label>
                                        <input type="text" name="landline" class="form-control" value="{{ old('landline', $settings->landline ?? '') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Contact Email</label>
                                        <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $settings->contact_email ?? '') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Operating shift hours</label>
                                        <input type="text" name="clinic_hours" class="form-control" value="{{ old('clinic_hours', $settings->clinic_hours ?? '') }}" placeholder="e.g. 02:00 PM - 08:00 PM">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Operating active days</label>
                                        <input type="text" name="clinic_days" class="form-control" value="{{ old('clinic_days', $settings->clinic_days ?? '') }}" placeholder="e.g. Mon - Sat">
                                    </div>
                                </div>

                                <hr class="my-3">
                                <h6 class="fw-bold mb-3">Social Networks & Links</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label small fw-semibold"><i class="bi bi-facebook text-primary me-1"></i> Facebook URL</label>
                                        <input type="url" name="social_facebook" class="form-control" value="{{ old('social_facebook', $settings->social_facebook ?? '') }}" placeholder="https://facebook.com/page">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label small fw-semibold"><i class="bi bi-twitter-x text-dark me-1"></i> Twitter / X URL</label>
                                        <input type="url" name="social_twitter" class="form-control" value="{{ old('social_twitter', $settings->social_twitter ?? '') }}" placeholder="https://twitter.com/page">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label small fw-semibold"><i class="bi bi-instagram text-danger me-1"></i> Instagram URL</label>
                                        <input type="url" name="social_instagram" class="form-control" value="{{ old('social_instagram', $settings->social_instagram ?? '') }}" placeholder="https://instagram.com/page">
                                    </div>
                                </div>
                            </div>

                            {{-- Tab 3: Hero Section --}}
                            <div class="tab-pane fade" id="hero" role="tabpanel" aria-labelledby="hero-tab">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Hero Badge text</label>
                                    <input type="text" name="hero_badge" class="form-control" value="{{ old('hero_badge', $settings->hero_badge ?? '') }}" placeholder="e.g. Excellence in Oncology">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Hero Title</label>
                                        <input type="text" name="hero_title" class="form-control" value="{{ old('hero_title', $settings->hero_title ?? '') }}" placeholder="e.g. Multan Cancer">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Hero Subtitle (Underlined focus)</label>
                                        <input type="text" name="hero_subtitle" class="form-control" value="{{ old('hero_subtitle', $settings->hero_subtitle ?? '') }}" placeholder="e.g. Clinic">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Hero Description Lead</label>
                                    <textarea name="hero_description" class="form-control" rows="3" placeholder="Brief clinic description... ">{{ old('hero_description', $settings->hero_description ?? '') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-danger">Urgent / Important Notice Box</label>
                                    <textarea name="notice_text" class="form-control border-danger border-opacity-20 text-dark" rows="2" placeholder="Urgent message for landing page... ">{{ old('notice_text', $settings->notice_text ?? '') }}</textarea>
                                    <div class="form-text text-danger">Will be displayed in a red-accent notice box. Leave empty to hide.</div>
                                </div>
                            </div>

                            {{-- Tab 4: Highlights & Features --}}
                            <div class="tab-pane fade" id="features" role="tabpanel" aria-labelledby="features-tab">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-0">Clinic Highlights</h6>
                                        <small class="text-muted">Edit features listed on the homepage</small>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" onclick="addFeature()">
                                        <i class="bi bi-plus-lg"></i> Add Highlight
                                    </button>
                                </div>

                                <div id="features-container">
                                    {{-- Generated by JS --}}
                                </div>
                            </div>

                            {{-- Tab 5: FAQs Editor --}}
                            <div class="tab-pane fade" id="faqs" role="tabpanel" aria-labelledby="faqs-tab">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-0">Accordion FAQ Items</h6>
                                        <small class="text-muted">Display patient guidelines and questions</small>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" onclick="addFaq()">
                                        <i class="bi bi-plus-lg"></i> Add FAQ Question
                                    </button>
                                </div>

                                <div id="faqs-container">
                                    {{-- Generated by JS --}}
                                </div>
                            </div>

                        </div>

                        {{-- Footer Buttons --}}
                        <div class="card-footer bg-light p-3 border-top d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light rounded-pill px-4" onclick="window.location.reload();">Reset</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Features Handling
    let features = {!! json_encode($settings->features ?? []) !!};
    if (typeof features === 'string') {
        try {
            features = JSON.parse(features);
        } catch(e) {
            features = [];
        }
    }
    // Default if empty
    if (!Array.isArray(features) || features.length === 0) {
        features = [
            {icon: 'bi-hospital', title: 'Oncologist Consultants', description: 'Specialized doctors available for detailed consultations and treatment planning.'},
            {icon: 'bi-clock', title: 'Scheduled Slots', description: 'No waiting in long queues. Book your specific time slot with your preferred doctor.'},
            {icon: 'bi-shield-check', title: 'Digital Records', description: 'Your medical history and prescriptions are securely stored and easily accessible.'}
        ];
    }

    function renderFeatures() {
        const container = document.getElementById('features-container');
        container.innerHTML = '';
        features.forEach((item, index) => {
            container.innerHTML += `
                <div class="card mb-3 border shadow-none" style="border-radius: 12px;">
                    <div class="card-body p-3 bg-light bg-opacity-30">
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <span class="badge bg-primary text-white p-2">Highlight #${index + 1}</span>
                            <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="removeFeature(${index})">
                                <i class="bi bi-trash3-fill"></i> Delete
                            </button>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold">Bootstrap Icon Class</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi ${item.icon || 'bi-star'} text-primary fs-5"></i></span>
                                    <input type="text" class="form-control" value="${item.icon || ''}" oninput="updateFeature(${index}, 'icon', this.value); updateIconPreview(this);">
                                </div>
                                <div class="form-text" style="font-size:0.75rem;">e.g. <code>bi-hospital</code>, <code>bi-clock</code>, <code>bi-shield-check</code></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Title</label>
                                <input type="text" class="form-control" value="${item.title || ''}" oninput="updateFeature(${index}, 'title', this.value)">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small fw-semibold">Description</label>
                                <textarea class="form-control" rows="2" oninput="updateFeature(${index}, 'description', this.value)">${item.description || ''}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        document.getElementById('features-hidden-input').value = JSON.stringify(features);
    }

    function addFeature() {
        features.push({icon: 'bi-star', title: 'New Highlight', description: 'Highlight details...'});
        renderFeatures();
    }

    function removeFeature(index) {
        features.splice(index, 1);
        renderFeatures();
    }

    function updateFeature(index, key, val) {
        features[index][key] = val;
        document.getElementById('features-hidden-input').value = JSON.stringify(features);
    }

    function updateIconPreview(input) {
        const val = input.value.trim() || 'bi-star';
        const icon = input.closest('.input-group').querySelector('.input-group-text i');
        icon.className = 'bi ' + val + ' text-primary fs-5';
    }


    // FAQs Handling
    let faqs = {!! json_encode($settings->faqs ?? []) !!};
    if (typeof faqs === 'string') {
        try {
            faqs = JSON.parse(faqs);
        } catch(e) {
            faqs = [];
        }
    }
    if (!Array.isArray(faqs) || faqs.length === 0) {
        faqs = [
            {question: 'Do you offer emergency services?', answer: 'No, Multan Cancer Clinic is a consultant-based clinic. We do not have a 24-hour emergency department. Please visit a general hospital for emergencies.'},
            {question: 'How do I book an appointment?', answer: 'You must register or login to our portal. Once logged in, you can view available doctors and select a time slot that suits you.'},
            {question: 'What are the clinic timings?', answer: 'Our clinic operates from 02:00 PM to 08:00 PM, Monday through Saturday. Doctors have specific slots within these hours.'}
        ];
    }

    function renderFaqs() {
        const container = document.getElementById('faqs-container');
        container.innerHTML = '';
        faqs.forEach((item, index) => {
            container.innerHTML += `
                <div class="card mb-3 border shadow-none" style="border-radius: 12px;">
                    <div class="card-body p-3 bg-light bg-opacity-30">
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <span class="badge bg-secondary text-white p-2">FAQ Item #${index + 1}</span>
                            <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="removeFaq(${index})">
                                <i class="bi bi-trash3-fill"></i> Delete
                            </button>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Question</label>
                            <input type="text" class="form-control" value="${item.question || ''}" oninput="updateFaq(${index}, 'question', this.value)">
                        </div>
                        <div>
                            <label class="form-label small fw-semibold">Answer</label>
                            <textarea class="form-control" rows="2" oninput="updateFaq(${index}, 'answer', this.value)">${item.answer || ''}</textarea>
                        </div>
                    </div>
                </div>
            `;
        });
        document.getElementById('faqs-hidden-input').value = JSON.stringify(faqs);
    }

    function addFaq() {
        faqs.push({question: 'New Question?', answer: 'Answer details...'});
        renderFaqs();
    }

    function removeFaq(index) {
        faqs.splice(index, 1);
        renderFaqs();
    }

    function updateFaq(index, key, val) {
        faqs[index][key] = val;
        document.getElementById('faqs-hidden-input').value = JSON.stringify(faqs);
    }

    // Init
    $(document).ready(function() {
        renderFeatures();
        renderFaqs();

        // Bind form submission to serialize just before posting
        $('#settings-form').submit(function() {
            document.getElementById('features-hidden-input').value = JSON.stringify(features);
            document.getElementById('faqs-hidden-input').value = JSON.stringify(faqs);
            return true;
        });
    });
</script>
@endpush