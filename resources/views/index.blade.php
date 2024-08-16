@extends('layout')

@section('title', 'Home - OneSchool')

@section('content')

<div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
            <div class="site-mobile-menu-close mt-3">
                <span class="icon-close2 js-menu-toggle"></span>
            </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div>
   
 
    @if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

@if (!Auth::check())  <!-- If the user is not logged in, show the intro section -->
<div class="intro-section" id="home-section">
    <div class="slide-1" style="background-image: url('{{ asset('frontend/assets/images/hero_1.jpg') }}');" data-stellar-background-ratio="0.5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="row align-items-center">
                        <div class="col-lg-6 mb-4">
                            <h1 data-aos="fade-up" data-aos-delay="100">Learn From The Expert</h1>
                            <p class="mb-4" data-aos="fade-up" data-aos-delay="200">Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime ipsa nulla sed quis rerum amet natus quas necessitatibus.</p>
                            <p data-aos="fade-up" data-aos-delay="300"><a href="#" class="btn btn-primary py-3 px-5 btn-pill">Admission Now</a></p>
                        </div>

                        <div class="col-lg-5 ml-auto" data-aos="fade-up" data-aos-delay="500">
                            <form action="{{ route('register') }}" method="post" class="form-box" enctype="multipart/form-data">
                                @csrf
                                <h3 class="h4 text-black mb-4">Sign Up</h3>

                                <!-- Full Name -->
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                                </div>

                                <!-- Email Address -->
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                                </div>

                                <!-- Password -->
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                                </div>

                                <!-- Re-type Password -->
                                <div class="form-group mb-4">
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Re-type Password" required>
                                </div>

                                <!-- City -->
                                <div class="form-group">
                                    <input type="text" name="city" class="form-control" placeholder="City" required>
                                </div>

                                <!-- Role Selection -->
                                <div class="form-group">
                                    <select name="role" class="form-control" required>
                                        <option value="" disabled selected>Select Role</option>
                                        <option value="student">Student</option>
                                        <option value="teacher">Teacher</option>
                                    </select>
                                </div>

                                <!-- Category (Visible only for teachers) -->
                                <div class="form-group" id="categoryField" style="display: none;">
                                    <select name="Category_id" class="form-control">
                                        <option value="" disabled selected>Select Category</option>
                                        <!-- Populate categories from the database -->
                                        <option value="1">Mathematics</option>
                                        <option value="2">Physics</option>
                                        <option value="3">Chemistry</option>
                                        <option value="4">Arabic</option>
                                        <option value="5">English</option>
                                        <option value="6">French</option>
                                        <option value="7">Science</option>
                                        <option value="8">Islamic Education</option>
                                        <option value="9">National Education</option>
                                    </select>
                                </div>

                                <!-- CV Upload (Visible only for teachers) -->
                                <div class="form-group" id="cvField" style="display: none;">
                                    <input type="file" name="cv_path" class="form-control">
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary btn-pill" value="Sign up">
                                </div>
                            </form>

                            <!-- Already have an account link -->
                            <p class="text-center mt-4">
                                Already have an account? <a href="{{ route('login') }}">Log in</a>
                            </p>
                        </div>

                        <script>
                            // Toggle fields based on role selection
                            document.querySelector('select[name="role"]').addEventListener('change', function() {
                                if (this.value === 'teacher') {
                                    document.getElementById('categoryField').style.display = 'block';
                                    document.getElementById('cvField').style.display = 'block';
                                } else {
                                    document.getElementById('categoryField').style.display = 'none';
                                    document.getElementById('cvField').style.display = 'none';
                                }
                            });
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

    <!-- //بداية الكود  -->
    <div class="site-section courses-title" id="courses-section">
    <div class="container">
        <div class="row mb-5 justify-content-center">
            <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="">
                <h2 class="section-title">Courses</h2>
            </div>
        </div>
    </div>
</div>

