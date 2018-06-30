<?php
class Track_model extends CI_Model{

	public function getTrackInfo($trackno){

		$sql = "SELECT t.*, DATE_FORMAT(t.eta,'%d/%m/%Y %l:%i %p') AS arrival_time, p.recipient_name, p.no_of_package, p.weight
                FROM tracking AS t
                LEFT JOIN package AS p ON (p.package_id = t.package_id)
                WHERE t.tracking_number = '".$trackno."'";
               
        $query = $this->db->query($sql);
	    if($query->num_rows() > 0){
	    	$result = $query->result_array();
	    	return $result;
	    }
	    return false;
	}

	public function add_Package($data){
		$this->db->insert('package', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function add_Tracking($data){
		$this->db->insert('tracking', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function add_TrackingStatus($data){
    	$this->db->insert('tracking_status', $data);
    }


	public function getTrackStatus($trackno){

		$sql = "SELECT ts.*, DATE_FORMAT(ts.created_datetime,'%b %d/%m/%y') AS update_date, DATE_FORMAT(ts.created_datetime,'%h:%i %p') AS update_time, s.status_name, s.level 
			FROM tracking_status AS ts
			LEFT JOIN status AS s ON (s.status_id = ts.status_id) 
			WHERE ts.tracking_id = '". $trackno . "'";

		$query = $this->db->query($sql);
	    if($query->num_rows() > 0){
	    	$result = $query->result_array();
	    	return $result;
	    }
	    return false;
	}

	public function get_TrackDetail($trackID){
		$sql = "SELECT t.*, DATE_FORMAT(t.eta,'%d/%m/%Y %l:%i %p') AS eta, p.recipient_name, p.no_of_package, p.weight, s.status_name, ts.remark
                FROM tracking AS t
                LEFT JOIN package AS p ON (p.package_id = t.package_id)
                LEFT JOIN status AS s ON (s.status_id = t.latest_status_id) 
                LEFT JOIN tracking_status AS ts ON (ts.tracking_id = t.tracking_id AND ts.status_id = t.latest_status_id)
                WHERE t.tracking_id = '".$trackID."'";
               
        $query = $this->db->query($sql);
	    if($query->num_rows() > 0){
	    	$result = $query->row();
	    	return $result;
	    }
	    return false;
	}

	public function getTrackList(){
		$sqladd = "";
		/*if (isset($post["trackingNo"]) && (!empty($post["trackingNo"]))){
            $queryString = $post["trackingNo"];
            
            if($sqladd != "") {
                $sqladd = $sqladd . " AND ((t.tracking_number LIKE '$queryString%')) ";    
            } else {
                $sqladd = $sqladd . " WHERE ((t.tracking_number LIKE '$queryString%')) ";    
            }            
        }


        if (isset($post["recipientName"]) && (!empty($post["recipientName"]))){
            $queryString = $post["recipientName"];
            
            if($sqladd != "") {
                $sqladd = $sqladd . " AND ((p.recipient_name LIKE '$queryString%')) ";    
            } else {
                $sqladd = $sqladd . " WHERE ((p.recipient_name LIKE '$queryString%')) ";    
            }            
        }

        if (isset($post["status"]) && (!empty($post["status"]))){
            $queryString = $post["status"];
            
            if($sqladd != "") {
                $sqladd = $sqladd . " AND ((t.latest_status_id= '$queryString')) ";    
            } else {
                $sqladd = $sqladd . " WHERE ((t.latest_status_id= '$queryString')) ";    
            }            
        }


        if (isset($post["mavb"]) && (!empty($post["mavb"]))){
            $queryString = $post["mavb"];
            
            if($sqladd != "") {
                $sqladd = $sqladd . " AND ((t.mavb LIKE '$queryString%')) ";    
            } else {
                $sqladd = $sqladd . " WHERE ((t.mavb LIKE '$queryString%')) ";    
            }            
        }


        if (isset($post["noOfPackage"]) && (!empty($post["noOfPackage"]))){
            $queryString = $post["noOfPackage"];
            
            if($sqladd != "") {
                $sqladd = $sqladd . " AND ((p.no_of_package LIKE '$queryString%')) ";    
            } else {
                $sqladd = $sqladd . " WHERE ((p.no_of_package LIKE '$queryString%')) ";    
            }            
        }

        if (isset($post["weight"]) && (!empty($post["weight"]))){
            $queryString = $post["weight"];
            
            if($sqladd != "") {
                $sqladd = $sqladd . " AND ((p.weight LIKE '$queryString%')) ";    
            } else {
                $sqladd = $sqladd . " WHERE ((p.weight LIKE '$queryString%')) ";    
            }            
        }

        if (isset($post["origin"]) && (!empty($post["origin"]))){
            $queryString = $post["origin"];
            
            if($sqladd != "") {
                $sqladd = $sqladd . " AND ((t.origin_port LIKE '$queryString%')) ";    
            } else {
                $sqladd = $sqladd . " WHERE ((t.origin_port LIKE '$queryString%')) ";    
            }            
        }

        if (isset($post["destination"]) && (!empty($post["destination"]))){
            $queryString = $post["destination"];
            
            if($sqladd != "") {
                $sqladd = $sqladd . " AND ((t.destination LIKE '$queryString%')) ";    
            } else {
                $sqladd = $sqladd . " WHERE ((t.destination LIKE '$queryString%')) ";    
            }            
        }*/

        $sql = "SELECT p.recipient_name, p.no_of_package, p.weight, t.*, s.status_name
                FROM package AS p
                LEFT JOIN tracking AS t ON (t.package_id = p.package_id)
                LEFT JOIN status AS s ON (s.status_id = t.latest_status_id)
                $sqladd
                ORDER BY p.package_id DESC";
               
        $query = $this->db->query($sql);
	    if($query->num_rows() > 0){
	    	$result = $query->result_array();
	    	return $result;
	    }
	    return false;
	}


	public function update_Package($id, $data){

		$query = $this->db
			->where('package_id', $id)
			->update('package',$data);
		if ($query){
			return TRUE;
		} else {
			return FALSE;
		}

	}

	public function update_Tracking($id, $data){

		$query = $this->db
			->where('tracking_id', $id)
			->update('tracking',$data);
		if ($query){
			return TRUE;
		} else {
			return FALSE;
		}

	}

	public function get_LatestStatus($packageID){

		$sql = "SELECT latest_status_id FROM tracking WHERE (package_id = $packageID)";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0){
			$result = $query->row();    
			return $result->latest_status_id;
		}
		return false;
	}


	public function getTrackingID($packageID){
		$sql = "SELECT tracking_id FROM tracking WHERE (package_id = $packageID)";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0){
			$result = $query->row();    
			return $result->tracking_id;
		}
		return false;
	}

	public function update_TrackingStatus($trackingID, $statusID, $data){
		$query = $this->db
			->where('tracking_id', $trackingID)
			->where('status_id', $statusID)
			->update('tracking_status',$data);
		if ($query){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function checkTrackingNo($trackingNo){
		$query = $this->db
            ->select("*")
            ->where("tracking_number", $trackingNo)
            ->get("tracking");
	    if($query->num_rows() > 0){
		    return TRUE;
	    }
	    return FALSE;
	}

	public function get_Status(){
		$query = $this->db
            ->select("*")
            ->get("status");
	    if($query->num_rows() > 0){
		    $result = $query->result_array();
		    return $result;
	    }
	    return FALSE;
	}

}
?>