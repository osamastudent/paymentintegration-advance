<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<meta name="description" content="description">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="author" content="author">
	<title>OpenAI</title>
</head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<body class="bg-dark">
	<div class="conatiner m-5 p-5">
		<div class="row m-5 p-5 border">
			<div class="col-lg-8 offset-lg-2">
				<form id="ask">
					<h1 class="text-white">Ask ChatGPT</h1>
					<div class="form-group">
						<input type="text" class="form-control" name="question" id="question">
						<div class="text-white" id="question_help"></div>
					</div>
					<button type="submit" class="btn btn-primary">Ask ChatGPT</button>
				</form>
			</div>

			<div class="col-lg-8 offset-lg-2 mt-5" id="chat">
			</div>

		</div>
	</div>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script type="text/javascript">
	$.ajaxSetup({
		headers : {
			'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
		}
	})


	$('#ask').submit(function(event){
		event.preventDefault();
		var form = $('#ask')[0];
		var formData = new FormData(form);

		$.ajax({
			url : '/question',
			type : 'POST',
			data : formData,
            _token: "{{ csrf_token() }}",
			contentType : false,
			processData : false,

			success: function(date_add)
			{
				refresh();
				var divQuestion = '<div class="bg-white rounded"><h4 class="p-2">Question : '+data.question+'</h6></div>';
				var divAnswer = '<h5 class="text-white">Answer </h5><div class="bg-white rounded mb-3"><textarea class="w-100 form-control h-auto">'+data.answer+'</textarea></div>';
				$('#chat').append(divQuestion);
				$('#chat').append(divAnswer);
			},
			error: function(reject)
			{
				refresh();
				if(reject.status = 422){
					var errors = $.parseJSON(reject.responseText);
					$.each(errors.errors , function(key, value){
						$('#'+ key + '_help' ).text(value[0]);
					})
				}
			}
		});
	});

	function refresh()
	{
		$('#question_help').text('');
	}


</script>

</html>
