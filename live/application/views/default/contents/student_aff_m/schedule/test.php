<div id="container">Loading ...</div>
<script src="<?php echo base_url(); ?>socket.io/node_modules/socket.io/node_modules/socket.io-client/socket.io.js"></script>
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script>
    // create a new websocket
    var socket = io.connect('http://idbuild.id.dyned.com:8080');
    console.log(socket);
    // on message received we print all the data inside the #container div
    socket.on('notification', function (data) {
        var usersList = "<dl>";
        $.each(data.user_notifications, function (index, user) {
            usersList += "<dt>" + user.user_id + "</dt>\n" +
                    "<dd>" + user.description + "\n";
        });
        usersList += "</dl>";
        $('#container').html(usersList);

        $('time').html('Last Update:' + data.time);
    });
</script>