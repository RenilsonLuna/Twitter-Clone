<?php  

namespace MF\Model;
use App\Connection;
class Container
{
	public static function getModel($model)
	{
		// criando modelo
		$class = "\\App\\Models\\".ucfirst($model);
		// instanciando conexao
		$conn = Connection::getDb();
		// criando modelo com conexao
		return new $class($conn); 
	}
}

?>