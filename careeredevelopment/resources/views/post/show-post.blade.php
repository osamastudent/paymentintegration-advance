<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- bootstrap cdn -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <title>show Post</title>
</head>
<body>
<div class="container mt-5">

@if(session('status'))
{{session('status')}}
@endif
<div class="row">
<!-- isGreaterThanOrEqualTo(0) -->
  @if(count($showPosts) )
  @foreach($showPosts as $showPost)
  <div class="col-3">
<div class="card border-0">
  <h5>{{$showPost->name}}</h5>
  <img src="{{asset('storage/postsImages/'.$showPost->image)}}" height="200">
  <img src="{{asset('storage/postsImages/'.$showPost->image_ext)}}" height="200">
  <p>{{$showPost->description}}</p>
  <a href="{{route('delete.post',$showPost->id)}}">Delete</a>
  <a href="{{route('delete.all.post')}}">Delete All</a>
  <a href="{{route('edit.post',$showPost->id)}}">Edit</a>
</div>
  </div>
  @endforeach
  @else
  <p>No Post Found.</p>
  @endif
<h1>osama@gmail.com</h1>
<h1>osamajanab9999@gmail.com</h1>
<h1>send emai</h1>
  <form action="{{route('sendEmailToUser')}}" method="post" enctype="multipart/form-data">
  @csrf
                <input type="text" name="name" class="form-control mt-3" autocomplete="off" placeholder="Name">
                <input type="text" name="email" class="form-control mt-3" autocomplete="off" placeholder="Email">
                <textarea cols="30" rows="5" name="body" class="form-control mt-3" autocomplete="off" placeholder="Message"></textarea>
                <input type="text" name="subject" class="form-control mt-3" autocomplete="off" placeholder="subject">
                <input type="file" name="myfiles[]" multiple  class="form-control mt-3">
                <input type="submit" name="" value="Send Data" class="btn btn-danger mt-3">
  </form>

</div>
</div>
</body>
</html>