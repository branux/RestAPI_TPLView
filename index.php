<?php
	header('Access-Control-Allow-Origin: *');
	require('Router.php');
	//Router::dirStatic("restPHP");

//TEMPLATES
	Router::get('/', function (){
		Router::make("home.html");
	});

	Router::get('/contact', function () {
		echo 'PÁGINA CONTATO';
	});

	
//APLICANDO TEMPLATE VIEW (este é o padrão de flags para tpl #{}# )
 	Router::get('/user/:nomeUser', function($data){
		$flags    =  array("#{desenvolvedor}#" => $data["nomeUser"]);
		Router::make("home.html",$flags);
	});
	
	
//REST API
	Router::get('/users', function () {
		echo 'DEVOLVER TODOS USUÁRIOS';
	});


	Router::post('/user', function(){
		echo 'RECUPERANDO POSTS:';
		print_r($_POST);
	});


	Router::put('/user', function($data){
		echo 'NOVOS DADOS DO USUÁRIO PARA ALTERAÇÃO:';
		print_r($data);
	});

	Router::delete('/user/:id', function($data){
		echo 'APAGAR USUÁRIO PELO ID: ' . $data['id'];
	});
?>