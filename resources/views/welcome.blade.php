<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="uFxkTB0m6tASRk0RkemBMFIyjR5TpGH5Qrmhpka_QfY"/>
    <meta name="framework" content="Laravel v{{ app()->version() }}">
    <title>{{ config('app.name', 'Karl Hill | Full Stack Engineer') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;900&display=swap">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="antialiased">
<div>
    <!-- Navbar -->
    <nav class="navbar fixed-top">
        <div class="container">
            <div class="float-left mr-auto" data-aos="flip-left">
                <i class="fas fa-envelope-open-text mr-2"></i><a
                    href="mailto:karlhillx@gmail.com" target="_blank">karlhillx@gmail.com</a>
            </div>
            <div class="float-right mt-2 mt-md-0" data-aos="flip-right">
                <a href="javascript:" data-toggle="modal" data-target="#login-modal">
                    <i class="fas fa-sign-in-alt mr-2"></i>Portfolio
                </a>
            </div>
        </div>
    </nav>
    <!-- // -->

    <!-- Landing -->
    <div class="content-wrap">
        <div class="landing">
            <div class="container">
                <div class="col-12">
                    <div data-aos="fade-left" data-aos-duration="1200" data-aos-delay="200"
                         data-aos-easing="ease-in-out-back">
                        <h1><strong>Karl Hill</strong></h1>
                    </div>
                    <div data-aos="fade-left" data-aos-duration="1100" data-aos-delay="400" class="mt-2 mb-3"
                         data-aos-easing="ease-in-out-back">
                        <h6 class="text-uppercase">Full Stack Engineer (Laravel, PHP, MySQL, LAMP, MVC)</h6>
                    </div>
                    <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="500"
                         data-aos-easing="ease-in-out-back">
                        <a href="https://www.linkedin.com/in/khill/" target="_blank" class="btn btn-primary mr-1"><i
                                class="fab fa-linkedin"></i> LinkedIn</a>
                        <button type="button" class="btn btn-warning mr-1" data-toggle="modal"
                                data-target="#resume-modal"><i class="far fa-file-pdf"></i> Resume
                        </button>
                        <a href="#why" class="btn btn-border"> Learn More</a>
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
                <div class="col-lg-5 text-right" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="300">
                    <img src="{{ asset('img/features/img1.png') }}" alt="" class="img-fluid">
                </div>
                <div class="col-lg-7" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="300">
                    <div class="content col-8">
                        <h2>Why You Should<br> Hire Me</h2>
                        <p>
                            Full stack engineer with over 20 years development experience. Background to accomplish
                            complex programming tasks and the expertise to produce engaging interfaces. Hunger for
                            learning new technologies and functions well independently or in team situations.
                            Professional, motivated, detail-oriented, wildly creative, and possesses excellent
                            verbal and written skills.
                        </p>
                        <ul class="list-item">
                            <li>
                                <i class="fas fa-thermometer-full mr-2"></i>20+ years SDLC experience
                            </li>
                            <li>
                                <i class="fas fa-thermometer-three-quarters mr-2"></i>MVC frameworks (Laravel, Symfony)
                            </li>
                            <li>
                                <i class="fas fa-thermometer-half mr-2"></i>Test-driven development (TDD)
                            </li>
                            <li>
                                <i class="fas fa-thermometer-quarter mr-2"></i>Agile methodologies (Scrum/Kanban)
                            </li>
                            <li>
                                <i class="fas fa-thermometer-quarter mr-2"></i>Responsive UI/UX (Tailwind CSS,
                                Bootstrap,
                                etc.)
                            </li>
                            <li>
                                <i class="fas fa-thermometer-empty mr-2"></i>Excellent communication and writing skills
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // -->

    <!-- Mapz -->
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
    <!-- // -->

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row pt-5 pb-5">
                <div class="col-md-7">
                    <small>© {{ date('Y') }} Karl Hill. Laravel Build v{{ app()->version() }}.<br>
                        Unless otherwise indicated, content is licensed under the Creative Commons Attribution
                        4.0 International.
                    </small>
                </div>
                <div class="col-md-5">
                    <div class="bottom-social-icons float-right">
                        <a class="linkedin" target="_blank" href="https://linkedin.com/in/khill"><i
                                class="fab fa-linkedin"></i></a>
                        <a class="github" target="_blank" href="https://github.com/karlhillx/"><i
                                class="fab fa-github"></i></a>
                        <a class="stackoverflow" target="_blank"
                           href="https://stackoverflow.com/users/633440/karl-hill"><i
                                class="fab fa-stack-overflow"></i></a>
                        <a class="twitter" target="_blank" href="https://twitter.com/karl_hill/"><i
                                class="fab fa-twitter-square"></i></a>
                        <a class="discogs" target="_blank" href="https://www.discogs.com/artist/1286669-Karl-Hill"><i
                                class="fas fa-compact-disc"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- // -->

    <!-- Resume Modal -->
    <div class="modal fade" id="resume-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Resume</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="alert alert-info" role="alert">
                        Please make a selection from the following options.
                    </div>
                    <div class="p-1 bd-example">
                        <a class="btn btn-danger" href="{{ asset('files/karlhill-resume.pdf') }}" role="button"
                           target="_blank"><i class="fas fa-file-pdf mr-2"></i>PDF Version</a>
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
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="post" action="{{ route('login') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="bd-example container">
                            <div class="user-icon text-center">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="mb-3 container">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="floatingInput"
                                           placeholder="name@example.com">
                                    <label for="floatingInput">Email address</label>
                                </div>
                                <small id="emailHelp" class="text-muted">We don't share your email address.</small>
                            </div>
                            <div class="mb-3 container">
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="floatingPassword"
                                           placeholder="Password">
                                    <label for="floatingPassword">Password</label>
                                </div>
                                <small id="passwordHelp" class="text-muted">Must be 8-20 characters long.</small>
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
    <!-- // -->

</div>
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

    AOS.init();
</script>
</html>
