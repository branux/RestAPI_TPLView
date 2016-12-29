<?php
	/**
	 * Class Router
	 * Trata rotas de template, api rest (get,post,put,delete)
	 * e monta template com técnica de template view #{ }#
	 */
	class Router
	{
		protected static $params = [];
		protected static $folder=null;
		
		
		/**
		 * Traduz uma rota, verifica se ela possui atributos, então pega tudo e envia em forma de array para
		 * o callback, sendo um array com os valores dos parametros da rota
		 * @param $_url - rota
		 * @param $_callback - função para retorno
		 */
		private static function translateURL($_url, $_callback)
		{
			//Se a url contém :, significa que parÂmetros foram informados
			if(  strstr( $_url, ":" ) )
			{
				//Rota que o Método esta aguardando
				$url           = explode("/", $_url);
				$totalParams   = count($url);
				
				//Transforma url em array
				$uri           =  str_replace(self::$folder, "", $_SERVER ['REQUEST_URI']);
				$request       =  explode("/", $uri);
				$totalRequest  =  count($request);
			
				//Teste para verificar se a url atual tem relaçação com alguma rota aguardada
				if( $totalParams == $totalRequest and self::compare($url, $request) == true )
				{
					//Recupera os parÂmetros e coloca dentro do atributo sel::$params
					for( $x=1; $x<$totalParams; $x++ )if(  strstr($url[$x],":") )self::$params[substr($url[$x], 1)] = urldecode($request[$x]);
					
					//Chamo o callback e devolvo todos os parâmetros recuperados
					call_user_func($_callback, self::$params);
				}
			}
			
			//Quando for PUT e também a rota esperada, devolver o fluxo de entrada do php
			if( $_SERVER['REQUEST_METHOD'] == "PUT" and $_SERVER ['REQUEST_URI'] == self::$folder.$_url )
			{
				parse_str(file_get_contents("php://input"), self::$params);
				call_user_func($_callback, self::$params);
			}
			//Quando a url não possuir parâmetros nem o método ser PUT, mas a rota ser a esperada
			elseif(  $_SERVER ['REQUEST_URI'] == self::$folder.$_url )call_user_func($_callback);
		}

		
		/**
		 * Comprarar cada dado da url.
		 * Preciso saber se a url informada tem relação com a rota desejada
		 * Para não acontecer de acionar mais de uma rota
		 */
		private static function compare($_url, $_request)
		{
			for( $x=1; $x < count($_url); $x++ )
			{
				if( strstr($_url[$x],":") or $_url[$x] == $_request[$x] ){
				}else{
					return false;
				}
			}
			return true;
		}

		
		/**
		 * Manipula uma rota com método GET
		 * @param $_url - rota
		 * @param $_callback - função para retorno
		 */
 		public static function get($_url, $_callback)
		{
			if( $_SERVER['REQUEST_METHOD'] == "GET" ){
				self::translateURL($_url, $_callback);
			}
		}

		
		/**
		 * Manipula uma rota com método POST
		 * @param $_url - rota
		 * @param $_callback - função para retorno
		 */
		public static function post($_url, $_callback)
		{
			if( $_SERVER['REQUEST_METHOD'] == "POST" )self::translateURL($_url, $_callback);
		}

		
		/**
		 * Manipula uma rota com método PUT
		 * @param $_url - rota
		 * @param $_callback - função para retorno
		 */
		public static function put($_url, $_callback)
		{
			if( $_SERVER['REQUEST_METHOD'] == "PUT" )self::translateURL($_url, $_callback);
		}

		
		/**
		 * Manipula uma rota com método DELETE
		 * @param $_url - rota
		 * @param $_callback - função para retorno
		 */
		public static function delete($_url, $_callback)
		{
			if( $_SERVER['REQUEST_METHOD'] == "DELETE" )self::translateURL($_url, $_callback);
		}
		
		
		/**
		 * Define diretório root
		 */
		public static function dirStatic($_dir)
		{
			self::$folder = "/".$_dir;
		}
		
		
		/**
		 * Monta template com ou sem a técnica de template view
		 *
		 * @param $_tpl    - caminho do template (OBRIGATÓRIO)
		 * @param $_flags  - array de flags para replace no template view ( NÃO OBRIGATÓRIO)
		 */
		public static function make($_tpl, $_flags=null)
		{
			if($_flags == null){
				echo file_get_contents($_tpl);
			}else{
				echo str_replace(array_keys($_flags), array_values($_flags), file_get_contents($_tpl) );
			}
		}
	}
?>
