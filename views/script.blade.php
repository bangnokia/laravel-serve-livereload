<!-- Live reload script -->
<script>
    (new WebSocket('ws://{{$host}}:{{$port}}')).onmessage = function (message) {
        if (message.data === 'reload') {
            window.location.reload(true);
        }
    };
</script>