$(document).ready(function() {
    // Toggle chat container visibility
    $('#openChat').click(function() {
        $('#chatContainer').toggle();
    });

    // Function to get current time
    function getCurrentTime() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // Handle midnight
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var time = hours + ':' + minutes + ' ' + ampm;
        return time;
    }

    // Function to send message and receive response
    $('#sendMessage').click(function() {
        var message = $('#userInput').val().trim();
        if (message !== '') {
            var currentTime = getCurrentTime();
            $('#chatMessages').append('<div class="message user"><div class="content">' + message + '</div><small class="time">' + currentTime + '</small></div>');
            $('#userInput').val('');

            // Send message to chatbot backend
            $.post('chatbot/chatbot.php', { message: message }, function(response) {
                var currentTime = getCurrentTime();
                $('#chatMessages').append('<div class="message bot"><div class="content">' + response + '</div><small class="time">' + currentTime + '</small></div>');
            });
        }
    });
});
