function clear_all_and_start() {
	$.ajax({
		url:	 "ajax/clear_connections.php",
		type:	"POST",
		dataType: "html",
		success: function(response) {
			start_new_message_sending();
		}
	});
}

function start_new_message_sending() {
	if (type_of_routing == "Datagram")
		send_message('send_message_form', 'ajax/create_packages.php', 2);
	else if (type_of_routing == "Logical")
		send_message('send_message_form', 'ajax/create_info_packages.php', 2);
    else if (type_of_routing == "Virtual")
        send_message('send_message_form', 'ajax/create_info_packages.php', 1);
}

$( document ).ready(function() {
	$("#send").click(
		function(){
			if (sending_message == 0)
			{
				$("#send_message_form_div").css('display', 'none');
				$("#stop_simulation_button").css('display', 'block');
				$("#sending_message_info").html("Message_size: " + $("#message_size").val() +
					"<br>Max_package_size: " + $("#package_size").val());
				$("#sending_message_info").css('display', 'block');
				sending_message = 1;
				time = 0;
				messages_sent = 0;
				packages_count = 0;
				previous_message_time = 0;
				frequency = $("#frequency").val();
				count_messages = $("#count_messages").val();
				type_of_routing = $('input[name=type_of_routing]:checked').val();
				$('#messages').html("");
				clear_all_and_start();
			}
			return false; 
		}
	);
	$("#stop_simulation_button").click(
		function(){
			if (sending_message == 1)
				sending_message = 2;
		}
	);
	$("#speed_range").change(
		function() {
			$("#speed").html(this.value);
		}   
	);

	$("#save_1").click(
		function(){
			$("#saved_1").html($("#result_table").html());
		}
	);
	$("#save_2").click(
		function(){
			$("#saved_2").html($("#result_table").html());
		}
	);
	$("#save_3").click(
		function(){
			$("#saved_3").html($("#result_table").html());
		}
	);
});

function next_nodes_step(packages_count, virtual) {
	var	 i;
	var	 speed;

	if ($("#max_speed").prop('checked'))
		speed = frequency + "";
	else
		speed = $('#speed_range')[0].value;

	$.ajax({
		url:	 "ajax/next_nodes_step.php",
		type:	"POST",
		dataType: "html",
		data: "speed=" + speed + "&messages_sent=" + messages_sent +
			"&count_messages=" + count_messages + "&virtual=" + virtual + "&type_of_canal=" + $('input[name=type_of_canal]:checked').val(),
		success: function(response) {
			//console.log(response);
			//console.log("pack count " + packages_count);
			result = $.parseJSON(response);
			for (i = 0; i < packages_count; i++) {
				$('#p' + i).css({'top' : result.messages_top[i], 'left' : result.messages_left[i]});
			}
			time += parseInt(speed);

			if (result.info_packages_done.length > 0)
			{
				var len = result.info_packages_done.length;
				var info_packages_done = result.info_packages_done;
				for (var i = 0; i < len; i++) {
					//console.log(result.info_packages_done);
					packages_count = create_packages_from_info(info_packages_done[i]);
				}
				if (sending_message == 2)
				{
					$("#send_message_form_div").css('display', 'block');
					$("#stop_simulation_button").css('display', 'none');
					$("#sending_message_info").css('display', 'none');
					sending_message = 0;
					console.log("TIME: " + time);
                    draw_table();
					return;
				}
				if (messages_sent < count_messages && parseInt(time) >= parseInt(previous_message_time) + parseInt(frequency))
					start_new_message_sending();
				else
					next_nodes_step(packages_count, virtual);
				return;
			}
			// console.log(previous_message_time + frequency);
			if (sending_message == 2)
			{
				$("#send_message_form_div").css('display', 'block');
				$("#stop_simulation_button").css('display', 'none');
				$("#sending_message_info").css('display', 'none');
				sending_message = 0;
				console.log("TIME: " + time);
                draw_table();
				return;
			}
			if (messages_sent < count_messages && parseInt(time) >= parseInt(previous_message_time) + parseInt(frequency))
			{
				start_new_message_sending();
				return;
			}
			if (result.all_sent == 0)
				next_nodes_step(packages_count, virtual);
			else
			{    
				$("#send_message_form_div").css('display', 'block');
				$("#stop_simulation_button").css('display', 'none');
				$("#sending_message_info").css('display', 'none');
				sending_message = 0;
				console.log("TIME: " + time);
                draw_table();
			}
		}
	});
}

function create_packages_from_info(id){
	$.ajax({
		url:	 "ajax/create_packages_from_info.php",
		type:	"POST",
		dataType: "html",
		data: $("#send_message_form").serialize() + "&packages_count=" + packages_count
			+ "&info_id=" + id,
		async:false,
		success: function(response) {
			//console.log("id: " + id);
			//console.log(response);
			result = $.parseJSON(response);
			if (result) {
				packages_count += result.packages_count;
				$('#messages').append(result.messages);
			}
		}
	});
	return (packages_count);
}

function send_message(send_message_form, url, virtual) {
	//console.log($("#"+send_message_form).serialize()+ "&packages_count=" + packages_count);

	$.ajax({
		url:	 url,
		type:	 "POST",
		dataType: "html",
		data: $("#"+send_message_form).serialize() + "&packages_count=" + packages_count,
		success: function(response) {
			//console.log(response);
			result = $.parseJSON(response);
			//console.log(result);
			if (result) {
				$('#messages').append(result.messages);
				messages_sent++;
				previous_message_time = time;
				packages_count += result.packages_count;
				next_nodes_step(packages_count, virtual);
			}
		}
		//,
		// error: function(response) { // Данные не отправлены
		// 	$('#result_send_message').html('Ошибка. Данные не отправлены.');
		// }
 	});
}

function draw_table() {
    $.ajax({
        url:     "ajax/result_table.php",
        type:    "POST",
        dataType: "html",
        data: "time=" + time + "&messages=" + $("#count_messages").val() + "&message_size=" + $("#message_size").val() +
        "&type_of_routing=" + $('input[name=type_of_routing]:checked').val(),
        success: function(response) {   
            //console.log(response);
            result = $.parseJSON(response);
            if (result) {
                $('#result_table').html(result.table);
            }
        }
    });
}