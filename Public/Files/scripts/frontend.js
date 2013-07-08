$(document).ready (function() {

	var file_path = $('#file_path').text();

	$('.gr-col').mouseover(function () {
		var ss = file_path + 'pictures/xmin/' + $(this).attr('id') + '-min.jpg';
		$(this).attr('src', ss);
		$(this).parents('img-box').css('background-color', 'black');
	});

	$('.gr-col').mouseout(function () {
		var ss = file_path + 'pictures/xgray/' + $(this).attr('id') + '-gray.jpg';
		$(this).attr('src', ss);
		$(this).parents('img-box').css('background-color', 'transporent');
	});
});