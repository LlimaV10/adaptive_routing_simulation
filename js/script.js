// function send_start(node_id) {
//     var messages = $("#messages").children();
//     var count_messages = messages.length;
//     var messages_weights = [];
//     var messages_first_next_coordinates = [];
//     var messages_done = [];
//     var messages_next_node = [];
//     var bool = {};
//     var i;

//     bool.result = {};
//     for (i = 0; i < count_messages; i++) {
//         messages_weights[i] = 0;
//         messages_done[i] = 0;
//         messages_next_node[i] = node_id;
//         messages_first_next_coordinates[i] = {};
//         messages_first_next_coordinates[i].x1 = messages.css('left');
//         messages_first_next_coordinates[i].y1 = messages.css('top');
//         messages_first_next_coordinates[i].x2 = 0;
//         messages_first_next_coordinates[i].y2 = 0;
//     }
//     while ($.inArray(0, messages_done) != -1) {
//         for (i = 0; i < count_messages; i++) {
//             if (messages_done[i] == 1)
//                 continue;
//             if (messages_weights[i] == 0) {
//                 bool.done = 0;
//                 get_node_way(bool, messages_next_node[i]);
//                 while (bool.done == 0) ;

//             }
//         }
//     }
//     console.log("all GOOD");
// }

// function get_node_way(d, node_id) {
//     $.ajax({
//         url:     "ajax/get_node_way.php",
//         type:    "POST",
//         dataType: "html",
//         data: "node_id=" + node_id.toString(),
//         success: function(response) {
//             d.result = $.parseJSON(response);
//             d.done = 1;
//         }
//     });
// }

// function send_start() {
//     var d = {};
//     var messages = $("#messages").children();
//     var count_messages = messages.length;

//     d.all_sent = 0;
//     while (d.all_sent == 0) {
//         for (i = 0; i < count_messages; i++) {
//             d.done = 0;
//             get_node_way(d, );
//             while (d.done == 0) ;
//         }

//     }
// }

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

function next_nodes_step1() {
    $.ajax({
        url:     "ajax/next_nodes_step.php",
        type:    "POST",
        dataType: "html",
        //data: "node_id=" + node_id.toString(),
        success: function(response) {
            console.log("success");
            result = $.parseJSON(response);
            console.log(result);
            $('#messages').html(result.messages);
            if (result.all_sent == 0)
                next_nodes_step1();
            // d.all_sent = result.all_sent;
            // d.done = 1;
        }
    });
    //d.all_sent = 1;
    //d.done = 1;
}

function next_nodes_step(d) {
    $.ajax({
        url:     "ajax/next_nodes_step.php",
        type:    "POST",
        //dataType: "html",
        //data: "node_id=" + node_id.toString(),
        success: function(response) {
            console.log("success");
            result = $.parseJSON(response);
            console.log(result);
            $('#messages').html(result.messages);
            d.all_sent = result.all_sent;
            d.done = 1;
        }
    });
    d.all_sent = 1;
    //d.done = 1;
}

function send_start() {
    var d = {};

    console.log("start");
    d.all_sent = 0;
    while (d.all_sent == 0) {
        d.done = 0;
        console.log("next");
        next_nodes_step(d);
        console.log(d);
        // console.log("wait");
        // while (d.done == 0) ;
        // console.log("wait_done");
    }
}

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
                next_nodes_step1();
            }
		}
        //,
		// error: function(response) { // Данные не отправлены
		// 	$('#result_send_message').html('Ошибка. Данные не отправлены.');
		// }
 	});
    //setTimeout(send_start, 200);
}

