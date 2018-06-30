<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index()
	{
		// $this->load->view('welcome_message');
	}

	public function getDirection() {
		$raw = file_get_contents('php://input');
		$json = json_decode($raw);
		$activeSide = $json->activeSide;
		$direction = $json->direction;
		if($activeSide == 'right'){
            if($direction == 'North'){
                $direction = 'East';
            }
            elseif ($direction == 'East'){
                $direction = 'South';
            }
            elseif ($direction == 'South'){
                $direction = 'West';
            }
            elseif ($direction == 'West'){
                $direction = 'North';
            }
        }
        else if($activeSide == 'left'){
            if($direction == 'North'){
                $direction = 'West';
            }
            elseif ($direction == 'East'){
                $direction = 'North';
            }
            elseif ($direction == 'South'){
                $direction = 'East';
            }
            elseif ($direction == 'West'){
                $direction = 'South';
            }
        }
		
        $newArr = array(
		        	"success" => TRUE, 
		        	"direction" => $direction
	        	);
        
        echo json_encode($newArr);
	}

	public function onMove(){
		$raw = file_get_contents('php://input');
		$json = json_decode($raw);
		$direction = $json->direction;
		$currentLocation = $json->currentLocation;

		$axisToChange = explode(",",$currentLocation);

		if($direction == 'North'){
            $y = 1 + $axisToChange[0];
            $axisToChange = $y.','.$axisToChange[1];
        }
        else if($direction == 'East'){
            $x = 1 + $axisToChange[1];
            $axisToChange = $axisToChange[0].','.$x;
        }
        else if($direction == 'South'){
        	$y = $axisToChange[0] - 1;
            $axisToChange = $y.','.$axisToChange[1];
        }
        else if($direction == 'West'){
        	$x = $axisToChange[1] - 1;
            $axisToChange = $axisToChange[0].','.$x;
        }

        $newArr = array(
		        	"success" => TRUE, 
		        	"newPoint" => $axisToChange, 
		        	"oldPoint" => $currentLocation
	        	);
        
        echo json_encode($newArr);
	}
}
