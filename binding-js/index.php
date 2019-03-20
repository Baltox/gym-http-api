<?php include('server.php') ?>

<html>
  <head>
  </head>

  <body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
    $( document ).ready(function() {
        var url = "http://localhost:8000";

        $.post(url+'/v1/envs/', {env_id: 'CartPole-v0'}, function( data ) {
            var data = jQuery.parseJSON(data);

          alert( "instance_id: " + data.instance_id );

            $.get(url+'/v1/envs/', {}, function( data ) {

                alert( "envs: " + data );
            });

          /*
            $.post(url+'/v1/envs/'+data.instance_id+'/monitor/start/', {}, function( data ) {

                alert( "Data Loaded 2: " + data );
            });
            */

            $.get(url+'/v1/envs/'+data.instance_id+'/observation_space/', {}, function( data ) {

                alert( "observation_space: " + data );
            });
        });
    });
    </script>

    </body>
</html>

