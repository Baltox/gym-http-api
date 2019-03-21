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
    });

    function onEnvCreated(data)
    {
        var data = jQuery.parseJSON(data);

        instanceId = data.instance_id;

        $.post(baseUrl+'/v1/envs/'+instanceId+'/monitor/start/', {directory: '/tmp/random-agent-results', force: true, resume: false}, onEnvStarted);
    }

    function onEnvStarted(data)
    {
        $.post(baseUrl+'/v1/envs/'+instanceId+'/reset/', {}, onEnvReseted);
    }

    function onEnvReseted(data)
    {
        console.log(data);

        $.post(baseUrl+'/v1/envs/'+instanceId+'/step/', {action: 0, render: true}, onEnvSteped);
    }

    function onEnvSteped(data)
    {
        console.log(data);

        //$.post(baseUrl+'/v1/envs/'+instanceId+'/step/', {action: 1, render: true}, onEnvSteped);
    }


    </script>
    </body>
</html>

