<!-- ======================= Footer Start ======================= -->
<footer class="site-footer">
    <div class="container">
        <div class="row text-center text-md-end">
            <!-- About and Social Column -->
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0 text-center">
                <div class="footer-logo">
                    <img src="{{ Storage::url($settings['site_logo_footer']) }}" alt="{{ $settings['site_name'] ?? '' }}"
                        width="100" />
                </div>
                <p class="text-muted">الموقع الرسمي لمحافظة كفر الشيخ</p>
                <div class="footer-socials">
                    <a href="https://x.com/kfs_gov" aria-label="X"><i class="fab fa-x-twitter"></i></a>
                    <a href="https://www.facebook.com/KafrelsheikhGovernorate" aria-label="Facebook"><i
                            class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/kafr_elsheikh_gov" aria-label="Instagram"><i
                            class="fab fa-instagram"></i></a>
                    <a href="https://youtube.com/@kafrelsheikhgovernorate583" aria-label="YouTube"><i
                            class="fab fa-youtube"></i></a>
                    </a>

                    <a href="https://www.tiktok.com/@kfs_gov" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>

            <!-- Governorate Links -->
            <div class="col-lg-3 col-md-4 col-6 mb-4 mb-lg-0 text-end">
                <h5>عن المحافظة</h5>
                <ul class="list-unstyled footer-links-with-icons">
                    <li><a href="{{ route('posts.index') }}"><i class="fas fa-newspaper"></i> الأخبار</a></li>
                    <li><a href="{{ route('investments.index') }}"><i class="fas fa-chart-line"></i> الاستثمار</a></li>
                    <li><a href="{{ route('services.index') }}"><i class="fas fa-concierge-bell"></i> الخدمات</a></li>
                    <li><a href="{{ route('projects.index') }}"><i class="fas fa-building-flag"></i> المشروعات</a></li>
                </ul>
            </div>

            <!-- Important Sites -->
            <div class="col-lg-3 col-md-4 col-6 mb-4 mb-md-0 text-end">
                <h5>مواقع مهمة</h5>
                <ul class="list-unstyled footer-links-with-icons">
                    <li>
                        <a href="#"><i class="fas fa-desktop"></i> مصر الرقمية</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-briefcase"></i> بوابة الوظائف</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-file-signature"></i> بوابة التعاقدات</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-hand-holding-heart"></i> التضامن
                            الاجتماعي</a>
                    </li>
                </ul>
            </div>

            <!-- Other Services -->
            <div class="col-lg-3 col-md-4 d-none d-md-block">
                <h5>خدمات أخرى</h5>
                <ul class="list-unstyled footer-links-with-icons">
                    <li>
                        <a href="#"><i class="fas fa-city"></i> خدمات المحليات</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-landmark"></i> الشهر العقاري</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-train-subway"></i> سكك حديد مصر</a>
                    </li>
                </ul>
            </div>
        </div>
        <hr />
        <div class="copyright text-center">
            محافظة كفر الشيخ - جميع الحقوق محفوظة &copy;
            <span id="copyright-year"></span>
        </div>
    </div>
</footer>
<!-- ======================= Footer End ======================= -->
