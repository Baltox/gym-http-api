<?php include('server.php') ?>

<html>
  <head>
  </head>

  <body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>

    var instanceId;
    var baseUrl = "http://localhost:8000";

    $( document ).ready(function() {


        $.post(baseUrl+'/v1/envs/', {env_id: 'CartPole-v0'}, onEnvCreated);

        /*
        $.post(url+'/v1/envs/', {env_id: 'CartPole-v0'}, function(data) {
            var data = jQuery.parseJSON(data);

            alert( "instance_id: " + data.instance_id );

            $.get(url+'/v1/envs/', {}, function( data ) {

                alert( "envs: " + data );
            });

            $.post(url+'/v1/envs/'+data.instance_id+'/reset/', {}, function( data ) {

                alert( "reset");
            });


            $.post(url+'/v1/envs/'+data.instance_id+'/monitor/start/', {}, function( data ) {

                alert( "Data Loaded 2: " + data );
            });


            $.get(url+'/v1/envs/'+data.instance_id+'/observation_space/', {}, function( data ) {

                alert( "observation_space: " + data );
            });
        });
        */
    });

    function onEnvCreated(data)
    {
        var data = jQuery.parseJSON(data);

        instanceId = data.instance_id;

        alert('instanceId : '+instanceId);

        $.post(baseUrl+'/v1/envs/'+instanceId+'/monitor/start/', {directory: '/tmp/random-agent-results', force: true, resume: false}, onEnvStarted);
    }

    function onEnvStarted(data)
    {
        $.post(baseUrl+'/v1/envs/'+instanceId+'/reset/', {}, onEnvReseted);
    }

    function onEnvReseted(data)
    {
        $.post(baseUrl+'/v1/envs/'+instanceId+'/step/', {action: 0, render: false}, onEnvSteped);
    }

    function onEnvSteped(data)
    {
        //$.post(baseUrl+'/v1/envs/'+instanceId+'/monitor/start/', {}, onEnvStarted);
    }


    </script>
    </body>
</html>

