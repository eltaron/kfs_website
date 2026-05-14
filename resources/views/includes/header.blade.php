<!-- ======================= Header Start ======================= -->
<header class="header-main">
    <div class="top-bar" id="top-bar">
        <div class="container d-flex justify-content-center justify-content-md-between align-items-center">

            <div class="d-none d-md-block date-time" id="date-time-container">
            </div>

            <div class="social-links d-flex align-items-center">
                <span>الموقع الان تجريبي وسيتم اطلاقه قريبا </span>
                <!-- <span>تابعنا علي مواقع التواصل الاجتماعي</span> -->
                <!-- <a href="https://x.com/kfs_gov" aria-label="X"><i class="fab fa-x-twitter"></i></a>
                <a href="https://www.facebook.com/KafrelsheikhGovernorate" aria-label="Facebook"><i
                        class="fab fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/kafr_elsheikh_gov" aria-label="Instagram"><i
                        class="fab fa-instagram"></i></a>
                <a href="https://youtube.com/@kafrelsheikhgovernorate583" aria-label="YouTube"><i
                        class="fab fa-youtube"></i></a>
                <a href="https://whatsapp.com/channel/0029VadcREoLI8YgR28sFz40" aria-label="WhatsApp"><i
                        class="fab fa-whatsapp"></i></a>
                <a href="#" aria-label="Telegram"><i class="fab fa-telegram-plane"></i></a>
                <a href="https://www.threads.net/kafr_elsheikh_gov" aria-label="threads"><i
                        class="fab fa-threads"></i></a>
                <a href="https://www.tiktok.com/@kfs_gov" aria-label="TikTok"><i class="fab fa-tiktok"></i></a> -->
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm main-nav">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ Storage::url($settings['site_logo_header']) }}" alt="شعار المحافظة" class="logo-img" />
            </a>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav m-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            عن المحافظة
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="aboutDropdown">
                            <li><a class="dropdown-item" href="{{ route('about') }}">عن المحافظة </a></li>
                            {{-- <li><a class="dropdown-item" href="#">إنجازات الدولة بالمحافظة</a></li> --}}
                            <li><a class="dropdown-item" href="{{ route('about.governor') }}">كلمة المحافظ</a></li>

                            {{-- This is the multi-level dropdown --}}
                            <li class="dropdown-submenu">
                                <a class="dropdown-item" href="#">
                                    قيادات المحافظة
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item"
                                            href="{{ route('officials.show', ['role' => 'governor']) }}">المحافظ</a>
                                    </li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('officials.show', ['role' => 'deputy-governor']) }}">نائب
                                            المحافظ</a></li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('officials.show', ['role' => 'secretary-general']) }}">السكرتير
                                            العام</a></li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('officials.show', ['role' => 'assistant-secretary-general']) }}">السكرتير
                                            العام المساعد</a></li>
                                </ul>
                            </li>

                            <li><a class="dropdown-item" href="{{ route('governorate.map') }}">خريطة المحافظة</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('posts.index') ? 'active' : '' }}"
                            href="{{ route('posts.index') }}">الأخبار</a></li>

                    <li class="nav-item dropdown">
                        <a class="nav-link " href="{{ route('services.index') }}">
                            الخدمات
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('projects.index') ? 'active' : '' }}"
                            href="{{ route('projects.index') }}">المشروعات</a></li>
                    <li class="nav-item"><a
                            class="nav-link {{ request()->routeIs('investments.index') ? 'active' : '' }}"
                            href="{{ route('investments.index') }}">الاستثمار</a></li>
                    <li class="nav-item"><a
                            class="nav-link {{ request()->routeIs('landmarks.index') ? 'active' : '' }}"
                            href="{{ route('landmarks.index') }}">السياحة</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            تواصل معنا
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('contact.index') }}">تواصل معنا </a></li>
                            <li><a class="dropdown-item" href="{{ route('complaints.create') }}">تقديم شكوي </a></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('emergency.create') }}">
                                    قدم بلاغ
                                </a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('suggestions.create') }}">تقديم مقترح </a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('surveys.service.create') }}">تقييم مستوى
                                    أداء
                                    الخدمات
                                </a>
                            </li>

                        </ul>
                    </li>
                </ul>
                {{-- Authentication links --}}
                <div class="d-flex align-items-center auth-links">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-primary me-2">تسجيل الدخول</a>
                        {{-- <a href="{{ route('register.citizen') }}" class="btn btn-primary">حساب جديد</a> --}}
                    @else
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i> مرحبا، {{ Str::words(Auth::user()->name, 1, '') }}
                            </a>
                            <ul class="dropdown-menu">

                                {{-- لوحة تحكم المواطن --}}
                                <li>
                                    <a class="dropdown-item justify-content-start"
                                        href="{{ route('citizen.dashboard') }}">
                                        <i class="fas fa-columns ms-2"></i> لوحة التحكم
                                    </a>
                                </li>

                                {{-- بوابة الموظفين ERP (تظهر فقط للموظفين) --}}
                                @if (auth()->user()->isEmployee())
                                    <li>
                                        <a class="dropdown-item fw-bold justify-content-start"
                                            href="{{ route('employee.erp.index') }}">
                                            <i class="fas fa-briefcase ms-2"></i> بوابة الموظفين (ERP)
                                        </a>
                                    </li>
                                @endif

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                {{-- تسجيل الخروج --}}
                                <li>
                                    <a class="dropdown-item text-danger justify-content-start"
                                        href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt ms-2"></i> تسجيل الخروج
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>

                {{-- START: Custom Language Switcher --}}
                <div class="language-switcher dropdown">
                    <button class="language-switcher-button dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-globe"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end text-center">
                        <li><a class="dropdown-item" onclick="setLanguage('ar')">العربية</a></li>
                        <li><a class="dropdown-item" onclick="setLanguage('en')">English</a></li>
                        <li><a class="dropdown-item" onclick="setLanguage('fr')">Français</a></li>
                        <li><a class="dropdown-item" onclick="setLanguage('de')">Deutsch</a></li>
                        <li><a class="dropdown-item" onclick="setLanguage('es')">Español</a></li>
                        <li><a class="dropdown-item" onclick="setLanguage('zh-CN')">中文 (Chinese)</a></li>
                        <li><a class="dropdown-item" onclick="setLanguage('ja')">日本語 (Japanese)</a></li>
                        <li><a class="dropdown-item" onclick="setLanguage('ko')">한국어 (Korean)</a></li>
                    </ul>
                    <div id="google_translate_element" style="display:none"></div>
                </div>
                {{-- END: Custom Language Switcher --}}
                {{-- زر فتح البحث --}}
                <button class="header-search-btn" id="searchTrigger" title="ابحث في الموقع">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <button class="navbar-toggler" type="button" id="mobileMenuToggler" aria-label="Toggle navigation">
                <i class="fas fa-grip"></i>
            </button>
        </div>
    </nav>
