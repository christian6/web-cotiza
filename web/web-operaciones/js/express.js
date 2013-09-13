var express = require('express');

var app = express.createServer();

var PORT = 8080;

app.listen(PORT, function () {
	console.log('Listening on port '+PORT);
});