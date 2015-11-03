$(document).ready(function() {
	$('.pd_opt_open').click(function() {
		$('.price_opt').toggle("","swing");
	});

	//datepicker start
	$(".opt_date").datepicker({
		dateFormat: 'yy-mm-dd'
	});

	$(".opt_time").timepicker();
});


function setAllForm() {
	$(".pd_name").val("title sample");
	$(".pd_shortDesc").val("shortDesc sample");
	$(".pd_price").val("9999");
	$(".pd_opt").val("Option title");
	$(".pd_opt_price").val("7777");


}