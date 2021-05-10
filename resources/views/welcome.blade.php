<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="uFxkTB0m6tASRk0RkemBMFIyjR5TpGH5Qrmhpka_QfY"/>
    <meta name="framework" content="Laravel v{{ app()->version() }}">
    <title>{{ config('app.name', 'Karl Hill | Full Stack Engineer') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="antialiased font-sans">
<div>
    <header>
        <nav class="navbar fixed-top">
            <div class="container">
                <div class="float-start" data-aos="flip-left">
                    <i class="fas fa-envelope-open-text me-2"></i>
                    <a href="mailto:karlhillx@gmail.com" target="_blank" class="hover:text-white">
                        karlhillx@gmail.com
                    </a>
                </div>
                <div class="float-right mt-2 mt-md-0" data-aos="flip-right">
                    <a href="javascript:" data-bs-toggle="modal" data-bs-target="#login-modal" class="hover:text-white">
                        <i class="fas fa-sign-in-alt me-2"></i> Portfolio
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero -->
    <div class="relative bg-gray-50 overflow-hidden">
        <div class="landing">
            <div class="container">
                <div class="col-12">
                    <div data-aos="fade-left" data-aos-duration="1200" data-aos-delay="200"
                         data-aos-easing="ease-in-out-back">
                        <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Karl Hill</h2>
                    </div>
                    <div data-aos="fade-left" data-aos-duration="1100" data-aos-delay="400" class="mt-2 mb-3"
                         data-aos-easing="ease-in-out-back">
                        <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Full Stack Engineer</h2>
                    </div>
                    <div data-aos="fade-left" data-aos-duration="1000" data-aos-delay="600" class="mt-2 mb-3"
                         data-aos-easing="ease-in-out-back">
                        <h6 class="uppercase font-normal text-lg">Python, PHP, Laravel, MVC, Tailwind</h6>
                    </div>
                    <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="500"
                         data-aos-easing="ease-in-out-back">

                        <a href="https://www.linkedin.com/in/khill/" target="_blank"
                           class="inline-flex items-center px-4 py-2 border border-transparent text-base
                            font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700
                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fab fa-linkedin mr-2"></i>LinkedIn
                        </a>

                        <button type="button" data-bs-toggle="modal" data-bs-target="#resume-modal"
                                class="inline-flex items-center px-4 py-2 border border-black
                            text-base font-medium rounded-md shadow-sm text-black bg-yellow-400 hover:bg-yellow-500
                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-300">
                            <i class="fas fa-file-pdf mr-2"></i>Resume/CV
                        </button>

                        <a href="#why" data-bs-toggle="modal" data-bs-target="#resume-modal"
                           class="inline-flex items-center px-4 py-2 border border-transparent
                            text-base font-medium rounded-md shadow-sm text-white bg-transparent hover:bg-gray-50
                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            Learn More
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- -->
    <div class="bg-white">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
            <div class="bg-indigo-900 rounded-lg shadow-xl overflow-hidden lg:grid lg:grid-cols-2 lg:gap-4">
                <div class="pt-10 pb-12 px-6 sm:pt-16 sm:px-16 lg:py-16 lg:pr-0 xl:py-20 xl:px-20">
                    <div class="lg:self-center">
                        <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                            <span class="block">Why you should hire me.</span>
                        </h2>
                        <p class="mt-4 text-lg leading-6 text-white">
                            With more than 20 years of experience developing and launching breakthrough solutions in Agile environments, I am a senior technology leader proven in client-facing situations. Background to accomplish
                            complex programming tasks and the expertise to produce engaging interfaces. Hunger for
                            learning new technologies and functions well independently or in team situations.
                            Professional, motivated, detail-oriented, wildly creative, and possesses excellent
                            verbal and written skills.
                        </p>
                    </div>
                </div>
                <div class="-mt-6 aspect-w-5 aspect-h-3 md:aspect-w-2 md:aspect-h-1">
                    <img class="transform translate-x-6 translate-y-6 rounded-md object-cover object-left-top sm:translate-x-16 lg:translate-y-20" src="https://tailwindui.com/img/component-images/full-width-with-sidebar.jpg" alt="App screenshot">
                </div>
            </div>
        </div>
    </div>


    <!-- Feature -->
    <section id="why" class="px-0 py-24">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 text-right" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="300">
                    <img src="{{ asset('img/features/img1.png') }}" alt="" class="img-fluid">
                </div>
                <div class="col-lg-7" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="300">
                    <div class="content col-8">
                        <h2 class="text-white font-medium text-4xl">Why You Should Hire Me</h2>
                        <p class="text-white text-lg mt-3 mb-3">
                            Full stack engineer with 25 years development experience. Background to accomplish
                            complex programming tasks and the expertise to produce engaging interfaces. Hunger for
                            learning new technologies and functions well independently or in team situations.
                            Professional, motivated, detail-oriented, wildly creative, and possesses excellent
                            verbal and written skills.
                        </p>
                        <ul class="list-item list-none">
                            <li class="text-base leading-10 text-white">
                                <i class="fas fa-thermometer-full me-2"></i>20+ years SDLC experience
                            </li>
                            <li class="text-base leading-10 text-white">
                                <i class="fas fa-thermometer-three-quarters me-2"></i>MVC frameworks (Laravel, Symfony)
                            </li>
                            <li class="text-base leading-10 text-white">
                                <i class="fas fa-thermometer-half me-2"></i>Test-driven development (TDD)
                            </li>
                            <li class="text-base leading-10 text-white">
                                <i class="fas fa-thermometer-quarter me-2"></i>Agile methodologies (Scrum/Kanban)
                            </li>
                            <li class="text-base leading-10 text-white">
                                <i class="fas fa-thermometer-quarter me-2"></i>Responsive UI/UX (Tailwind, Bootstrap,
                                etc.)
                            </li>
                            <li class="text-base leading-10 text-white">
                                <i class="fas fa-thermometer-empty me-2"></i>Excellent communication and writing skills
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map -->
    <div class="map-area">
        <div class="map">
            <iframe class="border-0 h-96 w-full"
                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA1U9kPUx3SN0u33kKiaCSXl7plnfA3y8Q&q=Woodley+Park,+Washington,+DC+20008"
                    allowfullscreen>
            </iframe>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-black">
        <div class="container">
            <div class="row pt-5 pb-5">
                <div class="col-md-7">
                    <div class="text-gray-400">© {{ date('Y') }} Karl Hill. Laravel Build v{{ app()->version() }}.<br>
                        Unless otherwise indicated, content is licensed under the Creative Commons Attribution
                        4.0 International.
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="bottom-social-icons float-end">
                        <a class="linkedin" target="_blank" href="https://linkedin.com/in/khill">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a class="github" target="_blank" href="https://github.com/karlhillx/">
                            <i class="fab fa-github"></i>
                        </a>
                        <a class="stackoverflow" target="_blank"
                           href="https://stackoverflow.com/users/633440/karl-hill">
                            <i class="fab fa-stack-overflow"></i>
                        </a>
                        <a class="twitter" target="_blank" href="https://twitter.com/karl_hill/">
                            <i class="fab fa-twitter-square"></i>
                        </a>
                        <a class="discogs" target="_blank" href="https://www.discogs.com/artist/1286669-Karl-Hill">
                            <i class="fas fa-compact-disc"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Resume Modal -->
    <div class="modal fade" id="resume-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog w-96" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="font-bold text-base">Resume</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="border-transparent rounded border-solid border mb-4 p-4 relative msg-info" role="alert">
                        Please make a selection from the following options.
                    </div>
                    <div class="p-1">
                        <a class="bg-green-500 text-white" href="{{ asset('files/karlhill-resume.pdf') }}" role="button"
                           target="_blank">
                            <i class="fas fa-file-pdf mr-3"></i>PDF Version
                        </a>
                    </div>
                </div>
                <div class="bg-gray-50">
                    <button type="button" data-bs-dismiss="modal" class="w-full inline-flex justify-center rounded-md border
                        border-gray-300 px-4 py-2 bg-white text-base font-medium text-gray-700 shadow-sm
                        hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                        sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm float-right mb-3 mt-3 mr-3">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Modal -->
    <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modalTitle"
         aria-hidden="true">
        <div class="modal-dialog w-96" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="login-modalTitle" class="font-medium">Portfolio</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('portfolio') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="bd-example container">
                            <div class="text-gray-200 text-9xl text-center">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="mb-3 container">
                                <div class="form-floating">
                                    <input type="email"
                                           class="form-control border border-gray-900 rounded focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                           id="floatingInput"
                                           placeholder="name@example.com">
                                    <label for="floatingInput">Email address</label>
                                </div>
                                <small id="emailHelp" class="text-muted">We won't share your email address.</small>
                            </div>
                            <div class="mb-3 container">
                                <div class="form-floating">
                                    <input type="password"
                                           class="form-control border border-gray-900 rounded focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                           id="floatingPassword"
                                           placeholder="Password">
                                    <label for="floatingPassword">Password</label>
                                </div>
                                <small id="passwordHelp" class="text-muted">Must be 8-20 characters long.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="pt-5">
                            <div class="flex justify-end">
                                <button type="button"
                                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="ml-2 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Login
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script src="{{ mix('js/app.js') }}"></script>
@toastr_js
@toastr_render
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
</body>
</html>
