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
			return $this->objPDO->selectAll("SELECT destinationcode, destinationdescription FROM thankq_destinationcode WHERE ExcludeFromDropdown = '0' $condition", array());
		}

		function customDestinationGroupOrder($mode){
			$order = array(
				0 => "Ministries-General",
				1 => "Ministries-Global",
				2 => "Ministries-International Services",
				3 => "Ministries-Region",
				4 => "National Movement",
				5 => "Staff-Global Ministries",
				6 => "Staff-International Services",
				7 => "Staff-National Movement",
				8 => "Staff-Region"
			);
			$outputOrder = "";
			switch($mode){
				case "movement":
					$outputOrder = "4,0,1,2,3,5,6,7,8";
				break;
				case "staff":
					$outputOrder = "5,6,7,8,0,1,2,3,4";
				break;
				default:
				case "ministry":
					$outputOrder = "0,1,2,3,4,5,6,7,8";
				break;
			}


			$output = "";
			$arrOrder = explode(",", $outputOrder);
			foreach($arrOrder AS $orderVal){
				if($output != ""){
					$output .= ",";
				}
				$output .= "'".$order[$orderVal]."'";
			}
			return "(destinationgroup, $output)";
		}
	}
?>