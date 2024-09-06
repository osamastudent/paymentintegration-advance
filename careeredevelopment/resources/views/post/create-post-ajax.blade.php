<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- bootstrap cdn -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <style>
    /* HTML: <div class="loader"></div> */
    .loader {
      width: 50px;
      --b: 8px;
      aspect-ratio: 1;
      border-radius: 50%;
      padding: 1px;
      background: conic-gradient(#0000 10%, #f03355) content-box;
      -webkit-mask:
        repeating-conic-gradient(#0000 0deg, #000 1deg 20deg, #0000 21deg 36deg),
        radial-gradient(farthest-side, #0000 calc(100% - var(--b) - 1px), #000 calc(100% - var(--b)));
      -webkit-mask-composite: destination-in;
      mask-composite: intersect;
      animation: l4 1s infinite steps(10);
    }

    @keyframes l4 {
      to {
        transform: rotate(1turn)
      }
    }
  </style>
  <title>Create Post Ajax</title>
</head>

<body id="body">
<div class="container">
<h6>
  AJAX is not a programming language. AJAX just uses a combination of: ... AJAX is a misleading name. AJAX
  </h6>
  <p>AJAX ka full form hai "Asynchronous JavaScript and XML".
<br>
  AJAX ek technique hai jisse web pages ko dynamic update kiya ja sakta hai, without reloading the entire page. Isse user experience behtar hota hai aur web application fast aur responsive ban jata hai.</p>
</div> 

  <div id="loader" class="d-flex justify-content-center d-none align-items-center min-vh-100">
    <div class="loader"></div>
  </div>

  <div class="container mt-1">
    <button class="btn btn-warning" id="createPostBtn">create</button>
    <button class="btn btn-primary" id="showPostBtn">show</button>
  </div>


  <div class="container mt-1" id="createPost">

    <div class="alert bg-warning d-none" id="showStatus"></div>
    <h1>ajax</h1>
    <form action="" id="form" method="post" enctype="multipart/form-data" class="w-75 mx-auto">
      @csrf
      <input type="text" id="namee" name="name" class="mt-3 form-control" placeholder="name">
      <input type="file" name="image" id="uploadFile" multiple class="mt-3 form-control">
      <img src="" id="previewFile" height="50">
      <textarea name="description" class="mt-3 form-control" placeholder="description"></textarea>
      <div class="add-fields">

      </div>
      <!-- <button type="button" id="addFile" class="btn btn-primary mt-4">Add file</button> -->
      <button type="submit" class="btn btn-primary mt-4">Submit ajax</button>
    </form>
  </div>

  <!-- show posts -->
  <div class="container  d-none " id="showPost">
    <div class="row">
      @if(count($showPosts)>0)
      @foreach($showPosts as $showPost)
      <div class="col-3" id="hideauto">
        <div class="card p-3">
          <img src="{{asset('storage/ajaximages/'.$showPost->image)}}" height="100">
          <h5 class="card-tile">{{$showPost->name}}</h5>
          <h5 class="card-tile">{{$showPost->image_size}}</h5>
          <h5 class="card-tile">{{$showPost->image_ext}}</h5>
          <div class="card-body">
            <p>{{$showPost->description}}</p>
            <button type="button" class="btn btn-warning deleteBtn" id="deleteBtn" value="{{$showPost->id}}">Delete</button>
            <button type="button" class="btn btn-warning updateBtn" id="updateBtn" value="{{$showPost->id}}">Update</button>
          </div>
        </div>
      </div>
      @endforeach
      @else
      <p>no record math.</p>
      @endif

    </div>
  </div>


  <div class="container mt-5 d-non" id="updatePost">

    <div class="alert bg-warning d-none" id=""></div>
    <h6>update</h6>
    <form action="" id="updateForm" method="post" enctype="multipart/form-data" class="w-75 mx-auto">
      @csrf
      <input type="text" name="id" id="updateId" value="" class="mt-3 form-control" placeholder="name">
      <input type="text" name="name" id="updateName" value="" class="mt-3 form-control" placeholder="name">
      <input type="file" name="image" multiple class="mt-3 form-control">
      <textarea name="description" id="updateDescription" class="mt-3 form-control" placeholder="description"></textarea>

      <div class="add-fields">
        <img id="showFile" height="200">
      </div>
      <!-- <button type="button" id="addFile" class="btn btn-primary mt-4">Add file</button> -->
      <button type="submit" id="updateBtnSubmit" class="btn btn-primary mt-4 ">Update ajax</button>
    </form>
  </div>

  <script>
    $(document).ready(function() {

      $('#uploadFile').change(function(event) {
    
        if ($(this).prop('files').length == 0) {
        return;
    } else {
        var tmpUrl = URL.createObjectURL($(this).prop('files')[0]);
        $('#previewFile').attr('src', tmpUrl);
    }



// - $(this).prop('checked'): Iska istemal checkbox element ki checked property ka value hasil karne ke liye kiya jata hai. Yaani, yeh check karta hai ke checkbox checked hai ya nahi.

// - $(this).prop('value'): Iska istemal input element ki value property ka value hasil karne ke liye kiya jata hai. Yaani, yeh input field mein likhe gaye text ko hasil karta hai.

// - $(this).prop('disabled', true): Iska istemal element ki disabled property ko true par set karne ke liye kiya jata hai. Yaani, yeh element ko disable kar deta hai, aur user uspar click nahi kar sakta.
    // $(this).prop('selected'): Iska istemal dropdown menu ke selected option ki value hasil karne ke liye kiya jata hai.
    // var file = $(this)[0].files[0];
    // if (file) {
    //   var tmpUrl = URL.createObjectURL($(this).prop('files')[0]);
    //   $('#previewFile').attr('src', tmpUrl);
    // } 

});

      // update
      $("#updateBtnSubmit").on("click", function(event) {
        event.preventDefault();
        let formData = new FormData(this.form);
        $.ajax({
          url: "{{route('update.post.ajax')}}",
          data: formData,
          type: "POST",
          contentType: false,
          processData: false,
          success: function(response) {
            console.log("updated check",response.filePath);
          },
          error: function(response) {
            console.log("updated error",response);
          }
        });


      });



      // edit
      $(".updateBtn").on("click", function() {
        let getId = $(this).val();
        $.ajax({
          url: "{{route('edit.post.ajax')}}",
          data: {
            id: getId
          },
          type: "GET",
          success: function(response) {
            console.log(response.post);
            $("#updateId").val(response.post.id);
            $("#updateName").val(response.post.name);
            console.log(response.post.image);
            $("#showFile").attr('src', "{{asset('storage/ajaximages/')}}" + '/' + response.post.image);
            $("#updateDescription").val(response.post.description);
          },
          error: function(response) {
            console.log(response);
          }
        });
      });


      //       $(".btn").on("click",function(){
      //     let value = $(this).val(); 
      //     console.log(value);
      // });
      


      $("[id='deleteBtn']").on("click", function(event) {
        // event.preventDefault();
        let id = $(this).val();
        $.ajax({
          url: "{{route('delete.post.ajax')}}",
          data: {
            id: id
          },
          type: "GET",
          success: function(response) {
            console.log("success id", response.message);
            $("#hideauto").remove();
          },
          error: function(response) {
            console.log("error id", response);
          }
        });
      });

      $("#createPostBtn").on("click", function() {
        $("#createPost").removeClass("d-none");
        $("#showPost").addClass("d-none");
      });

      $("#showPostBtn").on("click", function(event) {
        // event.preventDefault();
        $("#createPost").addClass("d-none");
        $("#loader").removeClass("d-none");

        $("#showPost").removeClass("d-none");

        $.ajax({
          url: "{{route('show.post.ajax')}}",
          type: "GET",
          success: function(response) {
            console.log("show data", response);
            localStorage.setItem("showPost", "true");
            location.reload();

          },
          error: function(response) {

          },
        });
      });

     // Aur page load hone par
      $(document).ready(function() {
        if (localStorage.getItem("showPost") == "true") {
          $("#createPost").addClass("d-none");
          $("#showPost").removeClass("d-none");
          localStorage.removeItem("showPost");
        }
      });

      // $("#body").keydown(function(event){
      // if(event.key=="Enter"){
      //   event.preventDefault();
      //   $("#form").submit();
      // }
      // });

// add post
      $("#form").on("submit", function(event) {
        event.preventDefault();
        let formData = new FormData(this);
        $.ajax({
          url: "{{route('store.post.ajax')}}",
          data: formData,
          type: 'POST',
          contentType: false,
          processData: false,
          success: function(response) {
            console.log("success osama", response.status);
            if (response.status) {
              $("#showStatus").removeClass("d-none");
              $("#showStatus").show().text(response.status);
            }
          },
          error: function(response) {
            console.log("Error osama", response.status);

          }


        });

      });

    });
  </script>
</body>

</html>