<div class="site-section courses-entry-wrap" data-aos="fade-up" data-aos-delay="100">
    <div class="container">
        <div class="row">
            <div class="owl-carousel col-12 nonloop-block-14">
            @foreach($courses as $course)
    <div class="course bg-white h-100 align-self-stretch">
        <figure class="m-0">
            <a href="{{ route('course.units', $course->id) }}">
                <img src="{{ asset('frontend\assets\example.png') }}" alt="Image" class="img-fluid">
            </a>
        </figure>
        <div class="course-inner-text py-4 px-4">
            <span class="course-price">${{ $course->price }}</span>
            <div class="meta"><span class="icon-clock-o"></span>{{ $course->duration }}</div>
            <h3><a href="{{ route('course.units', $course->id) }}">{{ $course->title }}</a></h3>
            <p>{{ $course->description }}</p>
            <p><strong>Number of Units:</strong> {{ $course->units_count }}</p>
            <p><strong>Number of Lessons:</strong> {{ $course->lessons_count }}</p>
            <p><strong>Number of Students:</strong> {{ $course->enrollments_count }}</p>

            <!-- Enroll Button -->
            <form action="{{ route('course.enroll', $course->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary btn-block mt-3">Enroll Now</button>
            </form>
        </div>
        <div class="d-flex border-top stats">
            <div class="py-3 px-4"><span class="icon-users"></span> {{ $course->students_count }} students</div>
            <div class="py-3 px-4 w-25 ml-auto border-left"><span class="icon-chat"></span> {{ $course->comments_count }}</div>
        </div>
    </div>
@endforeach
            </div>
        </div>
    </div>
</div>












            </div>
            <div class="row justify-content-center">
                <div class="col-7 text-center">
                    <button class="customPrevBtn btn btn-primary m-1">Prev</button>
                    <button class="customNextBtn btn btn-primary m-1">Next</button>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section" id="programs-section">
        <div class="container">
            <div class="row mb-5 justify-content-center">
                <div class="col-lg-7 text-center"  data-aos="fade-up" data-aos-delay="">
                    <h2 class="section-title">Our Programs</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magnam repellat aut neque! Doloribus sunt non aut reiciendis, vel recusandae obcaecati hic dicta repudiandae in quas quibusdam ullam, illum sed veniam!</p>
                </div>
            </div>
            <div class="row mb-5 align-items-center">
                <div class="col-lg-7 mb-5" data-aos="fade-up" data-aos-delay="100">
                    <img src="{{ asset('frontend/assets/images/undraw_youtube_tutorial.svg') }}" alt="Image" class="img-fluid">
                </div>
                <div class="col-lg-4 ml-auto" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="text-black mb-4">We Are Excellent In Education</h2>
                    <p class="mb-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem maxime nam porro possimus fugiat quo molestiae illo.</p>

                    <div class="d-flex align-items-center custom-icon-wrap mb-3">
                        <span class="custom-icon-inner mr-3"><span class="icon icon-graduation-cap"></span></span>
                        <div><h3 class="m-0">22,931 Yearly Graduates</h3></div>
                    </div>

                    <div class="d-flex align-items-center custom-icon-wrap">
                        <span class="custom-icon-inner mr-3"><span class="icon icon-university"></span></span>
                        <div><h3 class="m-0">150 Universities Worldwide</h3></div>
                    </div>

                </div>
            </div>

            <div class="row mb-5 align-items-center">
                <div class="col-lg-7 mb-5 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="100">
                    <img src="{{ asset('frontend/assets/images/undraw_teaching.svg') }}" alt="Image" class="img-fluid">
                </div>
                <div class="col-lg-4 mr-auto order-2 order-lg-1" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="text-black mb-4">Strive for Excellent</h2>
                    <p class="mb-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem maxime nam porro possimus fugiat quo molestiae illo.</p>

                    <div class="d-flex align-items-center custom-icon-wrap mb-3">
                        <span class="custom-icon-inner mr-3"><span class="icon icon-graduation-cap"></span></span>
                        <div><h3 class="m-0">22,931 Yearly Graduates</h3></div>
                    </div>

                    <div class="d-flex align-items-center custom-icon-wrap">
                        <span class="custom-icon-inner mr-3"><span class="icon icon-university"></span></span>
                        <div><h3 class="m-0">150 Universities Worldwide</h3></div>
                    </div>

                </div>
            </div>

            <div class="row mb-5 align-items-center">
                <div class="col-lg-7 mb-5" data-aos="fade-up" data-aos-delay="100">
                    <img src="{{ asset('frontend/assets/images/undraw_teacher.svg') }}" alt="Image" class="img-fluid">
                </div>
                <div class="col-lg-4 ml-auto" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="text-black mb-4">Education is life</h2>
                    <p class="mb-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem maxime nam porro possimus fugiat quo molestiae illo.</p>

                    <div class="d-flex align-items-center custom-icon-wrap mb-3">
                        <span class="custom-icon-inner mr-3"><span class="icon icon-graduation-cap"></span></span>
                        <div><h3 class="m-0">22,931 Yearly Graduates</h3></div>
                    </div>

                    <div class="d-flex align-items-center custom-icon-wrap">
                        <span class="custom-icon-inner mr-3"><span class="icon icon-university"></span></span>
                        <div><h3 class="m-0">150 Universities Worldwide</h3></div>
                    </div>

                </div>
            </div>

        </div>
    </div>


