<?php include('server.php') ?>

<html>
  <head>
  </head>

  <body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script>

    var instanceId;
    var baseUrl = "http://localhost:8000";
    var env = 'CartPole-v0';

    $( document ).ready(function() {
        initEnv();

        var data;

        while (data == null || data.done == false) {
            data = step(getRandomInt(2));
        }

        console.log('finished');

    });

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

    function initEnv() {
        createEnv();
        startMonitor();
        resetEnv();
    }

    function createEnv() {
        createEnvDatas = ajax('POST', '/v1/envs/', {env_id: env});

        instanceId = createEnvDatas.instance_id;
    }

    function startMonitor() {
        ajax('POST', '/v1/envs/'+instanceId+'/monitor/start/', {directory: '/tmp/random-agent-results', force: true, resume: false});
    }

    function resetEnv() {
        ajax('POST', '/v1/envs/' + instanceId + '/reset/');
    }

    function step(action) {
        data = ajax('POST', '/v1/envs/'+instanceId+'/step/', {action: action, render: true});

        return data;
    }

    function ajax(type, url, data) {
        returnAjax = $.ajax({
            type: type,
            url: baseUrl+url,
            datatype: "json",
            data: data,
            async: false
        });

        returnDatas = '';
        if(returnAjax.responseText!= '') {
            returnDatas = jQuery.parseJSON(returnAjax.responseText);
        }

        return returnDatas;
    }
    </script>
    </body>
</html>

