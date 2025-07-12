<div class="col-3  d-none d-xl-block" style="min-height:950px; border-right:1px solid gray;">
    <!-- Content for the first column -->


    <br>
    <br>
    <br>


    <ul class="nav nav-pills flex-column  " style="position:fixed;  ">
        <li class="nav-item ">
            <a href="/" class="nav-link leftbar-text float-left" aria-current="page">
                <i class="bi bi-house-door-fill " style="font-size:25px;"></i>
                &nbsp; {!! __('general.home') !!} &nbsp;

            </a>
        </li>


        @auth
            @php
                $currentUserID = auth()->user()->id;
                $likeCount = 0;
                
                $users_for_count = App\Models\User::all();
                $stats_for_count = App\Models\Stat::all();
                $posts_for_count = App\Models\Post::all();
            @endphp

            <li class="nav-item ">
                <span class="badge badge-primary   "
                    style="font-size:8px;margin-left:35px;  margin-top:5px; position:absolute;">


                    @foreach ($stats_for_count as $stat)
                        @if ($stat->likes == 1)
                            @php
                                $user = $users_for_count->where('id', $stat->user_id)->first();
                                $post = $posts_for_count->where('id', $stat->post_id)->first();
                                
                                if ($user && $post && $user->id !== $currentUserID && $post->user_id == $currentUserID) {
                                    $likeCount++;
                                }
                            @endphp
                        @endif
                    @endforeach

                    {{ $likeCount }}

                </span>
                <a href="/bildirimler" class="nav-link leftbar-text float-left" aria-current="page">

                    <i class="bi bi-bell-fill " style="font-size:25px;"></i>
                    &nbsp; {!! __('general.notifications') !!} &nbsp;





                </a>

            </li>

            <li class="nav-item ">

                <a href="/chatify" target="_blank" class="nav-link leftbar-text float-left" aria-current="page">
                    <i class="bi bi-envelope-fill   " style="font-size:25px; ">
                    </i>
                    &nbsp; {!! __('general.messages') !!} &nbsp;





                </a>
            </li>

            <li class="nav-item ">
                <a href="/profile/{!! auth()->user()->username !!}" class="nav-link leftbar-text float-left" aria-current="page">
                    <i class="bi bi-person-fill  " style="font-size:25px;"></i>
                    &nbsp; {!! __('general.profile') !!} &nbsp;

                </a>
            </li>

            <li class="nav-item ">
                <a style="cursor:pointer;" data-toggle="modal" data-target="#userSettingsModal{!! auth()->user()->id !!}"
                    class="nav-link leftbar-text float-left" aria-current="page">
                    <i class="bi bi-gear-fill  " style="font-size:25px;"></i>
                    &nbsp; {!! __('general.settings') !!} &nbsp;

                </a>
            </li>

            <li class="nav-item ">
                <button href="#" class="btn btn-block nav-link mt-1   " aria-current="page" data-toggle="modal"
                    data-target="#ilet"
                    style="background-color:rgb(29, 155, 240); border-radius:50px; color:white; font-weight:bold;
                "
                    aria-current="page">
                    &nbsp; {!! __('general.send_message') !!} &nbsp;

                </button>
            </li>

            <li class="nav-item " style="margin-top:400px;">

                <a href="{{ route('logout') }}" class="nav-link leftbar-text float-left" aria-current="page"
                    onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-left " style="font-size:25px;"></i>
                    &nbsp; {!! __('general.logout') !!} &nbsp;






                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>

        @endauth

        @guest

            <li class="nav-item ">

                <a href="/register" style="cursor:pointer;" class="nav-link leftbar-text float-left" aria-current="page">
                    <i class="bi bi-person-plus-fill " style="font-size:25px;"></i>

                    &nbsp; {!! __('general.register') !!} &nbsp;






                </a>
            </li>
            <li class="nav-item ">

                <a style="cursor:pointer;" class="nav-link leftbar-text float-left" aria-current="page" data-toggle="modal"
                    data-target="#login">
                    <i class="bi bi-box-arrow-in-right " style="font-size:25px;"></i>
                    &nbsp; {!! __('general.login') !!} &nbsp;
                </a>

            </li>



        @endguest


    </ul>






</div>




<!-- Modal -->
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="login">{!! __('general.login') !!}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="username"
                                class="col-md-4 col-form-label text-md-end">{!! __('general.user_name') !!}</label>

                            <div class="col-md-6">
                                <input id="username" type="text"
                                    class="form-control @error('username') is-invalid @enderror" name="username"
                                    value="{{ old('username') }}" required autocomplete="username" autofocus>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password"
                                class="col-md-4 col-form-label text-md-end">{!! __('general.password') !!}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">

                                    <a href="{{ route('password.request') }}">  {!! __('general.forgot_password') !!} </a>   

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {!! __('general.remember_me') !!}   
                                    </label>

                                    
                                </div>
                            </div>
                            
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-12 ">
                                <button type="submit" class="btn btn-block btn-primary">
                                    {!! __('general.login') !!}
                                </button>


                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>