<!-- //teacher  -->
<div class="row">
    @foreach($teachers as $teacher)
        <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="teacher text-center">
                <img src="{{ asset('frontend/assets/images/person_1.jpg') }}" alt="Image" class="img-fluid w-50 rounded-circle mx-auto mb-4">
                <div class="py-2">
                    <h3 class="text-black">{{ $teacher->user->name }}</h3> <!-- اسم الأستاذ -->
                    <p class="position">{{ $teacher->category ? $teacher->category->name : 'No Category' }}</p> <!-- اسم الفئة -->
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Porro eius suscipit delectus enim iusto tempora, adipisci at provident.</p>
                </div>
            </div>
        </div>
    @endforeach
</div>

    <div class="site-section bg-image overlay" style="background-image: url('{{ asset('frontend/assets/images/hero_1.jpg') }}');">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-8 text-center testimony">
                    <img src="{{ asset('frontend/assets/images/person_4.jpg') }}" alt="Image" class="img-fluid w-25 mb-4 rounded-circle">
                    <h3 class="mb-4">Jerome Jensen</h3>
                    <blockquote>
                        <p>&ldquo; Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum rem soluta sit eius necessitatibus voluptate excepturi beatae ad eveniet sapiente impedit quae modi quo provident odit molestias! Rem reprehenderit assumenda &rdquo;</p>
                    </blockquote>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section pb-0">

        <div class="future-blobs">
            <div class="blob_2">
                <img src="{{ asset('frontend/assets/images/blob_2.svg') }}" alt="Image">
            </div>
            <div class="blob_1">
                <img src="{{ asset('frontend/assets/images/blob_1.svg') }}" alt="Image">
            </div>
        </div>
        <div class="container">
            <div class="row mb-5 justify-content-center" data-aos="fade-up" data-aos-delay="">
                <div class="col-lg-7 text-center">
                    <h2 class="section-title">Why Choose Us</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 ml-auto align-self-start"  data-aos="fade-up" data-aos-delay="100">

                    <div class="p-4 rounded bg-white why-choose-us-box">

                        <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                            <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-graduation-cap"></span></span></div>
                            <div><h3 class="m-0">22,931 Yearly Graduates</h3></div>
                        </div>

                        <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                            <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-university"></span></span></div>
                            <div><h3 class="m-0">150 Universities Worldwide</h3></div>
                        </div>

                        <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                            <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-graduation-cap"></span></span></div>
                            <div><h3 class="m-0">Top Professionals in The World</h3></div>
                        </div>

                        <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                            <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-university"></span></span></div>
                            <div><h3 class="m-0">Expand Your Knowledge</h3></div>
                        </div>

                        <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                            <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-graduation-cap"></span></span></div>
                            <div><h3 class="m-0">Best Online Teaching Assistant Courses</h3></div>
                        </div>

                        <div class="d-flex align-items-center custom-icon-wrap custom-icon-light">
                            <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-university"></span></span></div>
                            <div><h3 class="m-0">Best Teachers</h3></div>
                        </div>

                    </div>


                </div>
                <div class="col-lg-7 align-self-end"  data-aos="fade-left" data-aos-delay="200">
                    <img src="{{ asset('frontend/assets/images/person_transparent.png') }}" alt="Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <div class="site-section bg-light" id="contact-section">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-7">

                    <h2 class="section-title mb-3">Message Us</h2>
                    <p class="mb-5">Natus totam voluptatibus animi aspernatur ducimus quas obcaecati mollitia quibusdam temporibus culpa dolore molestias blanditiis consequuntur sunt nisi.</p>
                
                    <form method="post" data-aos="fade">
                        <div class="form-group row">
                            <div class="col-md-6 mb-3 mb-lg-0">
                                <input type="text" class="form-control" placeholder="First name">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Last name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="text" class="form-control" placeholder="Subject">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="email" class="form-control" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <textarea class="form-control" id="" cols="30" rows="10" placeholder="Write your message here."></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <input type="submit" class="btn btn-primary py-3 px-5 btn-block btn-pill" value="Send Message">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
  

    <!-- Continue with other sections like Teachers, Why Choose Us, Contact, etc. -->

@endsection
