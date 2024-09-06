<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <title>send-data-viewa</title>
  
</head>
<body>
    <div style="background-image: url('cid:background-image.png'); background-repeat: no-repeat; background-size: cover; height: 500px; width: 100%;">
        <div class="container mt-5">

            <div class="card mt-3 p-4 w-75 mx-auto">
                
            <img src="cid:image.png" class="img-fluid">
                
                <div class="card-body">
                    <h5 class="card-title">{{$subject}}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
                    <p class="card-text">{{$body}}</p>
                    <a href="#" class="card-link">{{$subject}}</a>
                    <a href="http://127.0.0.1:8000/show-post" target="_blank" class="card-link">Another link</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

