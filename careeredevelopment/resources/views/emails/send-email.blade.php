<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  
    <title>shippedform </title>
</head>
<body>
<div class="container">
<div class="card mt-5">
<div class="card-header">
<h1>Send Email Using Markdown</h1>
<span>copy: osamajanab9999@gmail.com</span>
</div>
<div class="card-body">
<form action="{{route('markdownTemplatesendemail')}}" method="post" enctype="multipart/form-data">
    @csrf
<input type="text" name="name" class="form-control mt-3" autocomplete="off"  placeholder="Name">
<input type="text" name="email" class="form-control mt-3" autocomplete="off"  placeholder="Email">
<textarea cols="30" rows="5"  name="body" class="form-control mt-3" autocomplete="off"  placeholder="Message"></textarea>
<input type="text" name="subject" class="form-control mt-3" autocomplete="off"  placeholder="subject">
<input type="file" name="myfiles[]" multiple class="form-control mt-3">
<input type="submit" name="" value="Send Data" class="btn btn-danger mt-3">
</form>
</div>
</div>
</div>
</body>
</html>