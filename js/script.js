$( document ).ready(function() {
    $("#send").click(
        function(){
            send_message('send_message_form', 'ajax/create_packages.php');
            return false; 
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
            if (result.all_sent == 0)
                next_nodes_step(packages_count);
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

