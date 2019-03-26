# gym-http-api: JavaScript Binding

## Local
```
python3 gym_http_server.py
```

```
cd binding-js && php -S localhost:8000 index.php
```

Access : [http://localhost:8000](http://localhost:8000)

## Building

```
npm install
gulp
```

## Example

This should be run from the `binding-js` directory.

```javascript
var gym = require("./dist/lib/gymHTTPClient")
var client = new gym.default("http://127.0.0.1:5000")

var p = client.envCreate("CartPole-v0");
p.then((reply) => console.log("Reply: " + JSON.stringify(reply)))
p.catch((error) => console.log("Error : " + error))
```

After building the library, you can also run the example agent with `node dist/examples/exampleAgent.js`. 

