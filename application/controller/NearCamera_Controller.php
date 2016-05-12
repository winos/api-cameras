<?php  
/**
 *
 * Near Controller.
 *
 * This class manage the near cameras
 * 
 * @author  Dawin Ossa <dawinos@gmail.com>
 * @package Controller
 * @version 0.1
 */

class NearCamera_Controller
{
	//<----- private properties --------> 
    private $_near_model = null;
	
	/**
	 * Method construct
	 */
	public function __construct()
	{
		$this->app = \Slim\Slim::getInstance();
		$this->_near_model = new NearCamera_Model;
	}

	/**
	 * 
	 * Obtain the camera most nearly
	 * 
	 * @return Array Collection of Cameras
	 */
	public function get()
	{
		// Set the response headers...
		
		$response = array();		
		$params = $this->app->request()->params();
		
		$lat  = $params['lat'];
		$long  = $params['long'];
		$hourTime  = $params['hourTime'];

		try{

			$valToModel = array("lat" => $lat, "lon" => $long, "hourTime" => $hourTime);
			$resultModel = $this->_near_model->getNearCamera($valToModel);
			if (count($resultModel) > 0) {
				
				$response['data'] = $resultModel;
				$response['status'] = true;

			} else {
				throw new Exception("Error Processing Request", 400);
			}

		} catch (Exception $e) {
			$response['data'] = null;
			$response['status'] = false;
			$response['message'] = $e->getMessage();
		}
		header('Content-type: application/json;charset=utf-8');
		echo json_encode($response);
	}
}