</header>
<!-- ======================= Header End ======================= -->
<!-- ======================= Mobile Menu Start ======================= -->
<div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

<div class="mobile-menu-panel" style="z-index: 9999999999;" id="mobileMenuPanel">
    <button class="close-btn" id="closeMobileMenu">&times;</button>
    
    <div class="menu-content px-3">
        {{-- عرض ترحيبي أو اسم الموقع --}}
        <div class="text-center py-4">
            <img src="{{ Storage::url($settings['site_logo_header']) }}" alt="Logo" style="height: 60px;">
        </div>

        <ul class="list-unstyled">
            {{-- الرئيسية --}}
            <li>
                <a style="justify-content: right;" href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active-mobile-link' : '' }}">
                    <i class="fas fa-home ms-2"></i> الرئيسية
                </a>
            </li>

            {{-- عن المحافظة (قائمة منسدلة) --}}
            <li class="mobile-dropdown">
                <a style="justify-content: right;" href="javascript:void(0)" class="dropdown-trigger d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-info-circle ms-2"></i> عن المحافظة</span>
                    <i class="fas fa-chevron-down small"></i>
                </a>
                <ul class="mobile-submenu list-unstyled pe-4 mt-2 display-none" style="display: none; background: #f9f9f9; border-radius: 8px;">
                    <li><a href="{{ route('about') }}">عن المحافظة</a></li>
                    <li><a href="{{ route('about.governor') }}">كلمة المحافظ</a></li>
                    <li><a href="{{ route('officials.show', ['role' => 'governor']) }}">المحافظ</a></li>
                    <li><a href="{{ route('officials.show', ['role' => 'deputy-governor']) }}">نائب المحافظ</a></li>
                    <li><a href="{{ route('officials.show', ['role' => 'secretary-general']) }}">السكرتير العام</a></li>
                    <li><a href="{{ route('officials.show', ['role' => 'assistant-secretary-general']) }}">السكرتير المساعد</a></li>
                    <li><a href="{{ route('governorate.map') }}">خريطة المحافظة</a></li>
                </ul>
            </li>

            {{-- الأخبار --}}
            <li>
                <a style="justify-content: right;" href="{{ route('posts.index') }}" class="{{ request()->routeIs('posts.index') ? 'active-mobile-link' : '' }}">
                    <i class="fas fa-newspaper ms-2"></i> الأخبار
                </a>
            </li>

            {{-- الخدمات --}}
            <li>
                <a style="justify-content: right;" href="{{ route('services.index') }}" class="{{ request()->routeIs('services.index') ? 'active-mobile-link' : '' }}">
                    <i class="fas fa-concierge-bell ms-2"></i> الخدمات
                </a>
            </li>

            {{-- المشروعات --}}
            <li>
                <a style="justify-content: right;" href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.index') ? 'active-mobile-link' : '' }}">
                    <i class="fas fa-project-diagram ms-2"></i> المشروعات
                </a>
            </li>

            {{-- الاستثمار --}}
            <li>
                <a style="justify-content: right;" href="{{ route('investments.index') }}" class="{{ request()->routeIs('investments.index') ? 'active-mobile-link' : '' }}">
                    <i class="fas fa-chart-line ms-2"></i> الاستثمار
                </a>
            </li>

            {{-- السياحة --}}
            <li>
                <a style="justify-content: right;" href="{{ route('landmarks.index') }}" class="{{ request()->routeIs('landmarks.index') ? 'active-mobile-link' : '' }}">
                    <i class="fas fa-monument ms-2"></i> السياحة
                </a>
            </li>

            {{-- تواصل معنا (قائمة منسدلة) --}}
            <li class="mobile-dropdown">
                <a style="justify-content: right;" href="javascript:void(0)" class="dropdown-trigger d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-envelope-open-text ms-2"></i> تواصل معنا</span>
                    <i class="fas fa-chevron-down small"></i>
                </a>
                <ul class="mobile-submenu list-unstyled pe-4 mt-2 display-none" style="display: none; background: #f9f9f9;">
                    <li><a href="{{ route('contact.index') }}">تواصل معنا</a></li>
                    <li><a href="{{ route('complaints.create') }}">تقديم شكوى</a></li>
                    <li><a href="{{ route('emergency.create') }}">تقديم بلاغ طوارئ</a></li>
                    <li><a href="{{ route('suggestions.create') }}">تقديم مقترح</a></li>
                    <li><a href="{{ route('surveys.service.create') }}">تقييم أداء الخدمات</a></li>
                </ul>
            </li>

            {{-- حساب المستخدم --}}
            @guest
                <li><a style="justify-content: right;" href="{{ route('login') }}" class="btn btn-primary text-white mx-3 my-2 text-center">تسجيل الدخول</a></li>
                <li><a style="justify-content: right;" href="{{ route('register.citizen') }}" class="btn btn-outline-primary mx-3 text-center">تسجيل جديد</a></li>
            @else
                <li><a style="justify-content: right;" href="{{ route('citizen.dashboard') }}"><i class="fas fa-columns ms-2"></i> لوحة تحكم المواطن</a></li>
                
                @if (auth()->user()->isEmployee())
                    <li>
                        <a style="justify-content: right;" href="{{ route('employee.erp.index') }}" class="fw-bold text-success">
                            <i class="fas fa-briefcase ms-2"></i> بوابة الموظفين (ERP)
                        </a>
                    </li>
                @endif

                <li>
                    <a style="justify-content: right;" href="{{ route('logout') }}" class="text-danger" 
                       onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                        <i class="fas fa-sign-out-alt ms-2"></i> تسجيل الخروج
                    </a>
                </li>
            @endguest
        </ul>

       
    </div>
