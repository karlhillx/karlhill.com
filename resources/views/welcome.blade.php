<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="uFxkTB0m6tASRk0RkemBMFIyjR5TpGH5Qrmhpka_QfY"/>
    <meta name="version" content="Laravel v{{ app()->version() }}">
    <title>{{ config('app.name', 'Karl Hill | Full Stack Engineer') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar fixed-top">
    <div class="area float-left mr-auto">
        <span><i class="far fa-envelope"></i> <a href="mailto:karlhillx@gmail.com">karlhillx@gmail.com</a></span>
    </div>
    <div class="float-right mt-2 mt-md-0">
        <a href="javascript:" data-toggle="modal" data-target="#login-modal"><i class="far fa-user-circle"></i>
            Login</a>
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
                            frameworks (Laravel, Symphony, Django)
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
                <div class="col-md-6 col-sm-6">
                    <div class="copyright-text">
                        <small class="text-muted">© {{ date('Y') }} Karl Hill. Laravel v{{ app()->version() }}.<br>
                            Unless otherwise indicated, content is licensed under the Creative Commons Attribution
                            4.0 International.
                        </small>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 mb-3">
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
                       target="_blank"><i
                            class="fas fa-file-pdf" aria-hidden="true"></i>&nbsp;PDF</a>
                    <a class="btn btn-primary disabled" href="#" role="button"><i
                            class="fas fa-file-word" aria-hidden="true"></i>&nbsp;DOC</a>
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
