<script src="//js.pusher.com/3.1/pusher.min.js"></script>
<script>
    var pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
        encrypted: true,
        cluster: "eu"
    });

    // Subscribe to the channel we specified in our Laravel Event
    var channel = pusher.subscribe('notifs');

    // Bind a function to a Event (the full Laravel class)
    channel.bind('App\\Events\\NotificationReceived', function(data) {
        Livewire.emit("new_notif", )
    });
</script>