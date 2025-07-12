<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>{!! setting('site.owner') !!} </title>
    <meta name="description" content="{!! setting('site.owner') !!}">
    <meta name="author" content="{!! setting('site.owner') !!}">




    @include('links.css')


    <!-- Hotjar Tracking Code for http://www.daddycodeu.com -->
    <script>
        (function(h, o, t, j, a, r) {
            h.hj = h.hj || function() {
                (h.hj.q = h.hj.q || []).push(arguments)
            };
            h._hjSettings = {
                hjid: 2688596,
                hjsv: 6
            };
            a = o.getElementsByTagName('head')[0];
            r = o.createElement('script');
            r.async = 1;
            r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
            a.appendChild(r);
        })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
    </script>

</head>

<body class="side-header" data-bs-spy="scroll" data-bs-target="#header-nav" data-bs-offset="1">

    <!-- Preloader
    <div class="preloader preloader-dark">

        <div class="lds-ellipsis">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
   Preloader End -->

    <!-- Document Wrapper
=============================== -->
    <div id="main-wrapper">
        <!-- Header
  ============================ -->
        <header id="header" class="sticky-top">
            <!-- Navbar -->

            <nav class="primary-menu navbar navbar-expand-lg navbar-dark bg-dark border-bottom-0 ">

                <div class="container-fluid position-relative h-100 flex-lg-column ps-3 px-lg-3 pt-lg-3 pb-lg-2 ">

                    <!-- Logo -->
                    <a href="/en" class="mb-lg-auto mt-lg-4">
                        <span class="bg-dark-2 rounded-pill p-2 mb-lg-1 d-none d-lg-inline-block">
                            <img class="img-fluid rounded-pill d-block" src="/public/tema/site/images/profile.png"
                                title="{!! setting('site.owner') !!} " alt="">
                        </span>
                        <h1 class="d-none d-sm-block text-5 text-white text-center mb-0 d-lg-block">
                            {!! setting('site.owner') !!} </h1>
                    </a>
                    <!-- Logo End -->

                    <div id="header-nav" class="collapse navbar-collapse w-100 my-lg-auto">
                        <ul class="navbar-nav text-lg-center my-lg-auto py-lg-3">

                            @foreach ($en_headers->sortBy('sira') as $basliklar)
                                <li class="nav-item"><a class="nav-link smooth-scroll @once active @endonce"
                                        href="#{!! $basliklar->seo_url !!}">{!! $basliklar->name !!}</a></li>
                            @endforeach
                            <li class="nav-item"><a class="nav-link  " href="/"><img style="width:30px;"
                                        src="/public/tr_flag.png" alt=""></a></li>

                        </ul>
                    </div>
                    <ul class="social-icons social-icons-muted social-icons-sm mt-lg-auto ms-auto ms-lg-0 d-flex">
                        @foreach ($socials as $sosyal)
                            <li><a data-bs-toggle="tooltip" title="{!! $sosyal->title !!}"
                                    href="{!! $sosyal->link !!}" target="_blank"><i
                                        class="{!! $sosyal->icon !!}"></i></a>
                            </li>
                        @endforeach

                    </ul>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#header-nav"><span></span><span></span><span></span></button>
                </div>
            </nav>
            <!-- Navbar End -->
        </header>
        <!-- Header End -->

        <!-- Content
  ============================================= -->
        <div id="content" role="main">

            <!-- Intro
    ============================================= -->
            <section id="home">
                <div class="hero-wrap">
                    <div class="hero-mask opacity-8 bg-dark"></div>
                    <div class="hero-bg parallax" style="background-image:url('/public/tema/site/images/main.jpg');">
                    </div>
                    <div class="hero-content section d-flex min-vh-100">
                        <div class="container my-auto">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <div class="typed-strings">
                                        <p>{!! setting('site.owner') !!}</p>
                                        @foreach ($slogans->sortBy('rule') as $slogan)
                                            <p>{!! $slogan->title !!}</p>
                                        @endforeach
                                    </div>
                                    <p class="text-7 fw-500 text-white mb-2 mb-md-3">{!! setting('site.welcome_en') !!}
                                    </p>
                                    <h2 class="text-16 fw-600 text-white mb-2 mb-md-3"><span class="typed"></span>
                                    </h2>
                                    <p class="text-5 text-light mb-4">{!! setting('site.welcome2_en') !!}</p>
                                    <a href="#contact"
                                        class="btn btn-outline-primary rounded-pill shadow-none smooth-scroll mt-2">{!! setting('site.button_en') !!}</a>
                                </div>
                            </div>
                        </div>
                        <a href="#about" class="scroll-down-arrow text-white smooth-scroll"><span class="animated"><i
                                    class="fa fa-chevron-down"></i></span></a>
                    </div>
                </div>
            </section>
            <!-- Intro end -->

            <!-- About
    ============================================= -->
            <section id="about-me" class="section bg-dark-1">
                <div class="container px-lg-5">
                    <!-- Heading -->
                    <div class="position-relative d-flex text-center mb-5">
                        <h2 class="text-24 text-muted opacity-1 text-uppercase fw-600 w-100 mb-0">
                            {!! __('general.about') !!}</h2>
                        <p class="text-9 text-white fw-600 position-absolute w-100 align-self-center lh-base mb-0">
                            {!! __('general.know_me_more') !!}<span
                                class="heading-separator-line border-bottom border-3 border-primary d-block mx-auto"></span>
                        </p>
                    </div>
                    <!-- Heading end-->

                    <div class="row gy-5">
                        <div class="col-lg-7 col-xl-8 text-center text-lg-start">
                            <h2 class="text-7 text-white fw-600 mb-3">I am <span
                                    class="text-primary">{!! setting('site.owner') !!}</span> </h2>
                            <p class="text-white-50">Hello, I'm Emre, I was born in Istanbul in 2000 and grew up here.
                                After completing primary, secondary and high school in Istanbul, I went to Burdur for
                                university education, where I stayed for 4 years. I completed my undergraduate education
                                at Mehmet Akif Ersoy University - Management Information Systems and during this time I
                                have improved my knowledge both theoretically and practically.
                            </p>

                            <p class="text-white-50">Due to my interest in web technologies such as Laravel, PHP,
                                Bootstrap, JavaScript and HTML/CSS, I decided to specialize in these areas by improving
                                myself. My mastery of Laravel Framework has enabled me to achieve successful results,
                                especially in some backend projects. I also improved my project management, teamwork,
                                time management and problem solving skills. With more than 1 year of experience on
                                Laravel, I took an active role in some live web projects.
                            </p>




                        </div>
                        <div class="col-lg-5 col-xl-4">
                            <div class="ps-lg-4">
                                <ul class="list-style-2 list-style-light text-light">
                                    <li><span class="fw-600 me-2">
                                            {!! __('general.name') !!}:</span>{!! setting('site.owner') !!}</li>
                                    <li><span class="fw-600 me-2"> {!! __('general.mail') !!}:</span><a
                                            href="mailto:{!! setting('site.owner') !!}">{!! setting('bilgiler.e_posta') !!}</a></li>
                                    <li><span
                                            class="fw-600 me-2">{!! __('general.age') !!}:</span>{!! setting('bilgiler.yas') !!}
                                    </li>
                                    <li class="border-0"><span
                                            class="fw-600 me-2">{!! __('general.location') !!}:</span>{!! setting('bilgiler.konum') !!}
                                    </li>
                                </ul>
                                <a href="/public/CV_EN.pdf" target="_blank"
                                    class="btn btn-primary rounded-pill">{!! __('general.cv') !!}</a>
                            </div>
                        </div>
                    </div>
                    <div class="my-projectss-grid separator-border separator-border-light mt-5">
                        <div class="row">
                            @foreach ($en_quadruples->sortBy('rule') as $dortlu)
                                <div class="col-6 col-md-3">
                                    <div class="featured-box text-center">
                                        <h4 class="text-12 text-white-50 mb-0"><span class="counter" data-from="0"
                                                data-to="{!! $dortlu->number !!}">{!! $dortlu->number !!}</span>{!! $dortlu->char !!}
                                        </h4>
                                        <p class="text-light mb-0">{!! $dortlu->text !!}</p>
                                    </div>
                                </div>
                            @endforeach


                        </div>
                    </div>
                </div>
            </section>
            <!-- About end -->

            <!-- Services
    ============================================= -->
            <section id="skills" class="section bg-dark-2">
                <div class="container px-lg-5">
                    <!-- Heading -->
                    <div class="position-relative d-flex text-center mb-5">
                        <h2 class="text-24 text-white-50 opacity-1 text-uppercase fw-600 w-100 mb-0">
                            {!! __('general.skills2') !!}</h2>
                        <p class="text-9 text-white fw-600 position-absolute w-100 align-self-center lh-base mb-0">
                            {!! __('general.what_do_i_do') !!}<span
                                class="heading-separator-line border-bottom border-3 border-primary d-block mx-auto"></span>
                        </p>
                    </div>
                    <!-- Heading end-->

                    <div class="row">
                        <div class="col-lg-11 mx-auto">
                            <div class="row">
                                @foreach ($en_abilities as $beceriler)
                                    <div class="col-md-6">
                                        <div class="featured-box style-3 mb-5">
                                            <div class="featured-box-icon text-primary bg-dark-1 shadow-sm rounded"> <i
                                                    class="{!! $beceriler->icon !!}"></i> </div>
                                            <h3 class="text-white">{!! $beceriler->title !!}</h3>
                                            <p class="text-white-50 mb-0">{!! $beceriler->text !!}</p>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Services end -->

            <!-- Resume
    ============================================= -->
            <section id="resume" class="section bg-dark-1">
                <div class="container px-lg-5">
                    <!-- Heading -->
                    <div class="position-relative d-flex text-center mb-5">
                        <h2 class="text-24 text-muted opacity-1 text-uppercase fw-600 w-100 mb-0">
                            {!! __('general.summary') !!}</h2>
                        <p class="text-9 text-white fw-600 position-absolute w-100 align-self-center lh-base mb-0">
                            {!! __('general.resume') !!}<span
                                class="heading-separator-line border-bottom border-3 border-primary d-block mx-auto"></span>
                        </p>
                    </div>
                    <!-- Heading end-->

                    <div class="row gx-5">
                        <!-- My Education -->
                        <div class="col-md-6">
                            <h2 class="text-6 text-white fw-600 mb-4">{!! __('general.experience') !!}</h2>
                            @foreach ($en_resumes->sortBy('rule') as $ozgecmis)
                                @if ($ozgecmis->type == 'tecrube')
                                    <div class="bg-dark rounded p-4 mb-4">
                                        <p class="badge bg-danger text-2 fw-400">{!! $ozgecmis->year !!}</p>
                                        <h3 class="text-5 text-white">{!! $ozgecmis->title !!}</h3>
                                        <p class="text-primary">{!! $ozgecmis->title2 !!}</p>
                                        <p class="text-white-50 mb-0">{!! $ozgecmis->text !!}</p>
                                    </div>
                                @endif
                            @endforeach

                        </div>

                        <div class="col-md-6">
                            <h2 class="text-6 text-white fw-600 mb-4">{!! __('general.education') !!}</h2>
                            @foreach ($en_resumes->sortBy('rule') as $ozgecmis)
                                @if ($ozgecmis->type == 'egitim')
                                    <div class="bg-dark rounded p-4 mb-4">
                                        <p class="badge bg-danger text-2 fw-400">{!! $ozgecmis->year !!}</p>
                                        <h3 class="text-5 text-white">{!! $ozgecmis->title !!}</h3>
                                        <p class="text-primary">{!! $ozgecmis->title2 !!}</p>
                                        <p class="text-white-50 mb-0">{!! $ozgecmis->text !!}</p>
                                    </div>
                                @endif
                            @endforeach

                        </div>
                    </div>
                    <!-- My Skills -->
                    <h2 class="text-6 text-white fw-600 mt-4 mb-4">{!! __('general.skills') !!}</h2>
                    <div class="row gx-5">
                        @foreach ($skills as $yetenekler)
                            <div class="col-md-6">
                                <p class="text-light fw-500 text-start mb-2">{!! $yetenekler->title !!} <span
                                        class="float-end">{!! $yetenekler->percentage !!}%</span>
                                </p>
                                <div class="progress progress-sm bg-dark mb-4">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {!! $yetenekler->percentage !!}%"
                                        aria-valuenow="{!! $yetenekler->percentage !!}" aria-valuemin="0"
                                        aria-valuemax="{!! $yetenekler->percentage !!}"></div>
                                </div>

                            </div>
                        @endforeach

                    </div>
                    <small style="font-size:10px;" style="color:#777;">{!! __('general.muted') !!}</small>
                    <div class="text-center mt-5"><a href="/public/CV_EN.pdf" target="_blank"
                            class="btn btn-outline-secondary rounded-pill shadow-none">{!! __('general.cv') !!} <span
                                class="ms-1"><i class="fas fa-download"></i></span></a></div>
                </div>
            </section>
            <!-- Resume end -->

            <!-- Portfolio
    ============================================= -->
            <section id="portfolio" class="section bg-dark-2">
                <div class="container px-lg-5">
                    <!-- Heading -->
                    <div class="position-relative d-flex text-center mb-5">
                        <h2 class="text-24 text-white-50 opacity-1 text-uppercase fw-600 w-100 mb-0">
                            {!! __('general.portfoy') !!}</h2>
                        <p class="text-9 text-white fw-600 position-absolute w-100 align-self-center lh-base mb-0">
                            {!! __('general.projects') !!}<span
                                class="heading-separator-line border-bottom border-3 border-primary d-block mx-auto"></span>
                        </p>
                    </div>
                    <!-- Heading end-->

                    <!-- Filter Menu -->
                    <ul class="portfolio-menu nav nav-tabs nav-light justify-content-center border-bottom-0 mb-5">
                        <li class="nav-item"> <a data-filter="*" class="nav-link active" href="">All</a></li>
                        <li class="nav-item"> <a data-filter=".web-sites" href="" class="nav-link">Web
                                Sites</a></li>

                        <li class="nav-item"> <a data-filter=".special-projects" href=""
                                class="nav-link">Special
                                Projects</a>

                        <li class="nav-item"> <a data-filter=".my-projects" href="" class="nav-link">My
                                Projects</a>
                        </li>

                    </ul>
                    <!-- Filter Menu end -->
                    <div class="portfolio popup-ajax-gallery">
                        <div class="row portfolio-filter g-4">


                            <div class="col-sm-6 col-lg-4  my-projects ">
                                <div class="portfolio-box rounded">
                                    <div class="portfolio-img rounded"> <img style="height:200px; width:100%;"
                                            class="img-fluid d-block"
                                            src="/public/tema/site/images/projects/daddycodeu.png" alt="">
                                        <div class="portfolio-overlay"> <a class="popup-ajax stretched-link"
                                                href="/public/tema/site/ajax/portfolio-ajax-project-dark-14_EN.html"></a>
                                            <div class="portfolio-overlay-details">
                                                <h5 class="text-white fw-400">Anonymous comment site with PHP </h5>
                                                <span class="text-light">My Projects</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-6 col-lg-4  my-projects ">
                                <div class="portfolio-box rounded">
                                    <div class="portfolio-img rounded"> <img style="height:200px; width:100%;"
                                            class="img-fluid d-block"
                                            src="/public/tema/site/images/projects/projectx.png" alt="">
                                        <div class="portfolio-overlay"> <a class="popup-ajax stretched-link"
                                                href="/public/tema/site/ajax/portfolio-ajax-project-dark-16_EN.html"></a>
                                            <div class="portfolio-overlay-details">
                                                <h5 class="text-white fw-400">Advanced administration panel with
                                                    Laravel
                                                </h5>
                                                <span class="text-light">My Projects</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>.

                            <div class="col-sm-6 col-lg-4  my-projects ">
                                <div class="portfolio-box rounded">
                                    <div class="portfolio-img rounded"> <img style="height:200px; width:100%;"
                                            class="img-fluid d-block"
                                            src="/public/tema/site/images/projects/sablon.png" alt="">
                                        <div class="portfolio-overlay"> <a class="popup-ajax stretched-link"
                                                href="/public/tema/site/ajax/portfolio-ajax-project-dark-17_EN.html"></a>
                                            <div class="portfolio-overlay-details">
                                                <h5 class="text-white fw-400">One-page responsive website template
                                                </h5>
                                                <span class="text-light">My Projects</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4  my-projects ">
                                <div class="portfolio-box rounded">
                                    <div class="portfolio-img rounded"> <img style="height:200px; width:100%;"
                                            class="img-fluid d-block"
                                            src="/public/tema/site/images/projects/admin-panel.png" alt="">
                                        <div class="portfolio-overlay"> <a class="popup-ajax stretched-link"
                                                href="/public/tema/site/ajax/portfolio-ajax-project-dark-15_EN.html"></a>
                                            <div class="portfolio-overlay-details">
                                                <h5 class="text-white fw-400">Admin panel with PHP </h5>
                                                <span class="text-light">My Projects</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-lg-4  my-projects ">
                                <div class="portfolio-box rounded">
                                    <div class="portfolio-img rounded"> <img style="height:200px; width:100%;"
                                            class="img-fluid d-block"
                                            src="/public/tema/site/images/projects/agalarnediyor.png" alt="">
                                        <div class="portfolio-overlay"> <a class="popup-ajax stretched-link"
                                                href="/public/tema/site/ajax/portfolio-ajax-project-dark-18_EN.html"></a>
                                            <div class="portfolio-overlay-details">
                                                <h5 class="text-white fw-400">Twitter clone with Laravel and AJAX
                                                </h5>
                                                <span class="text-light">My Projects</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                                
                            <div class="col-sm-6 col-lg-4  my-projects ">
                                <div class="portfolio-box rounded">
                                    <div class="portfolio-img rounded"> <img style="height:200px; width:100%;"
                                            class="img-fluid d-block"
                                            src="/public/tema/site/images/projects/businessdogboosting.png" alt="">
                                        <div class="portfolio-overlay"> <a class="popup-ajax stretched-link"
                                                href="/public/tema/site/ajax/portfolio-ajax-project-dark-19_EN.html"></a>
                                            <div class="portfolio-overlay-details">
                                                <h5 class="text-white fw-400">Boosting site with PayTR integration
                                                </h5>
                                                <span class="text-light">My Projects</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-6 col-lg-4  my-projects ">
                                <div class="portfolio-box rounded">
                                    <div class="portfolio-img rounded"> <img style="height:200px; width:100%;"
                                            class="img-fluid d-block"
                                            src="/public/tema/site/images/projects/storage-1.png" alt="">
                                        <div class="portfolio-overlay"> <a class="popup-ajax stretched-link"
                                                href="/public/tema/site/ajax/portfolio-ajax-project-dark-20_EN.html"></a>
                                            <div class="portfolio-overlay-details">
                                                <h5 class="text-white fw-400">Google Drive clone with Laravel and VueJs.
                                                </h5>
                                                <span class="text-light">My Projects</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>
                </div>
            </section>
            <!-- Portfolio end -->

            <!-- Testimonial
    ============================================= -->
            <section id="blog" class="section bg-dark-1">
                <div class="container px-lg-5">
                    <!-- Heading -->
                    <div class="position-relative d-flex text-center mb-5">
                        <h2 class="text-24 text-muted opacity-1 text-uppercase fw-600 w-100 mb-0">
                            {!! __('general.blog') !!}</h2>
                        <p class="text-9 text-white fw-600 position-absolute w-100 align-self-center lh-base mb-0">
                            {!! __('general.blog2') !!}<span
                                class="heading-separator-line border-bottom border-3 border-primary d-block mx-auto"></span>
                        </p>
                    </div>
                    <!-- Heading end-->

                    <div class="owl-carousel owl-light owl-theme" data-loop="true" data-nav="false"
                        data-autoplay="false" data-margin="25" data-stagepadding="0" data-slideby="1"
                        data-items-xs="1" data-items-sm="1" data-items-md="1" data-items-lg="2">
                        @foreach ($en_blogs as $blog)
                            <div class="item ">
                                <div class="bg-dark rounded p-5 ">
                                    <div class=" d-flex align-items-center  mt-auto mb-4">
                                        <a data-bs-toggle="modal" data-bs-target="#{!! $blog->url !!}"
                                            href="#">
                                            <h4 class=" mb-0 t  "><strong
                                                    class="d-block text-white  fw-600">{!! $blog->title !!}
                                                </strong>
                                            </h4>
                                        </a>
                                    </div>
                                    <p class="text-light mb-4">{!! substr(strip_tags($blog->text), 0, 255) !!}... </p>
                                </div>
                            </div>
                        @endforeach


                    </div>
                </div>
            </section>
            <!-- Testimonial end -->

            <!-- Contact Me
    ============================================= -->

            <section id="contact" class="section bg-dark-2">

                <div class="container px-lg-5">
                    <!-- Heading -->
                    <div class="position-relative d-flex text-center mb-5">
                        <h2 class="text-24 text-white-50 opacity-1 text-uppercase fw-600 w-100 mb-0">
                            {!! __('general.contact') !!}</h2>
                        <p class="text-9 text-white fw-600 position-absolute w-100 align-self-center lh-base mb-0">
                            {!! __('general.get_in_touch') !!}<span
                                class="heading-separator-line border-bottom border-3 border-primary d-block mx-auto"></span>
                        </p>
                    </div>
                    <!-- Heading end-->
                    <div class="row gy-5">
                        <div class="col-md-4 col-xl-3 order-1 order-md-0 text-center text-md-start">


                            <br>
                            <br>
                            <p class="text-3 text-light mb-1"><span class="text-primary text-4 me-2"><i
                                        class="fas fa-phone"></i></span><a
                                    href="https://wa.me/+90{{ str_replace(' ', '', setting('bilgiler.telefon')) }}"
                                    target="_blank">{!! setting('bilgiler.telefon') !!}</a></p>

                            <p class="text-3 text-light mb-4"><span class="text-primary text-4 me-2"><i
                                        class="fas fa-envelope"></i></span><a
                                    href="mailto:{!! setting('bilgiler.e_posta') !!}">{!! setting('bilgiler.e_posta') !!}</a></p>
                            <h2 class="mb-3 text-5 text-white text-uppercase">{!! __('general.follow_me') !!}</h2>
                            <ul
                                class="social-icons social-icons-muted justify-content-center justify-content-md-start">
                                @foreach ($socials as $sosyal)
                                    <li><a data-bs-toggle="tooltip" title="{!! $sosyal->title !!}"
                                            href="{!! $sosyal->link !!}" target="_blank"><i
                                                class="{!! $sosyal->icon !!}"></i></a>
                                    </li>
                                @endforeach
                            </ul>
                            <br>
                            <br>

                            <!-- Put this code anywhere in the body of your page where you want the badge to show up. -->

                            <div itemscope itemtype='http://schema.org/Person' class='fiverr-seller-widget'
                                style='display: inline-block;'>
                                <a itemprop='url' href=https://www.fiverr.com/emrekd rel="nofollow" target="_blank"
                                    style='display: inline-block;'>
                                    <div class='fiverr-seller-content'
                                        id='fiverr-seller-widget-content-6ed3c448-0d09-4cb2-807a-e3c5a0bd3fbb'
                                        itemprop='contentURL' style='display: none;'></div>
                                        
                                    <div id='fiverr-widget-seller-data' style='display: none;'>
                                        <div itemprop='name'>emrekd</div>
                                        <div itemscope itemtype='http://schema.org/Organization'><span
                                                itemprop='name'>Fiverr</span></div>
                                        <div itemprop='jobtitle'>Seller</div>
                                        <div itemprop='description'>I am MIS student. I can build responsive html
                                            sites. Because of my country's
                                            exchange rate, ı am bidding the lowest price so you can hire me for low
                                            prizes</div>
                                    </div>
                                </a>
                            </div>

                          

                        </div>

                        <div class="col-md-8 col-xl-9 order-0 order-md-1">
                            <div class="row">


                                <div class="col-xl-6">
                                    <h2 class="mb-3 text-5 text-white text-uppercase text-center text-md-start">
                                        {!! __('general.send_me_note') !!}
                                    </h2>

                                </div>
                                <div class="col-xl-6">
                                    <h2 class=" text-5 text-white  text-md-end">
                                        @php
                                            $random = rand();
                                            echo $random;
                                        @endphp
                                    </h2>

                                </div>

                            </div>



                            <form id="form" method="POST" action="/#form" class="form-dark">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-xl-6">

                                        <input type="hidden" value="{{$random}}" name="random">

                                        <input type="text" placeholder="{!! __('general.name') !!}"
                                            class="form-control @error('name') is-invalid @enderror" id="name"
                                            name="name" value="{{ old('name') }}">

                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-6">
                                        <input type="email" placeholder="{!! __('general.mail') !!}"
                                            class="form-control @error('mail') is-invalid @enderror" id="mail"
                                            name="mail" value="{{ old('mail') }}">

                                        @error('mail')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-xl-6">
                                        <input type="tel" placeholder="{!! __('general.tel') !!}"
                                            class="form-control @error('tel') is-invalid @enderror" id="tel"
                                            name="tel" value="{{ old('tel') }}">

                                        @error('tel')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="col-xl-6">
                                        <input type="verification_code" placeholder="{!! __('general.verification_code') !!}"
                                            class="form-control @error('verification_code') is-invalid @enderror"
                                            id="verification_code" name="verification_code"
                                            value="{{ old('verification_code') }}">

                                        @error('verification_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <textarea placeholder="{!! __('general.message') !!}" class="form-control @error('message') is-invalid @enderror"
                                            id="message" name="message" value="{{ old('message') }}" rows="5"></textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>
                                </div>

                                <br>

                                @if (session('success_en'))
                                    <div class="alert alert-success alert-dismissible fade show text-center"
                                        role="alert">
                                        {{ session('success_en') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @elseif (session('fail_en'))
                                    <div class="alert alert-danger alert-dismissible fade show text-center"
                                        role="alert">
                                        {{ session('fail_en') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif
                                <p class="text-center mt-4 mb-0">
                                    <button id="submit-btn" class="btn btn-primary rounded-pill d-inline-flex"
                                        type="submit">{!! __('general.submit') !!}</button>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Contact Me end -->

        </div>
        <!-- Content end -->

        <!-- Footer
  ============================================= -->
        <footer id="footer" class="section footer-dark bg-dark-1">
            <div class="container px-lg-5">
                <div class="row">
                    <div class="col-lg-12 text-center text-lg-start">
                        <p class="mb-3 mb-lg-0">© {{ date('Y') }} Coded by <a href="#"
                                class="fw-500">{!! setting('site.owner') !!}</a></p>


                    </div>



                </div>
            </div>
        </footer>
        <!-- Footer end -->

    </div>
    <!-- Document Wrapper end -->

    <!-- Back to Top
============================================= -->
    <a id="back-to-top" class="rounded-circle" data-bs-toggle="tooltip" title="{!! __('general.go_up') !!}"
        href="javascript:void(0)"><i class="fa fa-chevron-up"></i></a>

    <!-- Terms & Policy Modal
================================== -->
    @foreach ($en_blogs as $blog)
        <div id="{!! $blog->url !!}" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content bg-dark-2 text-light">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title text-white">{!! $blog->title !!}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        {!! $blog->text !!}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- Terms & Policy Modal End -->


    @include('links.js')


</body>

</html>
