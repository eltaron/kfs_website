@extends(getTemplate() . '.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/css/css-stars.css">
    <link rel="stylesheet" href="/assets/default/vendors/video/video-js.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        .course-content-section {
            padding-top: 50px;
            margin-bottom: 0px !important
        }

        .course-cover-container {
            max-height: 380px;
        }

        .course-cover-img,
        .course-cover-container,
        .cover-content:after {
            border-radius: 24px !important;
        }

        .course-title {
            min-height: 0 !important;
        }

        .course-cover-container .course-video-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 20px 12px 0 rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            width: 96px;
            height: 96px;
            border-radius: 50%;
            z-index: 2;
        }

        .course-video-icon svg {
            stroke: var(--secondary);
            fill: var(--secondary);
        }

        .course-cover-video {
            object-fit: cover;
            height: 100%;
            border-radius: 24px;
            /* Add rounding if needed */
        }

        .course-cover-container .cover-content:after {
            content: none;
        }

        .custom-video-container {
            position: relative;
            max-height: 500px;
            height: 100%;
            overflow: hidden;
        }

        .course-cover-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .custom-play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
            z-index: 10;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background-color 0.3s;
        }

        .custom-play-button i {
            font-size: 24px;
        }

        .custom-play-button:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }
    </style>
@endpush


