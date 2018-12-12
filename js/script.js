function get_node_way(bool, node_id) {
    $.ajax({
        url:     "ajax/get_node_way.php",
        type:    "POST",
        dataType: "html",
        data: "node_id=" + node_id.toString(),
        success: function(response) {
            bool.result = $.parseJSON(response);
            bool.done = 1;
        }
    });
}

function send_start(node_id) {
    var messages = $("#messages").children();
    var count_messages = messages.length;
    var messages_weights = [];
    var messages_first_next_coordinates = [];
    var messages_done = [];
    var messages_next_node = [];
    var bool = {};
    var i;

    bool.result = {};
    for (i = 0; i < count_messages; i++) {
        messages_weights[i] = 0;
        messages_done[i] = 0;
        messages_next_node[i] = node_id;
        messages_first_next_coordinates[i] = {};
        messages_first_next_coordinates[i].x1 = messages.css('left');
        messages_first_next_coordinates[i].y1 = messages.css('top');
        messages_first_next_coordinates[i].x2 = 0;
        messages_first_next_coordinates[i].y2 = 0;
    }
    while ($.inArray(0, messages_done) != -1) {
        for (i = 0; i < count_messages; i++) {
            if (messages_done[i] == 1)
                continue;
            if (messages_weights[i] == 0) {
                bool.done = 0;
                get_node_way(bool, messages_next_node[i]);
                while (bool.done == 0) ;

            }
        }
    }
    console.log("all GOOD");
}

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

function send_message(send_message_form, url) {
    console.log($("#"+send_message_form).serialize());
	$.ajax({
		url:	 url,
		type:	 "POST",
		dataType: "html",
		data: $("#"+send_message_form).serialize(),
		success: function(response) {
			result = $.parseJSON(response);
            if (result) {
    			$('#messages').html(result.messages);
                //send_start();
            }
		}
        //,
		// error: function(response) { // Данные не отправлены
		// 	$('#result_send_message').html('Ошибка. Данные не отправлены.');
		// }
 	});
    //setTimeout(send_start, 200);
}

