// View calls infinite scroll
var offset = 0;
var kill_scroll = 0;
var load_time;
var helper = 2;
var temp_data;
var timeParent;
var sort = "";
var obj = "";
var ids = "";
var processing = false;
$(document).ready(function() {
	offset = 0;
	var idk = $('#pozovi').attr('name');
	var agent = $('#agent_id').val();
	$('.ajax-calls').load('../ajax/calls.php?id='+idk);
	$('.ajax-agent-calls').load('../ajax/calls_agent.php?id='+agent);
	toastr.options = {
		"positionClass": "toast-bottom-right",
		"showDuration": "300",
		"hideDuration": "1000",
		"timeOut": "2000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	}
});

$(document).ajaxStart(function() {
	 load_time = setTimeout(function() {
     Pace.restart();
 	},600);
 });
/*
$(document).ajaxStart(function(){
	load_time = setTimeout(function() {
    $('.loader-div').css('display', 'block');
	},500);
});
*/
$(document).ajaxStop(function(){
	clearTimeout(load_time);
    $('.loader-div').css('display', 'none');
});

$(document).on('click', '.set_arrival', function(e) {
	e.preventDefault();
	timeParent = $(this).parent();
	ids = $(this).attr('id');
	obj = $(this).parent().parent().find('.status-td');
	$('#setModal').modal('show');
	$('#timepicker1').timepicker({
		template: false,
		minuteStep: 1,
		showMeridian: false,
		maxHours: 24,
		showSeconds: true
	});
	/*
	var id = $(this).attr('id');
	var obj = $(this).parent().parent().find('.status-td');
	$.ajax({
		url: 'ajax.php',
		method: 'POST',
		data: {
			arrived: 1,
			id: id
		},
		success: function(response) {
			$(obj).html(response);
			toastr.success('Radnik prijavljen!');
		}
	});


	Date.prototype.timeNow = function () {
     return ((this.getHours() < 10)?"0":"") + this.getHours() +":"+ ((this.getMinutes() < 10)?"0":"") + this.getMinutes() +":"+ ((this.getSeconds() < 10)?"0":"") + this.getSeconds();
	}
	var date = new Date();
	$(this).parent().html(date.timeNow());
	*/
})
$(document).on('click', '#save', function(e) {
	var timeSelected = $('#timepicker1').val();
	$.ajax({
		url: 'ajax.php',
		method: 'POST',
		data: {
			arrived: 1,
			id: ids,
			time: timeSelected
		},
		success: function(response) {
			$(obj).html(response);
			$(timeParent).html(timeSelected);
			toastr.success('Radnik prijavljen!');
		}
	});
});
//PROFILE AJAX
$(document).on('click', '.profile-btn', function() {
	var id = $(this).attr('name');
	//temp_data = $('#search_clients').serializeArray();
	//console.log(temp_data[0]['name']);
	//window.document.location='profile_customer.php?id='+id;
	$('#cst-view').css('display', 'none');
	$('#profile-ajax').load('../ajax/customer_ajax.php?id='+id).css('display', 'block');
});
$(document).on('click', '#back-btn', function() {
	$('#profile-ajax').css('display', 'none');
	$('#cst-view').css('display', 'block');
});
//------------------------------------------------------------------------------->
//Add memebers------------------------------------------------------------------->
$(document).on('click', '#add_c', function() {
	$('section.content').load('add_worker.php');
});
$(document).on('click', '#add_a', function(e) {
	$('section.content').load('add_customer.php?agent');
});
$(document).on('click', '#add_m', function(e) {
	$('section.content').load('add_customer.php?mod');
});

$(document).on('click', '.delete', function(e) {
	e.cancelBubble = true;
	e.stopPropagation();
	var conf = confirm('Želite li obrisati agenta?');
	if(conf) {
		var id = $(this).attr('name');
		window.location.replace('agents.php?del='+id);
	}
});
$(document).on('click', '.delete_c', function(e) {
	e.cancelBubble = true;
	e.stopPropagation();
	var conf = confirm('Želite li obrisati klijenta');
	if(conf) {
		var id = $(this).attr('name');
		window.location.replace('view.php?users&del='+id);
	}
});
$(document).on('submit', '#search_clients', function(e) {
	e.preventDefault();
	$.ajax({
			type: "POST",
			url: "search.php",
			data: $('#search_clients').serialize(),
			success: function(response) {
				if(response) {
					$('#clients_table').html(response);
				}
			}
		});
});
//UPDATES-----------------------------------------------------------------------
$(document).on('click', '.edit_c', function(e) {
	e.preventDefault();
	var id = $(this).attr('name');
	$('.content').load('update.php?id='+id);
});

$(document).on('click', '#update-agent', function(e) {
	e.preventDefault();
	var id = $(this).attr('name');
	$('#profile-ajax').load('mods.php?id='+id);
});


//----------------------------------------------------------------------------->
//PAGINACIJA
$(document).on('click', '.pagge', function(e) {
	alert('kk');
	e.preventDefault();
	if(!processing) {
		processing = true;
	}
	else {
		return 0;
	}
	var mylink = this.href;
	var get = mylink.split('?');
	var search = "&client_search="+$('#client_search').val();
	var kampanja = "&kampanje="+$('#kampanje').val();
	var sort_what = $('.sort-all').attr('name');
	if($('#grad_id').val()) {
		var grad = "&grad_id="+$('#grad_id').val();
	}
	else grad = "";
	data_new = encodeURI(get[1] + search + kampanja + grad + "&sort=" + sort + "&sort_what=" + sort_what);

	$.ajax({
			type: "GET",
			url: 'search.php',
			data: data_new,
			success: function(response) {
				if(response) {
					$('#clients_table').html(response);
					processing = false;
				}
			}
		});
});
$(document).on('click', '.camp-page', function(e) {
	e.preventDefault();
	$('section.content').load(this.href);
});

$(document).on('submit', '#search_members', function(e) {
	e.preventDefault();
	$.ajax({
			type: "POST",
			url: "search.php?members",
			data: $('#search_members').serialize(),
			success: function(response) {
				if(response) {
					$('#members_table').html(response);
				}
			}
		});
});
function toggleonfoff_init() {
    $("div.input.toggle-onoff input:hidden").parent("div").children("i.fa").removeClass("fa-toggle-on").addClass("fa-toggle-off");
    $("div.input.toggle-onoff input:hidden[value='True']").parent("div").children("i.fa").removeClass("fa-toggle-off").addClass("fa-toggle-on");
}

//SORTING
$(document).on('click', '.active-toggle', function(e) {
	e.preventDefault();
	e.cancelBubble = true;
	e.stopPropagation();
	var klasa = $(this).attr('class');
	var id = $(this).parents('.view-agent').attr('id');
	if(!id) {
		alert('Error: No id!');
		return;
	}
	if(klasa == 'fa fa-toggle-off active-toggle') {
		var act = 1;
		$(this).attr('class', 'fa fa-toggle-on active-toggle');
	}
	else {
		var act = 0;
		$(this).attr('class', 'fa fa-toggle-off active-toggle');
	}
		$.ajax({
			type: "POST",
			url: "../ajax/helper.php",
			data: {agent_id: id, agent_active: act},
			success: function(response) {
				if(response == 1) {
					if(act == 1)
						toastr.success('Agent aktiviran!');
					else
						toastr.info('Agent deaktiviran!');
				}
			}
		});
});
$(document).on('click', '.sort-all', function() {
	if(sort == "down") {
		sort = "up";
		$(this).attr('class', 'fa fa-sort-up sort-all');
	}
	else {
		sort = "down";
		$(this).attr('class', 'fa fa-sort-down sort-all');
	}
	sort_what = $(this).attr('name');
	$.ajax({
			type: "POST",
			url: "search.php",
			data: $('#search_clients').serialize() + "&sort=" + sort + "&sort_what=" + sort_what,
			success: function(response) {
				if(response) {
					$('#clients_table').html(response);
				}
			}
		});
});
$(document).on("click", "div.input.toggle-onoff", function() {
	var camp = $(this).attr('id');
	$('.confirm-act-camp-da').attr('id',camp);
	$('.confirm-act-camp-da').attr('state',$(this).find('.togg-camp').attr('state'));
	$('.camp-active').modal('show');
});
$(document).on('click', '.confirm-act-camp-da', function() {
	$('.camp-active').modal('hide');
	var id  = $(this).attr('id');
	var stt = $(this).attr('state');
	$.ajax({
		type: "POST",
			url: "../pages/campaigns.php",
			data: {ch_status: id, state: stt},
			success: function(response) {
				if(response) {
					$('section.content').html(response);
				}
			}
	});
});
$(document).ready(function() {
    toggleonfoff_init();
});
