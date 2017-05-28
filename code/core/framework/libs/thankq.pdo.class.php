<?php
	require_once DIR_LIBS.'/pdo.class.php';
	require_once DIR_FRAMEWORK.'/config/thankq.config.php';

	class ThankQPDO{
		var $objPDO;

		function __construct(){
			$this->objPDO = new OZPDO(array(
				"mode" => "mysql",
				"server" => THANKQ_HOST,
				"port" => THANKQ_PORT,
				"db" => THANKQ_DB,
				"user" => THANKQ_USERNAME,
				"password" => THANKQ_PASSWORD,
				"ssl_ca_cert" => THANKQ_CA_CERT
			));
		}

		function listOfferingEvents(){
			return $this->objPDO->selectAll("SELECT SOURCECODE, SOURCEDESCRIPTION FROM thankq_sourcecode WHERE SOURCETYPE = 'Event' AND ExcludeFromDropdown = '0' GROUP BY SOURCECODE ORDER BY SOURCEDESCRIPTION ASC", array());
		}

		function listDestinationCodes($condition){
			return $this->objPDO->selectAll("SELECT destinationcode, destinationdescription FROM thankq_destinationcode WHERE ExcludeFromDropdown = '0' $condition ORDER BY destinationdescription ASC", array());
		}
	}
?>