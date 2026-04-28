<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    {{--  ===== 1. Basic Meta Tags ===== --}}
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="google" content="notranslate" />
    <link rel="alternate" href="{{ url()->current() }}" hreflang="ar" />

    {{--  ===== 2. Dynamic SEO Meta Tags ===== --}}
    <title>محافظة كفر الشيخ - الموقع الرسمي</title>
    <meta name="description" content="@yield('description', $settings['site_description'] ?? 'البوابة الإلكترونية الرسمية لمحافظة كفر الشيخ، تقدم آخر الأخبار، الخدمات، والمشاريع.')" />
    <meta name="keywords" content="محافظة كفر الشيخ, كفر الشيخ, خدمات حكومية, أخبار كفر الشيخ, استثمار" />
    <meta name="author" content="محافظة كفر الشيخ" />

    {{--  ===== 3. Open Graph / Facebook & Twitter Card Meta Tags (for social sharing) ===== --}}
    <meta property="og:title" content="@yield('title', $settings['site_name'] ?? 'محافظة كفر الشيخ')" />
    <meta property="og:description" content="@yield('description', $settings['site_description'] ?? '...')" />
    <meta property="og:image" content="@yield('og_image', asset(Storage::url($settings['site_logo_header'] ?? '')))" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:locale" content="ar_EG" />
    <meta name="twitter:card" content="summary_large_image" />

    {{--  ===== 4. Favicon Set (Professional Setup) ===== --}}
    {{-- Make sure to place your favicon images in the public/images/favicons/ directory --}}
    <link rel="icon" href="{{ asset('favicon/favicon.ico') }}" sizes="any" />
    <link rel="apple-touch-icon" href="{{ asset('favicon/apple-touch-icon.png') }}" /> {{-- 180x180 px --}}

    {{--  ===== 5. Canonical URL & Theme Color ===== --}}
    <link rel="canonical" href="{{ url()->current() }}" />
    <meta name="theme-color" content="#DAA520">
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/bootstrap-5.0.2-dist') }}/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Google Fonts (Tajawal) -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"
        rel="stylesheet" />
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css') }}/style.css" />
    <link rel="stylesheet" href="{{ asset('css') }}/index.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('css')
</head>

