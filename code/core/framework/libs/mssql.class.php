<?php
	class MSSQL{
		var $objPDO;

		function MSSQL($server, $port, $db, $user, $password){
			$objPDO = new PDO("sqlsrv:Server=$server,$port;Database=$db", $user, $password);
		}


	}
?>