@section('content')


    @php
        $percent = $course->getProgress();
        $shouldRedirectToMobileApp =
            session('error') &&
            !empty($securitySettings['device_restriction']) &&
            $securitySettings['device_restriction'] == '1';

        \Log::info('Course Details: ' . json_encode($category));
    @endphp


    <section
        class="container course-content-section mt-15 {{ $course->type }} {{ ($hasBought or $percent) ? 'has-progress-bar' : 'py-3' }}">

        <div class="row">
            <div class="col-12 col-lg-8  px-0">
                <section class="course-cover-container {{ empty($activeSpecialOffer) ? 'not-active-special-offer' : '' }} ">
                    @if ($course->video_demo)
                        <div class="custom-video-container">
                            <video id="courseVideo" class="course-cover-video w-100" poster="{{ $course->getImageCover() }}"
                                muted loop controls>
                                <source
                                    src="{{ $course->video_demo_source == 'upload' ? url($course->video_demo) : $course->video_demo }}"
                                    type="video/{{ pathinfo($course->video_demo, PATHINFO_EXTENSION) }}">
                                Your browser does not support the video tag.
                            </video>

                            <!-- Custom Play/Pause Button -->
                            <div id="customPlayPauseButton"
                                class="custom-play-button d-flex align-items-center justify-content-center">
                                <i class="fas fa-play"></i> <!-- Default to play icon -->
                            </div>
                        </div>
                    @else
                        <img src="{{ $course->getImageCover() }}" class="img-cover course-cover-img"
                            alt="{{ $course->title }}" />
                    @endif

                    <div class="cover-content pt-40  mt-3 rounded-sm">
                        <div class="container position-relative">
                            @if (!empty($activeSpecialOffer))
                                @include('web.default.course.special_offer')
                            @endif
                        </div>
                    </div>
                </section>


                <div class="course-content-body user-select-none mt-35 ">
                    <div class="course-body-on-cover text-white bg-white  p-3 rounded-sm"
                        style="border-bottom: 3px solid var(--primary);">
                        <h1 class="font-30 course-title text-secondary">
                            {{ $course->title }}
                        </h1>

                        @if (!empty($course->category))
                            <span class="d-block font-16 mt-10 text-secondary">{{ trans('public.in') }} <a
                                    href="{{ $course->category->getUrl() }}" target="_blank"
                                    class="text-secondary font-weight-500 text-decoration-underline text-white">{{ $course->category->title }}</a></span>
                        @endif

                        <div class="d-flex align-items-center">
                            @include('web.default.includes.webinar.rate', ['rate' => $course->getRate()])
                            <span
                                class=" text-secondary ml-10 mt-15 font-14 ">({{ $course->reviews->pluck('creator_id')->count() }}
                                {{ trans('public.ratings') }})</span>
                        </div>

                        <div class="mt-15">
                            <span class="font-14 text-secondary">{{ trans('public.created_by') }}</span>
                            <a href="{{ $course->teacher->getProfileUrl() }}" target="_blank"
                                class="text-secondary text-decoration-underline text-white font-14 font-weight-500">{{ $course->teacher->full_name }}</a>
                        </div>

                        @if ($hasBought or $percent)
                            <div class="mt-30 d-flex align-items-center">
                                <div class="progress course-progress flex-grow-1 shadow-xs rounded-sm">
                                    <span class="progress-bar rounded-sm bg-warning"
                                        style="width: {{ $percent }}%"></span>
                                </div>

                                <span class="ml-15 font-14 font-weight-500 text-secondary">
                                    @if ($hasBought and (!$course->isWebinar() or $course->isProgressing()))
                                        {{ trans('public.course_learning_passed', ['percent' => $percent]) }}
                                    @elseif(!is_null($course->capacity))
                                        {{ $course->getSalesCount() }}/{{ $course->capacity }}
                                        {{ trans('quiz.students') }}
                                    @else
                                        {{ trans('public.course_learning_passed', ['percent' => $percent]) }}
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>

                    @if (
                        !empty(getFeaturesSettings('frontend_coupons_display_type')) and
                            getFeaturesSettings('frontend_coupons_display_type') == 'before_content' and
                            !empty($instructorDiscounts) and
                            count($instructorDiscounts))
                        @foreach ($instructorDiscounts as $instructorDiscount)
                            @include('web.default.includes.discounts.instructor_discounts_card', [
                                'discount' => $instructorDiscount,
                                'instructorDiscountClassName' => 'mt-35',
                            ])
                        @endforeach
                    @endif

                    <div class="mt-35 bg-white  p-3 rounded-sm" style="border-bottom: 3px solid var(--primary);">
                        <ul class="nav nav-tabs bg-secondary rounded-sm p-15 d-flex align-items-center justify-content-between"
                            id="tabs-tab" role="tablist">
                            <li class="nav-item">
                                <a class="position-relative font-14 text-white {{ (empty(request()->get('tab', '')) or request()->get('tab', '') == 'information') ? 'active' : '' }}"
                                    id="information-tab" data-toggle="tab" href="#information" role="tab"
                                    aria-controls="information" aria-selected="true">{{ trans('product.information') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="position-relative font-14 text-white {{ request()->get('tab', '') == 'content' ? 'active' : '' }}"
                                    id="content-tab" data-toggle="tab" href="#content" role="tab"
                                    aria-controls="content" aria-selected="false">{{ trans('product.content') }}
                                    ({{ $webinarContentCount }})</a>
                            </li>
                            <li class="nav-item">
                                <a class="position-relative font-14 text-white {{ request()->get('tab', '') == 'reviews' ? 'active' : '' }}"
                                    id="reviews-tab" data-toggle="tab" href="#reviews" role="tab"
                                    aria-controls="reviews" aria-selected="false">{{ trans('product.reviews') }}
                                    ({{ $course->reviews->count() > 0 ? $course->reviews->pluck('creator_id')->count() : 0 }})</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade {{ (empty(request()->get('tab', '')) or request()->get('tab', '') == 'information') ? 'show active' : '' }} "
                                id="information" role="tabpanel" aria-labelledby="information-tab">
                                @include(getTemplate() . '.course.tabs.information')
                            </div>
                            <div class="tab-pane fade {{ request()->get('tab', '') == 'content' ? 'show active' : '' }}"
                                id="content" role="tabpanel" aria-labelledby="content-tab">
                                @include(getTemplate() . '.course.tabs.content')
                            </div>
                            <div class="tab-pane fade {{ request()->get('tab', '') == 'reviews' ? 'show active' : '' }}"
                                id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                @include(getTemplate() . '.course.tabs.reviews')
                            </div>
                        </div>

                    </div>


                    @if (
                        !empty(getFeaturesSettings('frontend_coupons_display_type')) and
                            getFeaturesSettings('frontend_coupons_display_type') == 'after_content' and
                            !empty($instructorDiscounts) and
                            count($instructorDiscounts))
                        @foreach ($instructorDiscounts as $instructorDiscount)
                            @include('web.default.includes.discounts.instructor_discounts_card', [
                                'discount' => $instructorDiscount,
                                'instructorDiscountClassName' => 'mt-35',
                            ])
                        @endforeach
                    @endif

                </div>
            </div>

            <div class="course-content-sidebar col-12 col-lg-4 mt-25 mt-lg-0 ">
                <div class="rounded-lg shadow-sm bg-white" style="border-bottom: 3px solid var(--primary);">

                    <div class="px-20 py-30">
                        <form action="/cart/store" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="item_id" value="{{ $course->id }}">
                            <input type="hidden" name="item_name" value="webinar_id">

                            @if (!empty($course->tickets))
                                @foreach ($course->tickets as $ticket)
                                    <div class="form-check mt-20">
                                        @php
                                            $currentDate = now(); // Get the current date
                                            $ticketStartDate = \Carbon\Carbon::parse($ticket->start_date); // Convert start_date to Carbon instance
                                            $ticketEndDate = \Carbon\Carbon::parse($ticket->end_date); // Convert end_date to Carbon instance
                                        @endphp

                                        @if ($ticketStartDate <= $currentDate && $ticketEndDate >= $currentDate)
                                            <!-- Check if current date is between start and end -->
                                            <input class="form-check-input"
                                                @if (!$ticket->isValid()) disabled @endif type="radio"
                                                data-discount-price="{{ handleCoursePagePrice($ticket->getPriceWithDiscount($course->getPrice()))['price'] }}"
                                                value="{{ $ticket->isValid() ? $ticket->id : '' }}" name="ticket_id"
                                                id="courseOff{{ $ticket->id }}">
                                            <label class="form-check-label d-flex flex-column cursor-pointer"
                                                for="courseOff{{ $ticket->id }}">
                                                <span class="font-16 font-weight-500 text-dark-blue">{{ $ticket->title }}
                                                    @if (!empty($ticket->discount))
                                                        ({{ $ticket->discount }}% {{ trans('public.off') }})
                                                    @endif
                                                </span>
                                                <span class="font-14 text-gray">{{ $ticket->getSubTitle() }}</span>
                                            </label>
                                        @endif
                                    </div>
                                @endforeach
                            @endif

                            @if ($course->price > 0)
                                <div id="priceBox"
                                    class="d-flex align-items-center justify-content-center {{ !empty($activeSpecialOffer) ? ' flex-column ' : '' }}">
                                    <div class="text-center">
                                        @php
                                            $realPrice = handleCoursePagePrice($course->price);
                                        @endphp
                                        <span id="realPrice" data-value="{{ $course->price }}"
                                            data-special-offer="{{ !empty($activeSpecialOffer) ? $activeSpecialOffer->percent : '' }}"
                                            class="d-block @if (!empty($activeSpecialOffer)) font-16 text-gray text-decoration-line-through @else font-30 text-primary @endif">
                                            {{ $realPrice['price'] }}
                                        </span>

                                        @if (!empty($realPrice['tax']) and empty($activeSpecialOffer))
                                            <span class="d-block font-14 text-gray">+ {{ $realPrice['tax'] }}
                                                {{ trans('cart.tax') }}</span>
                                        @endif
                                    </div>

                                    @if (!empty($activeSpecialOffer))
                                        <div class="text-center">
                                            @php
                                                $priceWithDiscount = handleCoursePagePrice($course->getPrice());
                                            @endphp
                                            <span id="priceWithDiscount" class="d-block font-30 text-primary">
                                                {{ $priceWithDiscount['price'] }}
                                            </span>

                                            @if (!empty($priceWithDiscount['tax']))
                                                <span class="d-block font-14 text-gray">+ {{ $priceWithDiscount['tax'] }}
                                                    {{ trans('cart.tax') }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="d-flex align-items-center justify-content-center mt-20">
                                    <span class="font-36 text-primary">{{ trans('public.free') }}</span>
                                </div>
                            @endif

                            @php
                                $canSale = ($course->canSale() and !$hasBought);
                                $authUserJoinedWaitlist = false;

                                if (!empty($authUser)) {
                                    $authUserWaitlist = $course->waitlists()->where('user_id', $authUser->id)->first();
                                    $authUserJoinedWaitlist = !empty($authUserWaitlist);
                                }
                            @endphp

                            <div class="mt-20 d-flex flex-column">
                                @if (!$canSale and $course->canJoinToWaitlist())
                                    <button type="button" data-slug="{{ $course->slug }}"
                                        class="btn btn-primary {{ !$authUserJoinedWaitlist ? (!empty($authUser) ? 'js-join-waitlist-user' : 'js-join-waitlist-guest') : 'disabled' }}"
                                        {{ $authUserJoinedWaitlist ? 'disabled' : '' }}>
                                        @if ($authUserJoinedWaitlist)
                                            {{ trans('update.already_joined') }}
                                        @else
                                            {{ trans('update.join_waitlist') }}
                                        @endif
                                    </button>
                                @elseif($hasBought or !empty($course->getInstallmentOrder()))
                                    <a href="" target="{{ session('error') ? '_self' : '_blank' }}"
                                        class="btn btn-primary">
                                        {{ trans('update.go_to_learning_page') }}
                                    </a>
                                @elseif(!empty($course->price) and $course->price > 0)
                                    <button type="button"
                                        class="btn btn-primary {{ $canSale ? 'js-course-add-to-cart-btn' : $course->cantSaleStatus($hasBought) . ' disabled ' }}">
                                        @if (!$canSale)
                                            @if ($course->checkCapacityReached())
                                                {{ trans('update.capacity_reached') }}
                                            @else
                                                {{ trans('update.disabled_add_to_cart') }}
                                            @endif
                                        @else
                                            {{ trans('public.add_to_cart') }}
                                        @endif
                                    </button>

                                    @if ($canSale and !empty($course->points))
                                        <a href="{{ !auth()->check() ? '/login' : '#' }}"
                                            class="{{ auth()->check() ? 'js-buy-with-point' : '' }} btn btn-outline-warning mt-20 {{ !$canSale ? 'disabled' : '' }}"
                                            rel="nofollow">
                                            {!! trans('update.buy_with_n_points', ['points' => $course->points]) !!}
                                        </a>
                                    @endif

                                    @if ($canSale and !empty(getFeaturesSettings('direct_classes_payment_button_status')))
                                        <button type="button"
                                            class="btn btn-outline-danger mt-20 js-course-direct-payment">
                                            {{ trans('update.buy_now') }}
                                        </button>
                                    @endif
                                    @if ($canSale)
                                        <button type="button" class="btn btn-outline-primary mt-20 js-course-qr-payment">
                                            {{ trans('update.buy_with_qr_code') }}
                                        </button>
                                    @endif

                                    @if (!empty($installments) and count($installments) and getInstallmentsSettings('display_installment_button'))
                                        <a href="/course/{{ $course->slug }}/installments"
                                            class="btn btn-outline-primary mt-20">
                                            {{ trans('update.pay_with_installments') }}
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ $canSale ? '/course/' . $course->slug . '/free' : '#' }}"
                                        class="btn btn-primary {{ !$canSale ? ' disabled ' . $course->cantSaleStatus($hasBought) : '' }}">
                                        @if (!$canSale)
                                            @if ($course->checkCapacityReached())
                                                {{ trans('update.capacity_reached') }}
                                            @else
                                                {{ trans('public.disabled') }}
                                            @endif
                                        @else
                                            {{ trans('public.enroll_on_webinar') }}
                                        @endif
                                    </a>
                                @endif

                                @if ($canSale and $course->subscribe)
                                    <a href="/subscribes/apply/{{ $course->slug }}"
                                        class="btn btn-outline-primary btn-subscribe mt-20 @if (!$canSale) disabled @endif">{{ trans('public.subscribe') }}</a>
                                @endif

                            </div>

                        </form>
                        @php
                            // Log the start of the process
                            Log::info('Getting the latest sale for the course.', ['course_id' => $course->id]);

                            $latestSale = null;

                            if (auth()->check()) {
                                $latestSale = auth()->user()->getLatestSaleForWebinar($course->id);
                            }
                            // Log the result of the sale query
                            Log::info('Latest sale for the course:', ['latest_sale' => $latestSale]);

                            // Check if the latest sale exists and calculate the days since the sale
                            if ($latestSale && $latestSale->created_at) {
                                // Ensure created_at is a Carbon instance
                                $saleDate = \Carbon\Carbon::parse($latestSale->created_at);

                                // Log the parsed sale date
                                Log::info('Parsed sale date:', ['sale_date' => $saleDate]);

                                $daysSinceSale = now()->diffInDays($saleDate);

                                // Log the number of days since the sale
                                Log::info('Days since the sale:', ['days_since_sale' => $daysSinceSale]);

                                $remainingMoneyBackDays = max(0, $course->money_back_days - $daysSinceSale);

                                // Log the remaining money back days
                                Log::info('Remaining money back days:', [
                                    'remaining_money_back_days' => $remainingMoneyBackDays,
                                ]);
                            } else {
                                // No sale found, show full money-back days
                                $remainingMoneyBackDays = $course->money_back_days;

                                // Log that no sale was found
                                Log::info('No sale found. Using full money-back days.', [
                                    'full_money_back_days' => $remainingMoneyBackDays,
                                ]);
                            }
                        @endphp

                        @if (
                            $hasBought and
                                !empty($remainingMoneyBackDays) &&
                                    $remainingMoneyBackDays >= 0 &&
                                    !empty(getOthersPersonalizationSettings('show_guarantee_text')) &&
                                    getOthersPersonalizationSettings('show_guarantee_text'))
                            <div class="mt-20 d-flex align-items-center justify-content-center text-gray hover-clickable"
                                onclick="submitRefundRequest()"
                                style="cursor: pointer; transition: color 0.3s, transform 0.2s;">
                                <i data-feather="thumbs-up" width="20" height="20"></i>
                                <span class="ml-5 font-14">
                                    {{ trans('product.guarantee_text', ['days' => $remainingMoneyBackDays]) }}
                                </span>
                            </div>
                        @endif



                        <div class="mt-35">
                            <strong
                                class="d-block text-secondary font-weight-bold">{{ trans('webinars.this_webinar_includes', ['classes' => trans('webinars.' . $course->type)]) }}</strong>
                            @if ($course->isDownloadable())
                                <div class="mt-20 d-flex align-items-center text-gray">
                                    <i data-feather="download-cloud" width="20" height="20"></i>
                                    <span
                                        class="ml-5 font-14 font-weight-500">{{ trans('webinars.downloadable_content') }}</span>
                                </div>
                            @endif

                            @if ($course->certificate or $course->quizzes->where('certificate', 1)->count() > 0)
                                <div class="mt-20 d-flex align-items-center text-gray">
                                    <i data-feather="award" width="20" height="20"></i>
                                    <span
                                        class="ml-5 font-14 font-weight-500">{{ trans('webinars.official_certificate') }}</span>
                                </div>
                            @endif

                            @if ($course->quizzes->where('status', \App\models\Quiz::ACTIVE)->count() > 0)
                                <div class="mt-20 d-flex align-items-center text-gray">
                                    <i data-feather="file-text" width="20" height="20"></i>
                                    <span
                                        class="ml-5 font-14 font-weight-500">{{ trans('webinars.online_quizzes_count', ['quiz_count' => $course->quizzes->where('status', \App\models\Quiz::ACTIVE)->count()]) }}</span>
                                </div>
                            @endif

                            @if ($course->support)
                                <div class="mt-20 d-flex align-items-center text-gray">
                                    <i data-feather="headphones" width="20" height="20"></i>
                                    <span
                                        class="ml-5 font-14 font-weight-500">{{ trans('webinars.instructor_support') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="mt-40 p-10 rounded-sm border row align-items-center favorites-share-box">
                            @if ($course->isWebinar())
                                <div class="col">
                                    <a href="{{ $course->addToCalendarLink() }}" target="_blank"
                                        class="d-flex flex-column align-items-center text-center text-gray">
                                        <i data-feather="calendar" width="20" height="20"></i>
                                        <span class="font-12">{{ trans('public.reminder') }}</span>
                                    </a>
                                </div>
                            @endif

                            <div class="col">
                                <a href="/favorites/{{ $course->slug }}/toggle" id="favoriteToggle"
                                    class="d-flex flex-column align-items-center text-gray">
                                    <i data-feather="heart" class="{{ !empty($isFavorite) ? 'favorite-active' : '' }}"
                                        width="20" height="20"></i>
                                    <span class="font-12">{{ trans('panel.favorite') }}</span>
                                </a>
                            </div>

                            <div class="col">
                                <a href="#" class="js-share-course d-flex flex-column align-items-center text-gray">
                                    <i data-feather="share-2" width="20" height="20"></i>
                                    <span class="font-12">{{ trans('public.share') }}</span>
                                </a>
                            </div>
                        </div>

                        <div class="mt-30 text-center">
                            <button type="button" id="webinarReportBtn"
                                class="font-14 text-gray btn-transparent">{{ trans('webinars.report_this_webinar') }}</button>
                        </div>
                    </div>
                </div>

                {{-- Cashback Alert --}}
                @include('web.default.includes.cashback_alert', ['itemPrice' => $course->price])

                {{-- Gift Card --}}
                @if (
                    $course->canSale() and
                        !empty(getGiftsGeneralSettings('status')) and
                        !empty(getGiftsGeneralSettings('allow_sending_gift_for_courses')))
                    <a href="/gift/course/{{ $course->slug }}"
                        class="d-flex align-items-center mt-30 rounded-lg border p-15">
                        <div class="size-40 d-flex-center rounded-circle bg-gray200">
                            <i data-feather="gift" class="text-gray" width="20" height="20"></i>
                        </div>
                        <div class="ml-5">
                            <h4 class="font-14 font-weight-bold text-gray">{{ trans('update.gift_this_course') }}</h4>
                            <p class="font-12 text-gray">{{ trans('update.gift_this_course_hint') }}</p>
                        </div>
                    </a>
                @endif

                @if ($course->teacher->offline)
                    <div class="rounded-lg shadow-sm mt-35 d-flex bg-white">
                        <div class="offline-icon offline-icon-left d-flex align-items-stretch">
                            <div class="d-flex align-items-center">
                                <img src="/assets/default/img/profile/time-icon.png" alt="offline">
                            </div>
                        </div>

                        <div class="p-15">
                            <h3 class="font-16 text-dark-blue">{{ trans('public.instructor_is_not_available') }}</h3>
                            <p class="font-14 font-weight-500 text-gray mt-15">{{ $course->teacher->offline_message }}</p>
                        </div>
                    </div>
                @endif

                <div class="rounded-lg shadow-sm mt-35 px-25 py-20 bg-white"
                    style="border-bottom: 3px solid var(--primary);">
                    <h3 class="sidebar-title font-16 text-secondary font-weight-bold">
                        {{ trans('webinars.' . $course->type) . ' ' . trans('webinars.specifications') }}</h3>

                    <div class="mt-30">
                        @if ($course->isWebinar())
                            <div class="mt-20 d-flex align-items-center justify-content-between text-gray">
                                <div class="d-flex align-items-center">
                                    <i data-feather="calendar" width="20" height="20"></i>
                                    <span class="ml-5 font-14 font-weight-500">{{ trans('public.start_date') }}:</span>
                                </div>
                                <span class="font-14">{{ dateTimeFormat($course->start_date, 'j M Y | H:i') }}</span>
                            </div>
                        @endif

                        <div class="mt-20 d-flex align-items-center justify-content-between text-gray">
                            <div class="d-flex align-items-center">
                                <i data-feather="user" width="20" height="20"></i>
                                <span class="ml-5 font-14 font-weight-500">{{ trans('public.capacity') }}:</span>
                            </div>
                            @if (!is_null($course->capacity))
                                <span class="font-14">{{ $course->capacity }} {{ trans('quiz.students') }}</span>
                            @else
                                <span class="font-14">{{ trans('update.unlimited') }}</span>
                            @endif
                        </div>

                        <div class="mt-20 d-flex align-items-center justify-content-between text-gray">
                            <div class="d-flex align-items-center">
                                <i data-feather="clock" width="20" height="20"></i>
                                <span class="ml-5 font-14 font-weight-500">{{ trans('public.duration') }}:</span>
                            </div>
                            <span
                                class="font-14">{{ convertMinutesToHourAndMinute(!empty($course->duration) ? $course->duration : 0) }}
                                {{ trans('home.hours') }}</span>
                        </div>

                        <div class="mt-20 d-flex align-items-center justify-content-between text-gray">
                            <div class="d-flex align-items-center">
                                <i data-feather="users" width="20" height="20"></i>
                                <span class="ml-5 font-14 font-weight-500">{{ trans('quiz.students') }}:</span>
                            </div>
                            <span class="font-14">{{ $course->getSalesCount() + $course->fake_counter }}</span>
                        </div>

                        @if ($course->isWebinar())
                            <div class="mt-20 d-flex align-items-center justify-content-between text-gray">
                                <div class="d-flex align-items-center">
                                    <img src="/assets/default/img/icons/sessions.svg" width="20" alt="">
                                    <span class="ml-5 font-14 font-weight-500">{{ trans('public.sessions') }}:</span>
                                </div>
                                <span class="font-14">{{ $course->sessions->count() }}</span>
                            </div>
                        @endif

                        @if ($course->isTextCourse())
                            <div class="mt-20 d-flex align-items-center justify-content-between text-gray">
                                <div class="d-flex align-items-center">
                                    <img src="/assets/default/img/icons/sessions.svg" width="20" alt="">
                                    <span
                                        class="ml-5 font-14 font-weight-500">{{ trans('webinars.text_lessons') }}:</span>
                                </div>
                                <span class="font-14">{{ $course->textLessons->count() }}</span>
                            </div>
                        @endif

                        @if ($course->isCourse() or $course->isTextCourse())
                            <div class="mt-20 d-flex align-items-center justify-content-between text-gray">
                                <div class="d-flex align-items-center">
                                    <img src="/assets/default/img/icons/sessions.svg" width="20" alt="">
                                    <span class="ml-5 font-14 font-weight-500">{{ trans('public.files') }}:</span>
                                </div>
                                <span class="font-14">{{ $course->files->count() }}</span>
                            </div>

                            <div class="mt-20 d-flex align-items-center justify-content-between text-gray">
                                <div class="d-flex align-items-center">
                                    <img src="/assets/default/img/icons/sessions.svg" width="20" alt="">
                                    <span class="ml-5 font-14 font-weight-500">{{ trans('public.created_at') }}:</span>
                                </div>
                                <span class="font-14">{{ dateTimeFormat($course->created_at, 'j M Y') }}</span>
                            </div>
                        @endif

                        @if (!empty($course->access_days))
                            <div class="mt-20 d-flex align-items-center justify-content-between text-gray">
                                <div class="d-flex align-items-center">
                                    <i data-feather="alert-circle" width="20" height="20"></i>
                                    <span
                                        class="ml-5 font-14 font-weight-500">{{ trans('update.access_period') }}:</span>
                                </div>
                                <span class="font-14">{{ $course->access_days }} {{ trans('public.days') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- organization --}}
                @if ($course->creator_id != $course->teacher_id)
                    @include('web.default.course.sidebar_instructor_profile', [
                        'courseTeacher' => $course->creator,
                    ])
                @endif
                {{-- teacher --}}
                @include('web.default.course.sidebar_instructor_profile', [
                    'courseTeacher' => $course->teacher,
                ])

                @if ($course->webinarPartnerTeacher->count() > 0)
                    @foreach ($course->webinarPartnerTeacher as $webinarPartnerTeacher)
                        @include('web.default.course.sidebar_instructor_profile', [
                            'courseTeacher' => $webinarPartnerTeacher->teacher,
                        ])
                    @endforeach
                @endif
                {{-- ./ teacher --}}

                {{-- tags --}}
                @if ($course->tags->count() > 0)
                    <div class="rounded-lg tags-card shadow-sm mt-35 px-25 py-20 bg-white">
                        <h3 class="sidebar-title font-16 text-secondary font-weight-bold">{{ trans('public.tags') }}</h3>

                        <div class="d-flex flex-wrap mt-10">
                            @foreach ($course->tags as $tag)
                                <a href="/tags/courses/{{ urlencode($tag->title) }}"
                                    class="tag-item bg-gray200 p-5 font-14 text-gray font-weight-500 rounded">{{ $tag->title }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
                {{-- ads --}}
                @if (!empty($advertisingBannersSidebar) and count($advertisingBannersSidebar))
                    <div class="row">
                        @foreach ($advertisingBannersSidebar as $sidebarBanner)
                            <div class="rounded-lg sidebar-ads mt-35 col-{{ $sidebarBanner->size }} ">
                                <a href="{{ $sidebarBanner->link }}">
                                    <img src="{{ $sidebarBanner->image }}" class="img-cover rounded-lg"
                                        alt="{{ $sidebarBanner->title }}">
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Ads Bannaer --}}
        @if (!empty($advertisingBanners) and count($advertisingBanners))
            <div class="mt-30 mt-md-50">
                <div class="row">
                    @foreach ($advertisingBanners as $banner)
                        <div class="col-{{ $banner->size }}">
                            <a href="{{ $banner->link }}">
                                <img src="{{ $banner->image }}" class="img-cover rounded-sm"
                                    alt="{{ $banner->title }}">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        {{-- ./ Ads Bannaer --}}
    </section>

    <div id="webinarReportModal" class="d-none">
        <h3 class="section-title after-line font-20 text-dark-blue">{{ trans('product.report_the_course') }}</h3>

        <form action="/course/{{ $course->id }}/report" method="post" class="mt-25">

            <div class="form-group">
                <label class="text-dark-blue font-14">{{ trans('product.reason') }}</label>
                <select id="reason" name="reason" class="form-control">
                    <option value="" selected disabled>{{ trans('product.select_reason') }}</option>

                    @foreach (getReportReasons() as $reason)
                        <option value="{{ $reason }}">{{ $reason }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="text-dark-blue font-14"
                    for="message_to_reviewer">{{ trans('public.message_to_reviewer') }}</label>
                <textarea name="message" id="message_to_reviewer" class="form-control" rows="10"></textarea>
                <div class="invalid-feedback"></div>
            </div>
            <p class="text-gray font-16">{{ trans('product.report_modal_hint') }}</p>

            <div class="mt-30 d-flex align-items-center justify-content-end">
                <button type="button"
                    class="js-course-report-submit btn btn-sm btn-primary">{{ trans('panel.report') }}</button>
                <button type="button"
                    class="btn btn-sm btn-danger ml-10 close-swl">{{ trans('public.close') }}</button>
            </div>
        </form>
    </div>

    @include('web.default.course.share_modal')
    @include('web.default.course.buy_with_point_modal')
@endsection

@push('scripts_bottom')
    <script src="/assets/default/js/parts/time-counter-down.min.js"></script>
    <script src="/assets/default/vendors/barrating/jquery.barrating.min.js"></script>
    <script src="/assets/default/vendors/video/video.min.js"></script>
    <script src="/assets/default/vendors/video/youtube.min.js"></script>
    <script src="/assets/default/vendors/video/vimeo.js"></script>

    <script>
        var webinarDemoLang = '{{ trans('webinars.webinar_demo') }}';
        var replyLang = '{{ trans('panel.reply') }}';
        var closeLang = '{{ trans('public.close') }}';
        var saveLang = '{{ trans('public.save') }}';
        var reportLang = '{{ trans('panel.report') }}';
        var reportSuccessLang = '{{ trans('panel.report_success') }}';
        var reportFailLang = '{{ trans('panel.report_fail') }}';
        var messageToReviewerLang = '{{ trans('public.message_to_reviewer') }}';
        var copyLang = '{{ trans('public.copy') }}';
        var copiedLang = '{{ trans('public.copied') }}';
        var learningToggleLangSuccess = '{{ trans('public.course_learning_change_status_success') }}';
        var learningToggleLangError = '{{ trans('public.course_learning_change_status_error') }}';
        var notLoginToastTitleLang = '{{ trans('public.not_login_toast_lang') }}';
        var notLoginToastMsgLang = '{{ trans('public.not_login_toast_msg_lang') }}';
        var notAccessToastTitleLang = '{{ trans('public.not_access_toast_lang') }}';
        var notAccessToastMsgLang = '{{ trans('public.not_access_toast_msg_lang') }}';
        var canNotTryAgainQuizToastTitleLang = '{{ trans('public.can_not_try_again_quiz_toast_lang') }}';
        var canNotTryAgainQuizToastMsgLang = '{{ trans('public.can_not_try_again_quiz_toast_msg_lang') }}';
        var canNotDownloadCertificateToastTitleLang = '{{ trans('public.can_not_download_certificate_toast_lang') }}';
        var canNotDownloadCertificateToastMsgLang = '{{ trans('public.can_not_download_certificate_toast_msg_lang') }}';
        var sessionFinishedToastTitleLang = '{{ trans('public.session_finished_toast_title_lang') }}';
        var sessionFinishedToastMsgLang = '{{ trans('public.session_finished_toast_msg_lang') }}';
        var sequenceContentErrorModalTitle = '{{ trans('update.sequence_content_error_modal_title') }}';
        var courseHasBoughtStatusToastTitleLang = '{{ trans('cart.fail_purchase') }}';
        var courseHasBoughtStatusToastMsgLang = '{{ trans('site.you_bought_webinar') }}';
        var courseNotCapacityStatusToastTitleLang = '{{ trans('public.request_failed') }}';
        var courseNotCapacityStatusToastMsgLang = '{{ trans('cart.course_not_capacity') }}';
        var courseHasStartedStatusToastTitleLang = '{{ trans('cart.fail_purchase') }}';
        var courseHasStartedStatusToastMsgLang = '{{ trans('update.class_has_started') }}';
        var joinCourseWaitlistLang = '{{ trans('update.join_course_waitlist') }}';
        var joinCourseWaitlistModalHintLang = "{{ trans('update.join_course_waitlist_modal_hint') }}";
        var joinLang = '{{ trans('footer.join') }}';
        var nameLang = '{{ trans('auth.name') }}';
        var emailLang = '{{ trans('auth.email') }}';
        var phoneLang = '{{ trans('public.phone') }}';
        var captchaLang = '{{ trans('site.captcha') }}';
    </script>
    <script>
        $(document).on('click', '.js-course-qr-payment', function(e) {
            e.preventDefault();
            console.log('QR Code Payment Process Started');

            Swal.fire({
                title: '{{ trans('update.qr_code_purchase') }}',
                html: ` 
            <div id="qr-reader" style="width: 100%;"></div>
            <input type="text" id="manual-qr-code" class="swal2-input" placeholder="{{ trans('update.enter_qr_code') }}">
        `,
                showCancelButton: true,
                confirmButtonText: '{{ trans('update.confirm_purchase') }}',
                cancelButtonText: '{{ trans('admin/main.cancel') }}',
                didOpen: () => {
                    console.log('QR Code Reader Initialized');
                    const html5QrCode = new Html5Qrcode("qr-reader");

                    // Start QR code scanning
                    html5QrCode.start({
                            facingMode: "environment"
                        }, // Use rear camera
                        {
                            fps: 10,
                            qrbox: {
                                width: 250,
                                height: 250
                            }
                        },
                        qrCodeMessage => {
                            console.log('QR Code Detected: ', qrCodeMessage);
                            // Call the first API to get the QR code data
                            fetchQrCodeData(qrCodeMessage);
                            html5QrCode.stop();
                            Swal.update({
                                html: `
                            <p>{{ trans('update.qr_code_detected') }}</p>
                            <input type="text" id="manual-qr-code" class="swal2-input" value="${qrCodeMessage}" readonly>
                        `
                            });
                        },
                        errorMessage => {
                            console.log('{{ trans('update.scanning_error') }}: ', errorMessage);
                        }
                    ).catch(err => {
                        console.error('{{ trans('update.camera_error') }}: ', err);
                    });
                },
                preConfirm: () => {
                    const manualCode = document.getElementById('manual-qr-code').value.trim();
                    console.log('Manual QR Code: ', manualCode);

                    if (!manualCode) {
                        Swal.showValidationMessage('{{ trans('update.qr_code_required') }}');
                        return false;
                    }

                    // Call the first API to fetch the QR code data
                    fetchQrCodeData(manualCode);

                    return false; // Prevent form submission, as we handle the second API after validation
                }
            });

            // Fetch QR code data from the first API
            function fetchQrCodeData(qrCode) {
                console.log('Fetching QR Code Data from API...');

                return fetch('/api/development/qrcode/show', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'x-api-key': '{{ env('API_KEY') }}' // Get the API key from the .env file
                        },
                        body: JSON.stringify({
                            code: qrCode,
                            user_id: '{{ auth()->id() }}'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('QR Code Data Response: ', data);

                        if (data.success) {
                            // Validate QR Code before proceeding with the purchase
                            const qrData = data.data;

                            // Check if the QR code is expired
                            const expirationDate = new Date(qrData.expiration_date);
                            const currentDate = new Date();
                            if (currentDate > expirationDate) {
                                Swal.fire('{{ trans('update.failed') }}',
                                    '{{ trans('update.qr_code_expired') }}', 'error');
                                return;
                            }

                            // Check if the QR code has already been used
                            if (qrData.is_used) {
                                Swal.fire('{{ trans('update.failed') }}',
                                    '{{ trans('update.qr_code_used') }}', 'error');
                                return;
                            }

                            // Check if bundle_id in the request is different from the one in the QR code
                            if (qrData.webinar_ids !== '{{ $course->id }}' && qrData.webinar_ids !== null) {
                                Swal.fire('{{ trans('update.failed') }}',
                                    '{{ trans('update.bundle_mismatch') }}', 'error');
                                return;
                            }
                            // Check if category_id in the QR code is different from the course's category_id
                            if (qrData.category_id != '{{ $category }}' && qrData.category_id !== null) {
                                Swal.fire('{{ trans('update.failed') }}',
                                    '{{ trans('update.category_mismatch') }}', 'error');
                                return;
                            }

                            // Show success message (but do not display the QR code data to the user)
                            Swal.fire({
                                title: '{{ trans('update.success') }}',
                                text: '{{ trans('update.valid_qr_code') }}',
                                icon: 'success',
                                confirmButtonText: '{{ trans('update.proceed_with_purchase') }}',
                                preConfirm: () => {
                                    console.log('Proceeding with purchase...');
                                    // Call the second API to process the purchase
                                    return fetchPurchase(qrCode, qrData);
                                }
                            });
                        } else {
                            Swal.fire('{{ trans('update.failed') }}',
                                '{{ trans('update.invalid_qr_code') }}', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching QR code data: ', error);
                        Swal.fire('{{ trans('update.failed') }}', '{{ trans('update.network_error') }}',
                            'error');
                    });
            }

            // Fetch purchase API to complete the transaction
            function fetchPurchase(qrCode, qrData) {
                console.log('Processing Purchase for QR Code: ', qrCode);

                return fetch('/api/development/qrcode', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'x-api-key': '{{ env('API_KEY') }}' // Get the API key from the .env file
                        },
                        body: JSON.stringify({
                            code: qrCode,
                            user_id: '{{ auth()->id() }}',
                            course_id: '{{ $course->id }}', // Fallback to bundle_id if it's not available in the QR data
                        })
                    })
                    .then(response => response.json())
                    .then(result => {
                        console.log('Purchase Response: ', result);

                        if (result.success) {
                            // Reload the page after success with a 1-second delay
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                            Swal.fire('{{ trans('update.success') }}',
                                '{{ trans('update.purchase_success') }}', 'success');
                        } else {
                            Swal.fire('{{ trans('update.failed') }}',
                                '{{ trans('update.purchase_failed') }}', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error processing purchase: ', error);
                        Swal.fire('{{ trans('update.failed') }}', '{{ trans('update.network_error') }}',
                            'error');
                    });
            }
        });
    </script>
    <script src="/assets/default/js/parts/comment.min.js"></script>
    <script src="/assets/default/js/parts/video_player_helpers.min.js"></script>
    <script src="/assets/default/js/parts/webinar_show.min.js"></script>

    <script>
        function submitRefundRequest() {
            console.log('Opening confirmation dialog...');

            Swal.fire({
                title: '{{ trans('product.confirmation_title') }}', // Translated title
                text: '{{ trans('product.confirmation_text') }}', // Translated text
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ trans('product.confirm_button') }}', // Translated confirm button
                cancelButtonText: '{{ trans('product.cancel_button') }}' // Translated cancel button
            }).then((result) => {
                console.log('Dialog result:', result);

                if (result.isConfirmed) {
                    console.log('User confirmed. Preparing to submit the refund request...');

                    // Show loading indicator
                    Swal.fire({
                        title: '{{ trans('product.loading_title') }}',
                        text: '{{ trans('product.loading_text') }}',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit the request via AJAX
                    fetch('/panel/support/refund-request', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            title: '{{ trans('product.request_title') }}', // Translated request title
                            type: 'platform_support',
                            department_id: 2, // Adjust based on your logic
                            webinar_id: {{ $course->id ?? 'null' }},
                            message: '{{ trans('product.request_message') }}' // Translated message
                        })
                    }).then(response => {
                        console.log('Response received:', response);
                        return response.json();
                    }).then(data => {
                        console.log('Parsed response data:', data);

                        if (data.success) {
                            console.log('Refund request submitted successfully.');
                            Swal.fire('{{ trans('product.success_title') }}',
                                '{{ trans('product.success_text') }}', 'success');
                        } else {
                            console.error('Refund request submission failed:', data);
                            Swal.fire('{{ trans('product.error_title') }}',
                                '{{ trans('product.error_text') }}', 'error');
                        }
                    }).catch(error => {
                        console.error('Error occurred while submitting the refund request:', error);
                        Swal.fire('{{ trans('product.error_title') }}',
                            '{{ trans('product.error_text') }}', 'error');
                    });
                } else {
                    console.log('User canceled the refund request.');
                }
            });
        }
        document.addEventListener("DOMContentLoaded", function() {
            const video = document.getElementById("courseVideo");
            const playPauseButton = document.getElementById("customPlayPauseButton");
            const icon = playPauseButton.querySelector("i");

            // Handle Play/Pause toggle
            playPauseButton.addEventListener("click", () => {
                if (video.paused) {
                    video.play();
                    icon.classList.remove("fa-play");
                    icon.classList.add("fa-pause");
                } else {
                    video.pause();
                    icon.classList.remove("fa-pause");
                    icon.classList.add("fa-play");
                }
            });

            // Sync button icon with video state
            video.addEventListener("pause", () => {
                icon.classList.remove("fa-pause");
                icon.classList.add("fa-play");
            });

            video.addEventListener("play", () => {
                icon.classList.remove("fa-play");
                icon.classList.add("fa-pause");
            });
        });
    </script>


    @if (
        !empty($course->creator) and
            !empty($course->creator->getLiveChatJsCode()) and
            !empty(getFeaturesSettings('show_live_chat_widget')))
        <script>
            (function() {
                "use strict"

                {!! $course->creator->getLiveChatJsCode() !!}
            })(jQuery)
        </script>
    @endif
@endpush
