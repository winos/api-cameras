<?php 

/**
* NearCamera Model.
*  
* this class managed the access to the table cameras
* 
* @package   Model
* @author    Dawin Ossa <dawin@neatproj.com>
* @version   0.1
*/

class NearCamera_Model
{
	/**
	 * LIMIT: limit of area expressed in KM
	 * @var const 
	 */
	const LIMIT = 0.2;
	/**
	* with this method we obtain the most near cameras.
	*
	* @param  float $lat lat of camera
	* @param  float $lon long of camera
	* @return Array info camera captured
	*/
	public function getNearCamera($data) 
	{
        $resultOperation = array();
        
        try {

            $stm = "SELECT *, 
                        (((acos(sin((" . $data['lat'] . "*pi()/180)) * sin((`cs135_lat`*pi()/180))+cos((" . $data['lat'] . "*pi()/180)) 
                        * cos((`cs135_lat`*pi()/180)) * cos(((" . $data['lon'] . " - `cs135_lon`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance
                        FROM `ts135_cameras`
                        WHERE `cs135_estado` = '15'
                        AND '" . str_replace('_', ':', $data['hourTime']) . "' BETWEEN `cs135_horario_inicial` AND `cs135_horario_final`
                        GROUP BY distance
                        HAVING distance <= " . self::LIMIT . "
                        LIMIT 1;";
/*
            echo "$stm\n";
            $bindings = array(
                            ':km' => self::LIMIT, 
                            ':lat' => $data['lat'], 
                            ':lon' => $data['lon'],
                            ':hourTime' => str_replace('_', ':', $data['hourTime'])
                            );
*/
            $resultOperation = R::getAll($stm);

        } catch (Exception $e) {
			            
        }
        return $resultOperation;
    }
}
