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

class Devices_Controller
{
  //<----- private properties -------->
  private $_devices_model = null;

  /**
  * Method construct
  */
  public function __construct()
  {
    $this->app = \Slim\Slim::getInstance();
    $this->_devices_model = new Devices_Model;
  }

  private function castTypeVehicle ($typeVehicleId) {
    $types = array('1'=> 'AUTOMOVIL', '2' => 'BUS', '9'=> 'MOTOCICLETA', '5'=>'CAMIONETA');
    return $types[$typeVehicleId];
  }

  /**
  *
  * Obtain the camera most nearly
  *
  * @return Array Collection of Devices
  */
  public function get()
  {
    // Set the response headers...
    $response['data'] = $response = array();
    $params = $this->app->request()->params();

    $userId  = $params['userId'];
    $groups  = $params['groups'];
    $objectTypes  = $params['objectTypes'];

    try {

      $valToModel = array("userId" => $userId, "groups" => $groups, "objectTypes" => $objectTypes);
      $resultModel = $this->_devices_model->getDevices($valToModel);

      if ( count($resultModel) > 0 ) {
        foreach ($resultModel as $key => $row) {
          switch ($row['object_type_id']) {
            case '13': // Conductor

            break;

            case '24': // Vehiculo
              $vehiculos = $this->_devices_model->getDevicesVehiclesProperties((int)$row['object_id']);
              if (isset($vehiculos['tracker_imei'])) {
                $data = array(
                  'deviceId'=> $row['object_id'],
                  'name'=> $vehiculos['tracker_license_plate'],
                  'type_vehicle'=>self::castTypeVehicle($vehiculos['type_vehicle_id']),
                  'phone' => $vehiculos['tracker_phone_number'],
                  'imei' => $vehiculos['tracker_imei']);
                $response['data'][] =  $data;
              }
            break;

            case '25': // Tracker

            break;
            default:
            break;
          }
        }

        //$response['data'] = $resultModel['objects'];
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
