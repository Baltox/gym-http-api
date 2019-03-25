<?php include('server.php') ?>

<html>
  <head>
  </head>

  <body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://unpkg.com/@tensorflow/tfjs"></script>

    <script>

    var instanceId;
    var baseUrl = "http://localhost:8000";
    var env = 'CartPole-v0';

    // Create the model
    // Input
    const input = tf.input({batchShape: [null, 4]});
    // Hidden layer
    const layer = tf.layers.dense({useBias: true, units: 32, activation: 'relu'}).apply(input);
    // Output layer
    const output = tf.layers.dense({useBias: true, units: 3, activation: 'linear'}).apply(layer);
    // Create the model
    const model = tf.model({inputs: input, outputs: output});
    // Optimize
    let model_optimizer = tf.train.adam(0.01);

    $( document ).ready(function() {
        train();
    });

    function train() {
        let eps = 1.0;
        // Used to store the experiences
        let states = [];
        let rewards = [];
        let reward_mean = [];
        let next_states = [];
        let actions = [];

        let st;
        let st2;

        initEnv();

        //TODO
        //for (let epi=0; epi < 150; epi++){
        for (let epi=0; epi < 5; epi++){
            let reward = 0;
            let step = 0;

            initialState = resetEnv();

            st = initialState.observation;

            while (step < 400) {
                let act = pickAction(st, eps);

                state = stepEnv(act);

                reward = (state.done == false ? 1 : -1);

                st2 = state.observation;

                let mask = [0, 0];
                mask[act] = 1;

                // Randomly insert the new transition tuple
                let index = Math.floor(Math.random() * states.length);
                states.splice(index, 0, st);
                rewards.splice(index, 0, [reward]);
                reward_mean.splice(index, 0, reward)
                next_states.splice(index, 0, st2);
                actions.splice(index, 0, mask);
                // Be sure to keep the size of the dataset under 10000 transitions
                if (states.length > 10000){
                    states = states.slice(1, states.length);
                    rewards = rewards.slice(1, rewards.length);
                    reward_mean = reward_mean.slice(1, reward_mean.length);
                    next_states = next_states.slice(1, next_states.length);
                    actions = actions.slice(1, actions.length);
                }
                st = st2;
                step += 1;

                //Boucle de fin
                if(state.done) {
                    break;
                }
            }

            // Decrease epsilon
            eps = Math.max(0.1, eps*0.99);
            // Train model every 5 episodes
            if (epi % 5 == 0){
                console.log("---------------");
                console.log("rewards mean", mean(reward_mean));
                console.log("episode", epi);

                //TODO à finir
                //await train_model(states, actions, rewards, next_states);
                //await tf.nextFrame();
            }
        }

    }
    
    
    function pickAction(st, eps) {
        let st_tensor = tf.tensor([st]);
        let act;

        if (Math.random() < eps){ // Pick a random action
            act = Math.floor(Math.random()*2);
        }
        else {
            let result = model.predict(st_tensor);
            let argmax = result.argMax(1);

            if(argmax.buffer().values != undefined) {
                act = argmax.buffer().values[0];
            } else {
                act = Math.floor(Math.random()*2);
            }

            argmax.dispose();
            result.dispose();
        }
        st_tensor.dispose();
        return act;
    }

    function mean(array){
        if (array.length == 0)
            return null;
        var sum = array.reduce(function(a, b) { return a + b; });
        var avg = sum / array.length;
        return avg;
    }


    ///////////////// Manage env /////////////////
    function initEnv() {
        createEnv();
        startMonitor();
    }

    function createEnv() {
        createEnvDatas = ajax('POST', '/v1/envs/', {env_id: env});

        instanceId = createEnvDatas.instance_id;

        return createEnvDatas;
    }

    function startMonitor() {
        return ajax('POST', '/v1/envs/'+instanceId+'/monitor/start/', {directory: '/tmp/random-agent-results', force: true, resume: false});
    }

    function resetEnv() {
        return ajax('POST', '/v1/envs/' + instanceId + '/reset/');
    }

    function stepEnv(action) {
        //TODO passer render a true
        return ajax('POST', '/v1/envs/'+instanceId+'/step/', {action: action, render: false});
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
            try {
                returnDatas = jQuery.parseJSON(returnAjax.responseText);
            } catch (e) {
                console.log('erreur ajax');
                console.log(returnAjax.responseText);
            }
        }

        return returnDatas;
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