@auth

    <div class="modal fade" id="ilet" tabindex="-1" role="dialog" aria-labelledby="ilet" aria-hidden="true"
        style="background-color:rgba(91, 112, 131, 0.4);">
        <div class="modal-dialog" role="document">
            <div class="modal-content " style="background-color:var(--main-color)">

                <div class="modal-body container-fluid">
                    <div class="col-12 ">


                        <div class="row mt-4">


                            <div class="col-2  ">
                                @if (Auth::user()->user_image == null)
                                    <img style="border-radius:100%; m margin-top:10px; width:50px; height:50px;"
                                        src="/storage/default_user_image.png" alt="User Image">
                                @else
                                    <img style="border-radius:100%; m margin-top:10px; width:50px; height:50px;"
                                        src="/storage/{{ Auth::user()->user_image }}" alt="User Image">
                                @endif
                            </div>


                            <div class="col-10 text-center   mb-2 ">

                                <form action="/post" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <textarea id="flexibleTextarea" name="text" placeholder="{!! __('general.post_placeholder') !!}" required maxlength="400"
                                        oninput="adjustTextareaHeight(this)"></textarea>
                                    <label for="imageUpload" class="custom-file-upload">
                                        <i class="bi bi-image float-left" style="color:rgb(29, 155, 240);"></i>
                                    </label>
                                    <input type="file" id="imageUpload" name="image" accept="image/*">
                                    <span class="file-name mt-1" id="fileNameDisplay"></span>

                                    <input type="hidden" name="user_id" value="{!! auth()->user()->id !!}">

                                    <button class=" btn float-right mt-1 " type="submit"
                                        style="border-radius:50px; background-color:rgb(29, 155, 240); color:white; font-weight:500;">{!! __('general.send') !!}</button>
                                    <input type="hidden" value="{{ request()->path() }}" name="currentRoute">
                                    <input type="hidden" value="1" name="isModal">

                                </form>


                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>



    <div style="background-color:rgba(91, 112, 131, 0.4);" class="modal fade"
        id="userSettingsModal{!! auth()->user()->id !!}" tabindex="-1" role="dialog"
        aria-labelledby="userSettingsModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content " style="background-color:var(--main-color); ">
                <form action="/settings/{!! auth()->user()->id !!}" method="POST">
                    @csrf
                    <div class="modal-header  border border-0 " style="color:var(--font-color);">
                        <h5 class="modal-title text-center" id="exampleModalLongTitle">Site Ayarları</h5>

                    </div>

                    <div class="modal-body  text-left" style="color:var(--font-color);">
                        Tema Rengi
                        <div class="col-12 d-flex align-items-center border-0 rounded " style="height:80px;">



                            <div class="col-4 bg-white  border mr-1 rounded  d-flex align-items-center justify-content-center"
                                style="height:50px; color:black;">
                                <div class="form-check ">
                                    <input class="form-check-input" type="radio" name="tema" id="exampleRadios1"
                                        value="white" @if (auth()->user()->tema == 'white' or auth()->user()->tema == null) checked @endif>
                                    <label class="form-check-label" for="exampleRadios1">
                                        Beyaz
                                    </label>
                                </div>
                            </div>
                            <div class="col-4  border rounded mr-1 d-flex align-items-center justify-content-center"
                                style="height:50px; background-color:rgb(21, 32, 43); color:white">

                                <div class="form-check">
                                    <input class="form-check-input " type="radio" name="tema"
                                        @if (auth()->user()->tema == 'rgb(21, 32, 43)') checked @endif id="exampleRadios2"
                                        value="rgb(21, 32, 43)">
                                    <label class="form-check-label" for="exampleRadios2">
                                        Haydar
                                    </label>
                                </div>
                            </div>
                            <div class="col-4  border rounded d-flex align-items-center justify-content-center"
                                style="height:50px; background-color:black; color:white">

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tema"
                                        @if (auth()->user()->tema == 'black') checked @endif id="exampleRadios2"
                                        value="black">
                                    <label class="form-check-label" for="exampleRadios2">
                                        Zenci
                                    </label>
                                </div>
                            </div>


                        </div>

                    </div>

                    <div class="modal-footer border border-0 d-flex justify-content-center">
                        <button type="submit" class="btn btn-outline-info">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endauth



<script>
    const fileInput = document.getElementById('imageUpload');
    const fileNameDisplay = document.getElementById('fileNameDisplay');

    fileInput.addEventListener('change', function(event) {
        if (event.target.files.length > 0) {
            const fileName = event.target.files[0].name;
            fileNameDisplay.textContent = fileName;
        } else {
            fileNameDisplay.textContent = '';
        }
    });
</script>