<body>
    <div id="page-wrapper">
        @include('includes.header')

        @yield('content')

        @include('includes.footer')
    </div>
    {{-- START: Accessibility Tools Widget --}}
    {{-- <div class="accessibility-toolbar">
        <button class="toggle-button" aria-label="أدوات المساعدة لذوي الهمم">
            <i class="fas fa-universal-access"></i>
        </button>
        <div class="tools-menu">
            <button class="tool-btn" data-action="increase-font" aria-label="تكبير حجم الخط">A+</button>
            <button class="tool-btn" data-action="decrease-font" aria-label="تصغير حجم الخط">A-</button>
            <button class="tool-btn" data-action="high-contrast" aria-label="تغيير إلى وضع التباين العالي">
                <i class="fas fa-adjust"></i> <span class="tool-text">تباين الألوان</span>
            </button>
            <button class="tool-btn" data-action="grayscale" aria-label="تغيير إلى وضع الأبيض والأسود">
                <i class="fas fa-palette"></i> <span class="tool-text">تدرج رمادي</span>
            </button>
            <button class="tool-btn" data-action="reset" aria-label="إعادة تعيين الإعدادات">
                <i class="fas fa-undo"></i> <span class="tool-text">إعادة تعيين</span>
            </button>
        </div>
    </div> --}}
    {{-- END: Accessibility Tools Widget --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toolbar = document.querySelector('.accessibility-toolbar');
            const toggleBtn = toolbar.querySelector('.toggle-button');
            const body = document.body;

            // Toggle menu visibility
            toggleBtn.addEventListener('click', function() {
                toolbar.classList.toggle('active');
            });

            // Handle tool actions
            toolbar.querySelectorAll('.tool-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const action = this.dataset.action;
                    handleAction(action);
                });
            });

            function handleAction(action) {
                const pageWrapper = document.getElementById('page-wrapper');
                if (!pageWrapper) return;
                let currentFontSize = parseFloat(getComputedStyle(body).fontSize);

                switch (action) {
                    case 'increase-font':
                        if (currentFontSize < 22) { // Set a max font size
                            body.style.fontSize = (currentFontSize + 1) + 'px';
                        }
                        break;
                    case 'decrease-font':
                        if (currentFontSize > 14) { // Set a min font size
                            body.style.fontSize = (currentFontSize - 1) + 'px';
                        }
                        break;
                    case 'high-contrast':
                        // Apply class to the WRAPPER, not the body
                        pageWrapper.classList.toggle('high-contrast');
                        break;
                    case 'grayscale':
                        // Apply class to the WRAPPER, not the body
                        pageWrapper.classList.toggle('grayscale');
                        break;
                    case 'reset':
                        body.style.fontSize = '';
                        // Remove classes from the WRAPPER
                        pageWrapper.classList.remove('high-contrast', 'grayscale');
                        break;
                }
            }
        });
    </script> --}}
    {{-- START: Voice Assistant UI --}}
    {{-- <div id="voice-assistant">
        <button id="voice-assistant-btn" class="btn btn-primary btn-lg rounded-circle"
            aria-label="تفعيل المساعد الصوتي">
            <i class="fas fa-microphone"></i>
        </button>
        <div id="listening-indicator" class="listening-indicator visually-hidden">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div> --}}
    {{-- END: Voice Assistant UI --}}
    {{-- Voice Assistant Script --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. SETUP ---
            const voiceAssistantBtn = document.getElementById('voice-assistant-btn');
            const listeningIndicator = document.getElementById('listening-indicator');

            // Browser support check
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SpeechRecognition) {
                console.error("متصفحك لا يدعم الأوامر الصوتية.");
                if (voiceAssistantBtn) voiceAssistantBtn.style.display = 'none';
                return;
            }

            const recognition = new SpeechRecognition();
            const synth = window.speechSynthesis;

            // Recognition configuration
            recognition.lang = 'ar-EG';
            recognition.interimResults = false;
            recognition.continuous = false; // Process after user stops speaking

            // State variables
            let isListening = false;
            let conversationState = 'idle'; // idle, collecting_name, collecting_subject, ...
            let complaintData = {};

            // --- 2. COMMAND DICTIONARY ---
            // Combined navigation and conversation starter commands
            const commands = {
                // Navigation Commands
                'الصفحه الرئيسيه|اذهب الى الرئيسيه|عرض الرئيسيه': {
                    action: () => window.location.href = "{{ route('home') }}",
                    feedback: "جاري الانتقال إلى الصفحة الرئيسية"
                },
                'اذهب الى الاخبار|صفحه الاخبار|عرض الاخبار': {
                    action: () => window.location.href = "{{ route('posts.index') }}",
                    feedback: "جاري عرض صفحة الأخبار"
                },
                // ... Add all other navigation commands here ...

                // Scrolling Commands
                'التمرير للاسفل|انزل تحت': {
                    action: () => window.scrollBy({
                        top: window.innerHeight * 0.8,
                        behavior: 'smooth'
                    }),
                    feedback: "تم التمرير للأسفل"
                },
                'التمرير للاعلى|اطلع فوق': {
                    action: () => window.scrollBy({
                        top: -window.innerHeight * 0.8,
                        behavior: 'smooth'
                    }),
                    feedback: "تم التمرير للأعلى"
                },

                // Control Commands
                'ايقاف|توقف|شكرا': {
                    action: stopListening,
                    feedback: "تم إيقاف المساعد الصوتي"
                },

                // Conversation Starter
                'تقديم شكوى': {
                    action: startComplaintProcess,
                    feedback: "بالتأكيد. لنبدأ بتقديم الشكوى."
                },
            };

            // --- 3. CORE FUNCTIONS ---

            function speak(text, onEndCallback = null) {
                synth.cancel(); // Cancel any ongoing speech
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'ar-SA';
                utterance.rate = 1.1; // Slightly faster speech
                if (onEndCallback) {
                    utterance.onend = onEndCallback;
                }
                synth.speak(utterance);
            }

            function startListening() {
                if (!isListening) {
                    isListening = true;
                    listeningIndicator.classList.remove('visually-hidden');
                    recognition.start();
                    // Give initial feedback only when first activated.
                    if (conversationState === 'idle') {
                        speak("المساعد الصوتي يعمل. كيف يمكنني مساعدتك؟");
                    }
                }
            }

            function stopListening() {
                isListening = false;
                listeningIndicator.classList.add('visually-hidden');
                recognition.stop();
            }

            function processCommand(transcript) {
                // First check for a cancellation command that works everywhere
                if (transcript.includes('الغاء') || transcript.includes('تراجع')) {
                    cancelConversation();
                    speak("تم الإلغاء.");
                    return true; // Command was handled
                }

                // Check for general navigation/action commands
                for (const commandKey in commands) {
                    const commandOptions = commandKey.split('|');
                    for (const commandText of commandOptions) {
                        if (transcript.includes(commandText.trim())) {
                            const command = commands[commandKey];
                            speak(command.feedback);
                            setTimeout(command.action, command.feedback.length *
                                70); // Delay action based on feedback length
                            return true;
                        }
                    }
                }
                return false; // No command found
            }

            // --- 4. EVENT LISTENERS ---

            if (voiceAssistantBtn) {
                voiceAssistantBtn.addEventListener('click', () => {
                    isListening ? stopListening() : startListening();
                });
            }

            recognition.onresult = (event) => {
                const transcript = event.results[0][0].transcript.trim().toLowerCase();
                console.log('User said:', transcript);

                // If we are in a conversation, handle that first
                if (conversationState !== 'idle') {
                    handleConversationStep(transcript);
                } else {
                    // Otherwise, process general commands
                    if (!processCommand(transcript)) {
                        speak("عذرًا، لم أفهم الأمر. يرجى المحاولة مرة أخرى.");
                    }
                }
            };

            recognition.onend = () => {
                // If the assistant is supposed to be listening, restart it.
                if (isListening) {
                    recognition.start();
                }
            };

            recognition.onerror = (event) => {
                if (event.error !== 'no-speech') {
                    console.error('Speech recognition error:', event.error);
                    speak("حدث خطأ في التعرف على الصوت.");
                }
                // No need to stopListening here, 'onend' will handle the restart.
            };

            // --- 5. CONVERSATION LOGIC ---

            function startComplaintProcess() {
                complaintData = {};
                conversationState = 'collecting_name';
                speak("ما هو اسمك بالكامل؟", () => recognition.start()); // Ask then listen
            }

            function cancelConversation() {
                conversationState = 'idle';
                complaintData = {};
            }

            function handleConversationStep(transcript) {
                switch (conversationState) {
                    case 'collecting_name':
                        complaintData.name = transcript;
                        conversationState = 'collecting_subject';
                        speak(`حسنًا ${complaintData.name}. ما هو موضوع الشكوى؟`);
                        break;

                    case 'collecting_subject':
                        complaintData.subject = transcript;
                        conversationState = 'collecting_message';
                        speak("فهمت. الآن، يرجى شرح نص الشكوى بالتفصيل.");
                        break;

                    case 'collecting_message':
                        complaintData.message = transcript;
                        conversationState = 'confirming_submission';
                        speak("شكرًا لك. هل تؤكد إرسال الشكوى؟ قل 'نعم' للتأكيد أو 'الغاء' للمتابعة.");
                        break;

                    case 'confirming_submission':
                        if (transcript.includes('نعم')) {
                            submitComplaint();
                        } else {
                            cancelConversation();
                            speak("تم إلغاء إرسال الشكوى.");
                        }
                        break;
                }
            }

            async function submitComplaint() {
                speak("جاري إرسال شكواك. يرجى الانتظار.");
                cancelConversation(); // Reset state

                try {
                    const response = await fetch('/api/voice-complaint', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(complaintData)
                    });
                    const result = await response.json();
                    if (!response.ok) throw new Error(result.message);
                    speak(result.message);
                } catch (error) {
                    console.error('Submission failed:', error);
                    speak('عذرًا، حدث خطأ أثناء إرسال الشكوى.');
                }
            }
        });
    </script> --}}
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js') }}/script.js"></script>
    <script src="{{ asset('js') }}/index.js"></script>
    <script>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'ar',
                includedLanguages: 'ar,en,fr,de,es,zh-CN,ja,ko',
                autoDisplay: false
            }, 'google_translate_element');
        }

        function setLanguage(lang) {
            // حذف أي كوكيز قديمة
            document.cookie = "googtrans=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
            document.cookie = "googtrans=; path=/; domain=" + window.location.hostname +
                "; expires=Thu, 01 Jan 1970 00:00:00 UTC;";

            // تعيين اللغة الجديدة
            document.cookie = "googtrans=/ar/" + lang + "; path=/";
            document.cookie = "googtrans=/ar/" + lang + "; path=/; domain=" + window.location.hostname;

            location.reload();
        }
    </script>

    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script>
        function applyDirectionByLanguage() {
            const match = document.cookie.match(/googtrans=\/[^\/]+\/([^;]+)/);
            const lang = match ? match[1] : 'ar';
            const dateContainer = document.getElementById('top-bar');

            if (lang === 'ar') {
                document.documentElement.setAttribute('dir', 'rtl');
                document.documentElement.setAttribute('lang', 'ar');
                document.body.classList.add('rtl');
                document.body.classList.remove('ltr');
                dateContainer.style.display = 'block';
            } else {
                document.documentElement.setAttribute('dir', 'ltr');
                document.documentElement.setAttribute('lang', lang);
                document.body.classList.add('ltr');
                document.body.classList.remove('rtl');
                dateContainer.style.display = 'none';
            }
        }
        applyDirectionByLanguage();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.dropdown-submenu > a').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const submenu = this.nextElementSibling;
                    submenu.classList.toggle('show');
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchTrigger = document.getElementById('searchTrigger');
            const searchOverlay = document.getElementById('searchOverlay');
            const closeSearch = document.getElementById('closeSearch');
            const searchInput = document.getElementById('searchInput');

            // فتح السيرش
            if (searchTrigger) {
                searchTrigger.addEventListener('click', () => {
                    searchOverlay.classList.add('active');
                    // تأخير الفوكس قليلاً حتى ينتهي الأنيميشن
                    setTimeout(() => {
                        searchInput.focus();
                    }, 400);
                    document.body.style.overflow = 'hidden'; // منع السكرول خلف المودال
                });
            }

            // إغلاق السيرش بالضغط على الزر
            if (closeSearch) {
                closeSearch.addEventListener('click', () => {
                    searchOverlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                });
            }

            // إغلاق السيرش بزر Esc
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
                    searchOverlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.querySelector('header');

            // وظيفة مراقبة التمرير
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) { // إذا نزل المستخدم أكثر من 50 بيكسل
                    header.classList.add('header-scrolled');
                } else {
                    header.classList.remove('header-scrolled');
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