</div>
<!-- ======================= Mobile Menu End ======================= -->
{{-- مودال البحث الاحترافي --}}
<div class="search-overlay" id="searchOverlay">
    <button class="close-search" id="closeSearch">&times;</button>
    <div class="search-container">
        <div class="search-header-text">
            <h2>ما الذي تبحث عنه؟</h2>
            <p>ابحث في أخبار، خدمات، ومشروعات محافظة كفر الشيخ</p>
        </div>
        <form action="{{ route('search') }}" class="search-modal-form">
            <div class="input-group-premium">
                <input type="text" name="query" placeholder="اكتب كلمة البحث هنا..." autocomplete="off"
                    id="searchInput">
                <button type="submit" class="modal-submit-btn">
                    <i class="fas fa-search"></i>
                    <span>بحث</span>
                </button>
            </div>
        </form>
        <div class="search-hints mt-4">
            <span>كلمات شائعة:</span>
            <a href="{{ route('search', ['query' => 'الخطة الاستثمارية']) }}">الخطة الاستثمارية</a>
            <a href="{{ route('search', ['query' => 'وظائف']) }}">وظائف</a>
            <a href="{{ route('search', ['query' => 'مبادرة حياة كريمة']) }}">حياة كريمة</a>
        </div>
    </div>
</div>
