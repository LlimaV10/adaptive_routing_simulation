$( document ).ready(function() {
    $("#send").click(
        function(){
            if (sending_message == 0)
            {
                $("#send_message_form_div").css('display', 'none');
                $("#stop_simulation_button").css('display', 'block');
                $("#sending_message_info").html("Message_size: " + $("#message_size").val() +
                    "<br>Max_package_size: " + $("#package_size").val() +
                    "<br>From_workstation № " + $("#from_station").val() +
                    "<br>To_workstation № " + $("#to_station").val());
                $("#sending_message_info").css('display', 'block');
                sending_message = 1;
                send_message('send_message_form', 'ajax/create_packages.php');
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
});

function next_nodes_step(packages_count) {
    var     i;
    var     speed = $('#speed_range')[0].value;

    $.ajax({
        url:     "ajax/next_nodes_step.php",
        type:    "POST",
        dataType: "html",
        data: "speed=" + speed,
        success: function(response) {
            result = $.parseJSON(response);
            for (i = 0; i < packages_count; i++) {
                $('#p' + i).css({'top' : result.messages_top[i], 'left' : result.messages_left[i]});
            }
            if (sending_message == 2)
            {
                $("#send_message_form_div").css('display', 'block');
                $("#stop_simulation_button").css('display', 'none');
                $("#sending_message_info").css('display', 'none');
                sending_message = 0;
                return;
            }
            if (result.all_sent == 0)
                next_nodes_step(packages_count);
            else
            {
                $("#send_message_form_div").css('display', 'block');
                $("#stop_simulation_button").css('display', 'none');
                $("#sending_message_info").css('display', 'none');
                sending_message = 0;
            }
        }
    });
}

function send_message(send_message_form, url) {
	$.ajax({
		url:	 url,
		type:	 "POST",
		dataType: "html",
		data: $("#"+send_message_form).serialize(),
		success: function(response) {
			result = $.parseJSON(response);
            if (result) {
    			$('#messages').html(result.messages);
                next_nodes_step(result.packages_count);
            }
		}
        //,
		// error: function(response) { // Данные не отправлены
		// 	$('#result_send_message').html('Ошибка. Данные не отправлены.');
		// }
 	});
}

