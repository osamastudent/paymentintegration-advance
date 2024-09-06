<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- bootstrap cdn -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <title>Create Post</title>
</head>
<body>
<div class="container mt-5">
@if(isset($messagebody))
        <h1>{{ $messagebody }}</h1>
    @endif

@if(session('status'))
{{session('status')}}
@endif
<form action="{{route('store.post')}}" method="post" enctype="multipart/form-data" class="w-75 mx-auto">
    @csrf
    <input type="text" name="name" class="mt-3 form-control" placeholder="name">
     <textarea name="description" class="mt-3 form-control" placeholder="description"></textarea>
    <input type="file" name="image" multiple class="mt-3 form-control"> 
    <input type="file" name="myimage" class="mt-3 form-control"> 
    <div class="add-fields">

    </div>
    <!-- <button type="button" id="addFile" class="btn btn-primary mt-4">Add file</button> -->
    <button type="submit" class="btn btn-primary mt-4">Submit</button>
</form>
</div>


<script>
  $(document).ready(function(){
$("#addFile").on("click",function(){
  
  let input=`
    <input type="file" name="image[]" class="mt-3 form-control"> 
  `;
  console.log(input);
$(".add-fields").append(input);

})
  })
</script>
</body>
</html>