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
        var data = null;
        while(data == null || data.done == false) {
            var returnAjax = $.ajax({
                type: "POST",
                url: baseUrl+'/v1/envs/'+instanceId+'/step/',
                datatype: "json",
                data: {action: getRandomInt(2), render: true},
                async: false
            });

            data = jQuery.parseJSON(returnAjax.responseText);

            sleep(5);
        }
    }

    function onEnvSteped(data)
    {
        //$.post(baseUrl+'/v1/envs/'+instanceId+'/step/', {action: 1, render: true}, onEnvSteped);
    }

    function sleep(milliseconds) {
        var start = new Date().getTime();
        for (var i = 0; i < 1e7; i++) {
            if ((new Date().getTime() - start) > milliseconds){
                break;
            }
        }
    }

    function getRandomInt(max) {
        return Math.floor(Math.random() * Math.floor(max));
    }


    </script>
    </body>
</html>

