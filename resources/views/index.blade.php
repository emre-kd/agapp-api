<!DOCTYPE html>
<html>

<head>



    @include('links.css')

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>



<body>
    @include('layouts.footer')
    <div class="container  ">
        <div class="row">
            @include('leftbar')



            @auth


                <div class="col-xl-7 col-lg-12 col-md-12  col-sm-12 col-xs-12 ">


                    @include('layouts.tabs.index-tab')




                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                            @include('layouts.post.post-input')

                        </div>
                        <div class="tab-pane fade  text-center mt-2" id="profile"
                            role="tabpanel"aria-labelledby="profile-tab">
                            <div class="col-12 text-right mb-2">
                                <select class="custom-select custom-select-sm my-1 mr-sm-2 "
                                    style="color:var(--font-color); background-color:var(--main-color);" id="selectOrder">
                                    <option value="mostlike_global">En çok beğenilen</option>
                                    <option value="newest_global">En yeni</option>
                                   <!--  <option value="random_global">Rastgele</option> -->
                                </select>
                  

                            </div>






                            <div class="row">
                                <div id="postContainer" class="postContainer postContainer-global" style="width:100%;">


                                    <div id="mostlike_global">

                                        @foreach ($postWithMostLikes as $posts)
                                            @foreach ($posts2 as $postlar)
                                                @foreach ($users as $user)
                                                    @if ($postlar->id == $posts->post_id and $user->id == $postlar->user_id)
                                                        @include('layouts.post.index-global-post')
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                        <div class="mt-3 mr-2">

                                            <span class="float-right">
                                                {{ $postWithMostLikes->links('pagination::bootstrap-4') }}
                                            </span>
                                        </div>

                                    </div>



                                    <div id="newest_global" style="display:none;">


                                        @foreach ($post_for_global_newest as $postlar)
                                            @foreach ($users as $user)
                                                @if ($user->id == $postlar->user_id)
                                                    @include('layouts.post.index-global-post-2')
                                                @endif
                                            @endforeach
                                        @endforeach


                                    </div>



                                    <div id="random_global" style="display:none;">


                                        @foreach ($post_for_global_random as $postlar)
                                            @foreach ($users as $user)
                                                @if ($user->id == $postlar->user_id)
                                                    @include('layouts.post.index-global-post-3')
                                                @endif
                                            @endforeach
                                        @endforeach

                                     

                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>



                </div>
            @else
                <div class="col-xl-7 col-lg-12 col-md-12  col-sm-12 col-xs-12 ">


                    <div class="row">

                        @include('layouts.non-auth')


                        <div id="postContainer" style="width:100%;">

                            @foreach ($randomPosts as $postlar)
                                @foreach ($users as $user)
                                    @if ($user->id == $postlar->user_id)
                                        @include('layouts.post.index-post-non-auth')
                                    @endif
                                @endforeach
                            @endforeach



                        </div>



                    </div>



                </div>
            @endauth


            @include('rightbar')



        </div>


    </div>

    <!-- Modal -->

    @include('links.js')

    <script>
        function getCSRFToken() {
            return $('meta[name="csrf-token"]').attr('content');
        }

        $(document).ready(function() {
            $(document).on('click', '.submit_form', function(e) {
                e.preventDefault();
                let user_id = $('#user_id').val();
                let text = $('#text').val();
                let isModal = $('#isModal').val();
                let currentRoute = $('#currentRoute').val();
                let fileInput = $('#image')[0].files[0]; // Get the file input element and its selected file

                let formData = new FormData(); // Create a FormData object to send the data with the file
                formData.append('user_id', user_id);
                formData.append('image', fileInput); // Append the file to the FormData
                formData.append('text', text);
                formData.append('isModal', isModal);
                formData.append('currentRoute', currentRoute);

                /*
                $('body').on('show.bs.modal', '.modal', function() {
                    // Handle modal show event here (if needed)
                });

                */

                $.ajax({
                    url: "{{ route('post.add') }}",
                    method: 'POST',
                    data: formData, // Use the FormData object here instead of the plain object
                    processData: false, // Important: prevent jQuery from processing the FormData
                    contentType: false, // Important: let the browser set the content type automatically
                    headers: {
                        'X-CSRF-TOKEN': getCSRFToken()
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            $('#text').val('');
                            $('#fileNameDisplay1').text('');
                            $('#image').val('');



                            $('tostContainer').empty();


                            $('.tostContainer').load(location.href + ' .tostContainer');
                            adjustTextareaHeight($('#text')[0]);


                            /*
                            $('body').off('click',
                                '.modal-trigger-btn'
                            ); // Remove previous event bindings (optional)
                            $('body').on('click', '.modal-trigger-btn', function() {
                                var targetModal = $(this).data('target');
                                $(targetModal).modal('show');
                            });
                            */
                        }
                    },
                    error: function(err) {
                        let errors = err.responseJSON; // Fix a typo: 'error' should be 'errors'
                        $('.errMsgContainer').empty(); // Clear previous errors
                        $.each(errors, function(index, value) {
                            $('.errMsgContainer').append('<span class="text-danger">' +
                                value + '</span>' + '<br>');
                        });
                    }
                })
            })
        });
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var selectBox = document.getElementById("selectOrder");
            var div1 = document.getElementById("mostlike_global");
            var div2 = document.getElementById("newest_global");
            var div3 = document.getElementById("random_global");


            selectBox.addEventListener("change", function() {
                var selectedOption = selectBox.options[selectBox.selectedIndex].value;

                if (selectedOption === "mostlike_global") {
                    div1.style.display = "block";
                    div2.style.display = "none";
                    div3.style.display = "none";
                } else if (selectedOption === "newest_global") {
                    div1.style.display = "none";
                    div2.style.display = "block";
                    div3.style.display = "none";
                } else if (selectedOption === "random_global") {
                    div1.style.display = "none";
                    div2.style.display = "none";
                    div3.style.display = "block";
              
                }
            });
        });
    </script>



</body>



<style>
    .dropdown-hover:hover {

        background-color: rgba(255, 255, 255, 0.03) !important;
        border-radius: 50px;
    }
</style>




<script>
    const fileInput1 = document.getElementById('image');
    const fileNameDisplay1 = document.getElementById('fileNameDisplay1');

    fileInput1.addEventListener('change', function(event) {
        if (event.target.files.length > 0) {
            const fileName = event.target.files[0].name;
            fileNameDisplay1.textContent = fileName;
        } else {
            fileNameDisplay1.textContent = '';
        }
    });
</script>


<script>
    /*
    $(document).ready(function() {
        function refreshContent() {
            $('.postContainer-follows').load(location.href + ' .postContainer-follows');
        }

        setInterval(refreshContent, 30000); // Refresh every 5 seconds (5000 milliseconds)
    });

    */
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $.noConflict();
</script>





</html>
