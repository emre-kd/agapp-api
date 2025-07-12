<div class="col-12 text-left text-white post align-items-center    "
style="border-top:1px solid gray; min-height:100px;">
<div class="row  ">

    <div class=" col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2 col-3 ">
        @if ($user->user_image !== null)
            <img style="border-radius:100%; margin-top:20px; margin-left:10px; width:50px; height:50px;"
                src="/public/storage/{{ $user->user_image }}"
                alt="User Image">
        @else
            <img style="border-radius:100%; margin-top:20px; margin-left:10px; width:50px; height:50px;"
                src="/public/storage/default_user_image.png" alt="User Image">
        @endif

    </div>

    <div class=" col-xl-10 col-lg-10 col-md-10 col-sm-10 col-xs-10 col-8   "
        style="margin-top:30px; ;">

        @php $usernameWithAt = '@' . $user->username; @endphp



        <div class="row ">
            <a href="#" class="username">
                <b>{!! $user->name !!}</b>
            </a>
            &nbsp;
            <p class="text-muted"> {!! $usernameWithAt !!} </p>&nbsp;
            <p class="text-muted"> {!! $postlar->created_at !!} </p>


        </div>


    </div>






</div>

<br>
<div class="col-md-12">
    <p
        style="min-height: 100%; overflow: hidden; text-overflow: ellipsis; word-wrap: break-word;">
        {!! $postlar->text !!}
    </p>
</div>

@if (isset($postlar->image))
    <img class="img-fluid  mx-auto d-block"
        style="  margin-bottom:20px; width:450px; max-height:400px; border-radius:30px;"
        src="/public/storage/{{ $postlar->image }}">
@endif

</div>