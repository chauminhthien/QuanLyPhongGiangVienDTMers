var express = require("express");

var app 	= express();
var server 	= require("http").Server(app);
var io 		= require('socket.io')(server);

// server.listen(process.env.PORT || 3100);
server.listen(3100);

io.on('connection', function(socket){
	console.log("client connected");
	
});


app.get("/", function(req, res){ // tạo router cho người dùng vào
	res.send('welcome to server socket.io');
});

console.log("connectd");