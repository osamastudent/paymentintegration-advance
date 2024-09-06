<?php
$seenIcon = (!!$seen ? 'check-double' : 'check');
$timeAndSeen = "<span data-time='$created_at' class='message-time'>
        " . ($isSender ? "<span class='fas fa-$seenIcon' seen'></span>" : '') . " <span class='time'>$timeAgo</span>
    </span>";
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<div class="message-card @if($isSender) mc-sender @endif" data-id="{{ $id }}">
    {{-- Delete Message Button --}}
    @if ($isSender)
    <div class="actions">
        <i class="fas fa-trash delete-btn" data-id="{{ $id }}"></i>
        <input type="text" class="myinput" id="myinput" data-id="{{ $id }}" value="{{ $id }}">
        <a href="#" class="getId" data-id="{{ $id }}">edit</a>
    </div>





    <!-- //     $(".getId").on("click", function(event) {
    //         event.preventDefault(); // Prevent default action for the anchor tag
    //         var dataId = $(this).data('id');
    //         // var dataId = $(this).attr('data-id');
    //         // alert(dataId);
    //         // console.log(dataId);

    //         $.ajax({
    //             url: "{{route('getRecordBody')}}",
    //             data: {
    //                 id: dataId
    //             },
    //             type: "GET",
    //             success: function(response) {
    //                 console.log(response);
    //             },
    //             error: function(response) {
    //                 console.log("Error osama", response);
    //             }
    //         });
    //    }); -->
    <script>
        $(document).ready(function() {


            var dataId;
            $(".getId").on("click", function(event) {
                event.preventDefault();
                var dataId = $(this).data('id');
                let creaetValueAtt = $("#myuppdateId").val(dataId);

                $.ajax({
                    url: "{{route('getRecordBody')}}",
                    data: {
                        id: dataId
                    },
                    type: "GET",
                    success: function(response) {
                        console.log(response);
                        mymessage = $('textarea[name="message"]').val(response.body);
                        // $("#myapp").css("display", "none");
                        $("#myapp").hide();
                        $("#myappupdate").removeClass("d-none");
                        $("#myappupdate").show();
                    },
                    error: function(response) {
                        console.log("Error osama", response);
                    }
                });

                // update   
            });

    // $('#messageId').keydown(function(event) {
    //     if (event.key === 'Enter') {
    //         event.preventDefault();
    //         $('#updateBtn').click();
    //     }
    // });


            $("#updateBtn").on("click", function(event) {
                // event.preventDefault();
                $.ajax({
                    url: "{{route('update.message')}}",
                    data: {
                        id: $("#myuppdateId").val(),
                        body: $("#messageId").val()
                    },
                    type: "POST",
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            });



        });
    </script>





    @endif
    {{-- Card --}}
    <div class="message-card-content">
        @if (@$attachment->type != 'image' || $message)
        <div class="message">
            {!! ($message == null && $attachment != null && @$attachment->type != 'file') ? $attachment->title : nl2br($message) !!}
            {!! $timeAndSeen !!}
            {{-- If attachment is a file --}}
            @if(@$attachment->type == 'file')
            <a href="{{ route(config('chatify.attachments.download_route_name'), ['fileName'=>$attachment->file]) }}" class="file-download">
                <span class="fas fa-file"></span> {{$attachment->title}}</a>
            @endif
        </div>
        @endif
        @if(@$attachment->type == 'image')
        <div class="image-wrapper" style="text-align: {{$isSender ? 'end' : 'start'}}">
            <div class="image-file chat-image" style="background-image: url('{{ Chatify::getAttachmentUrl($attachment->file) }}')">
                <div>{{ $attachment->title }}</div>
            </div>
            <div style="margin-bottom:5px">
                {!! $timeAndSeen !!}
            </div>
        </div>
        @endif
    </div>
</div>