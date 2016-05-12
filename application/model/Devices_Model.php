<?php

/**
* Devices Model.
*
* this class managed the access to the table devices and groups
*
* @package   Model
* @author    Dawin Ossa <dawin@neatproj.com>
* @version   0.1
*/

class Devices_Model
{
	/**
	* with this method we obtain the most near cameras.
	*
	* @param  float $lat lat of camera
	* @param  float $lon long of camera
	* @return Array info camera captured
	*/
	public function getDevices($data)
	{
				$userId = $data['userId'];
				$groups = $data['groups'];
				$objectTypes = $data['objectTypes'];

        $resultOperationObjectId = array();

        try {

						$stm = "SELECT cc37_object_id AS object_id, cc22_cc38_object_type_id as object_type_id FROM tc37_grants_users ' .
						'WHERE cc37_cc38_object_type_id IN ( ' . $objectTypes . ' ) '.
						'AND cc37_cs18_user_id =  ' . $userId . ' ' .
						'UNION ' .
						'SELECT cc22_object_id AS object_id, cc22_cc38_object_type_id as object_type_id FROM tc22_grants_groups ' .
						'WHERE cc22_cc38_object_type_id = ' . $objectTypeId . ' ' .
						'AND cc22_cs08_group_id IN(' . $groups . ') ' .
						'GROUP BY object_id ' .
						'ORDER BY object_id ASC;";
            $resultOperationObjectId = R::getAll($stm);

        } catch (Exception $e) {

        }
        return $resultOperationObjectId;
    }

		public function getDevicesVehiclesProperties($objectid)
		{
					$resultOperationObjectId = array();

					try {
						$stm = 'SELECT cs56_tracker_id AS tracker_id, cc109_cc67_type_vehicle_id AS type_vehicle_id, cs56_imei AS tracker_imei, cs56_phone_number AS tracker_phone_number, cs57_vehicle_id AS tracker_vehicle_id, ' .
						'cs57_licence_plate AS tracker_licence_plate, cs80_code AS tracker_model, cs78_code AS type_tracker_code ' .
						'FROM tc109_vehicles, ts56_trackers, ts80_models_trackers, ts78_types_trackers ' .
						'WHERE AND cs109_vehicle_id IN(' . $objectid . ')'.
						'AND cc109_cc02_state_id = 1 ' .
						'AND cc109_cs56_tracker_id <> 0 '.
						'AND cc109_cs56_tracker_id = cs56_tracker_id ' .
						'AND cs56_cs80_model_tracker_id = cs80_model_tracker_id ' .
						'AND cs80_cs78_type_tracker_id = cs78_type_tracker_id ' .
						'ORDER BY cs57_vehicle_id ASC;';
							$resultOperationObjectId = R::getRow($stm);

					} catch (Exception $e) {

					}
					return $resultOperationObjectId;
			}
}
