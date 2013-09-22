$(document).ready (function() {

	var file_path = $('#file_path').text();

	$('.img-box').mouseover(function () {

		var img = $(this).find('.gr-col');
		var ss  = file_path + 'pictures/xmin/' + img.attr('id') + '-min.jpg';

		$(this).css('background-color', 'black');
		img.attr('src', ss);
	});
	$('.img-box').mouseout(function () {

		var img = $(this).find('.gr-col');
		var ss  = file_path + 'pictures/xgray/' + img.attr('id') + '-gray.jpg';

		$(this).css('background-color', 'transparent');
		img.attr('src', ss);
	});
});