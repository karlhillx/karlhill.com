<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="uFxkTB0m6tASRk0RkemBMFIyjR5TpGH5Qrmhpka_QfY"/>
    <meta name="laravel-version" content="Laravel v{{ app()->version() }}">
    <title>{{ config('app.name', 'Karl Hill | Full Stack Engineer') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;900&display=swap">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <style>
        /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */
        html {
            line-height: 1.15;
            -webkit-text-size-adjust: 100%
        }

        body {
            margin: 0
        }

        a {
            background-color: transparent
        }

        [hidden] {
            display: none
        }

        html {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
            line-height: 1.5
        }

        *, :after, :before {
            box-sizing: border-box;
            border: 0 solid #e2e8f0
        }

        a {
            color: inherit;
            text-decoration: inherit
        }

        svg, video {
            display: block;
            vertical-align: middle
        }

        video {
            max-width: 100%;
            height: auto
        }

        .bg-white {
            --bg-opacity: 1;
            background-color: #fff;
            background-color: rgba(255, 255, 255, var(--bg-opacity))
        }

        .bg-gray-100 {
            --bg-opacity: 1;
            background-color: #f7fafc;
            background-color: rgba(247, 250, 252, var(--bg-opacity))
        }

        .border-gray-200 {
            --border-opacity: 1;
            border-color: #edf2f7;
            border-color: rgba(237, 242, 247, var(--border-opacity))
        }

        .border-t {
            border-top-width: 1px
        }

        .flex {
            display: flex
        }

        .grid {
            display: grid
        }

        .hidden {
            display: none
        }

        .items-center {
            align-items: center
        }

        .justify-center {
            justify-content: center
        }

        .font-semibold {
            font-weight: 600
        }

        .h-5 {
            height: 1.25rem
        }

        .h-8 {
            height: 2rem
        }

        .h-16 {
            height: 4rem
        }

        .text-sm {
            font-size: .875rem
        }

        .text-lg {
            font-size: 1.125rem
        }

        .leading-7 {
            line-height: 1.75rem
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto
        }

        .ml-1 {
            margin-left: .25rem
        }

        .mt-2 {
            margin-top: .5rem
        }

        .mr-2 {
            margin-right: .5rem
        }

        .ml-2 {
            margin-left: .5rem
        }

        .mt-4 {
            margin-top: 1rem
        }

        .ml-4 {
            margin-left: 1rem
        }

        .mt-8 {
            margin-top: 2rem
        }

        .ml-12 {
            margin-left: 3rem
        }

        .-mt-px {
            margin-top: -1px
        }

        .max-w-6xl {
            max-width: 72rem
        }

        .min-h-screen {
            min-height: 100vh
        }

        .overflow-hidden {
            overflow: hidden
        }

        .p-6 {
            padding: 1.5rem
        }

        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem
        }

        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem
        }

        .pt-8 {
            padding-top: 2rem
        }

        .fixed {
            position: fixed
        }

        .relative {
            position: relative
        }

        .top-0 {
            top: 0
        }

        .right-0 {
            right: 0
        }

        .shadow {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06)
        }

        .text-center {
            text-align: center
        }

        .text-gray-200 {
            --text-opacity: 1;
            color: #edf2f7;
            color: rgba(237, 242, 247, var(--text-opacity))
        }

        .text-gray-300 {
            --text-opacity: 1;
            color: #e2e8f0;
            color: rgba(226, 232, 240, var(--text-opacity))
        }

        .text-gray-400 {
            --text-opacity: 1;
            color: #cbd5e0;
            color: rgba(203, 213, 224, var(--text-opacity))
        }

        .text-gray-500 {
            --text-opacity: 1;
            color: #a0aec0;
            color: rgba(160, 174, 192, var(--text-opacity))
        }

        .text-gray-600 {
            --text-opacity: 1;
            color: #718096;
            color: rgba(113, 128, 150, var(--text-opacity))
        }

        .text-gray-700 {
            --text-opacity: 1;
            color: #4a5568;
            color: rgba(74, 85, 104, var(--text-opacity))
        }

        .text-gray-900 {
            --text-opacity: 1;
            color: #1a202c;
            color: rgba(26, 32, 44, var(--text-opacity))
        }

        .underline {
            text-decoration: underline
        }

        .antialiased {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale
        }

        .w-5 {
            width: 1.25rem
        }

        .w-8 {
            width: 2rem
        }

        .w-auto {
            width: auto
        }

        .grid-cols-1 {
            grid-template-columns:repeat(1, minmax(0, 1fr))
        }

        @media (min-width: 640px) {
            .sm\:rounded-lg {
                border-radius: .5rem
            }

            .sm\:block {
                display: block
            }

            .sm\:items-center {
                align-items: center
            }

            .sm\:justify-start {
                justify-content: flex-start
            }

            .sm\:justify-between {
                justify-content: space-between
            }

            .sm\:h-20 {
                height: 5rem
            }

            .sm\:ml-0 {
                margin-left: 0
            }

            .sm\:px-6 {
                padding-left: 1.5rem;
                padding-right: 1.5rem
            }

            .sm\:pt-0 {
                padding-top: 0
            }

            .sm\:text-left {
                text-align: left
            }

            .sm\:text-right {
                text-align: right
            }
        }

        @media (min-width: 768px) {
            .md\:border-t-0 {
                border-top-width: 0
            }

            .md\:border-l {
                border-left-width: 1px
            }

            .md\:grid-cols-2 {
                grid-template-columns:repeat(2, minmax(0, 1fr))
            }
        }

        @media (min-width: 1024px) {
            .lg\:px-8 {
                padding-left: 2rem;
                padding-right: 2rem
            }
        }

        @media (prefers-color-scheme: dark) {
            .dark\:bg-gray-800 {
                --bg-opacity: 1;
                background-color: #2d3748;
                background-color: rgba(45, 55, 72, var(--bg-opacity))
            }

            .dark\:bg-gray-900 {
                --bg-opacity: 1;
                background-color: #1a202c;
                background-color: rgba(26, 32, 44, var(--bg-opacity))
            }

            .dark\:border-gray-700 {
                --border-opacity: 1;
                border-color: #4a5568;
                border-color: rgba(74, 85, 104, var(--border-opacity))
            }

            .dark\:text-white {
                --text-opacity: 1;
                color: #fff;
                color: rgba(255, 255, 255, var(--text-opacity))
            }

            .dark\:text-gray-400 {
                --text-opacity: 1;
                color: #cbd5e0;
                color: rgba(203, 213, 224, var(--text-opacity))
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar fixed-top">
    <div class="area float-left mr-auto">
        <span>
            <i class="fas fa-envelope-open-text"></i> <a
                href="mailto:karlhillx@gmail.com">karlhillx@gmail.com</a>
        </span>
    </div>
    <div class="float-right mt-2 mt-md-0">
        <a href="javascript:" data-toggle="modal" data-target="#login-modal">
            <i class="fas fa-sign-in-alt"></i>
            Login
        </a>
    </div>
</nav>
<!-- // -->

<!-- Landing -->
<div class="content-wrap">
    <div class="landing" id="home">
        <div class="container">
            <div class="col-md-12 contents text-left">
                <div data-aos="fade-left" data-aos-duration="1200" data-aos-delay="200"
                     data-aos-easing="ease-in-out-back">
                    <h1><strong>Karl Hill</strong></h1>
                </div>
                <div data-aos="fade-left" data-aos-duration="1100" data-aos-delay="400"
                     data-aos-easing="ease-in-out-back">
                    <p>Full Stack Engineer (LAMP, Laravel, MVC, Bootstrap)</p>
                </div>
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="500"
                     data-aos-easing="ease-in-out-back">
                    <button type="button" class="btn btn-warning" data-toggle="modal"
                            data-target="#resume-modal">Resume
                    </button>
                    <a href="#why" class="btn btn-border">Learn More</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- // -->

<!-- Why? -->
<section id="why" class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="300">
                <img src="{{ asset('img/features/img1.png') }}" alt="" class="img-fluid">
            </div>
            <div class="col-md-6 col-sm-6" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="300">
                <div class="content">
                    <h2>Why You Should<br> Hire Me</h2>
                    <p>
                        Full stack engineer with over 20 years development experience. Background to accomplish
                        complex programming tasks and the expertise to produce engaging interfaces. Hunger for
                        learning new technologies and functions well independently or in team situations.
                        Professional, motivated, detail-oriented, wildly creative, and possesses excellent
                        verbal and written skills.
                    </p>
                    <ul class="list-item">
                        <li><i class="fa fa-thermometer-full" aria-hidden="true"></i>&nbsp;20+ years experience</li>
                        <li><i class="fa fa-thermometer-three-quarters" aria-hidden="true"></i>&nbsp;MVC
                            frameworks (Laravel, Symfony)
                        </li>
                        <li><i class="fa fa-thermometer-half" aria-hidden="true"></i>&nbsp;Test-driven
                            development (TDD)
                        </li>
                        <li><i class="fa fa-thermometer-quarter" aria-hidden="true"></i>&nbsp;Agile methodologies
                            (Scrum/Kanban)
                        </li>
                        <li><i class="fa fa-thermometer-empty" aria-hidden="true"></i>&nbsp;Responsive UI/UX design</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- // -->

<div class="map-area">
    <div class="map">
        <iframe
            width="100%" height="400"
            style="border:0"
            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA1U9kPUx3SN0u33kKiaCSXl7plnfA3y8Q&q=Woodley+Park,+Washington,+DC+20008"
            allowfullscreen>
        </iframe>
    </div>
</div>

<!-- Footer -->
<footer>
    <!-- Copyright -->
    <div id="copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="copyright-text">
                        <small>© {{ date('Y') }} Karl Hill. Laravel v{{ app()->version() }}.<br>
                            Unless otherwise indicated, content is licensed under the Creative Commons Attribution
                            4.0 International.
                        </small>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="bottom-social-icons float-right">
                        <a class="linkedin" target="_blank" href="https://linkedin.com/in/khill"><i
                                class="fab fa-linkedin"></i></a>
                        <a class="github" target="_blank" href="https://github.com/karlhillx/"><i
                                class="fab fa-github"></i></a>
                        <a class="stackoverflow" target="_blank"
                           href="https://stackoverflow.com/users/633440/karl-hill"><i class="fab fa-stack-overflow"></i></a>
                        <a class="twitter" target="_blank" href="https://twitter.com/karl_hill/"><i
                                class="fab fa-twitter-square"></i></a>
                        <a class="discogs" target="_blank" href="https://www.discogs.com/artist/1286669-Karl-Hill"><i
                                class="fas fa-compact-disc"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- // -->

</footer>
<!-- // -->

<!-- Resume Modal -->
<div class="modal fade" id="resume-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Resume</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="alert alert-info" role="alert">
                    Please make a selection from the following options.
                </div>
                <div class="p-5 bd-example">
                    <a class="btn btn-danger" href="{{ asset('files/karlhill-resume.pdf') }}" role="button"
                       target="_blank"><i class="fas fa-file-pdf" aria-hidden="true"></i>&nbsp;PDF</a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"> Close</button>
            </div>
        </div>
    </div>
</div>
<!-- // -->

<!-- Portfolio Modal -->
<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="login-modalTitle">Portfolio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" action="{{ route('login') }}">
                @csrf
                <div class="modal-body">
                    <div class="bd-example">
                        <div class="user-icon text-center">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" aria-describedby="emailHelp" data-minlength="1"
                                   maxlength="255" required>
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" aria-describedby="passwordHelp"
                                   data-minlength="8" maxlength="20" required>
                            <div id="passwordHelp" class="form-text">Must be 8-20 characters long.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary active" aria-pressed="true">Login</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<a href="#" class="back-to-top">
    <i class="fas fa-arrow-up"></i>
</a>

</body>
<script src="{{ mix('js/app.js') }}"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-85045253-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', 'UA-85045253-1');
</script>
<script>
    AOS.init();
</script>
</html>
