$(document).ready(function() {
    $('#openChat').click(function() {
        $('#chatContainer').show();
    });

    $('#sendMessage').click(function() {
        var message = $('#userInput').val();
        $('#chatMessages').append('<div>You: ' + message + '</div>');
        $('#userInput').val('');

        // Send message to chatbot backend
        $.post('chatbot/chatbot.php', { message: message }, function(response) {
            $('#chatMessages').append('<div>Agrina: ' + response + '</div>');
        });
    });
});
