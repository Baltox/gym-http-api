<?php include('server.php') ?>

<html>
  <head>
  </head>

  <body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
    $( document ).ready(function() {
        var url = "http://localhost:8000";

        $.get(url+'/v1/envs/', {env_id: 'CartPole-v0'}, function( data ) {
          alert( "Data Loaded: " + data );
        });
    });
    </script>

    </body>
</html>

