<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- bootstrap cdn -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <title>show Post Ajax</title>
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
          <img src="{{asset('storage/ajaximages/'.$showPost->image)}}" height="200">
          <img src="{{asset('storage/ajaximages/'.$showPost->image_ext)}}" height="200">
          <p>{{$showPost->description}}</p>
          <a href="{{route('delete.post',$showPost->id)}}">Delete</a>
          <a href="{{route('delete.all.post')}}">Delete All</a>
          <a href="{{route('edit.post',$showPost->id)}}">Edit</a>
        </div>
      </div>


    </div>
  </div>
</body>

</html>