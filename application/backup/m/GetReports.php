<?php
    class GetReports extends CI_Model{
        function __construct(){
            parent::__construct();
            $this->load->database();
            
        }
        public function GetUserReport(){
            $this->db->join("locationmaster", "locationmaster.LOCID = usermaster.location");
            return $this->db->get_where("usermaster", ["usermaster.isactive" => 1]);
        }
        public function GetConsigneeReport($fromdate, $todate, $location, $admin_id){
            $this->db->join("states", "states.state_id = consigneemaster.state");
            $this->db->join("cities", "cities.city_id = consigneemaster.city");
            if($location != "empty"){
                $this->db->where("createdby", $location);
            }
            else{
                $this->db->where("createdby", $admin_id);
            }
            if($fromdate != "empty" && $todate != "empty"){
                $arr = array(
                    'createddt >=' => strtotime($fromdate),
                    'createddt <=' => strtotime($todate.' +1 day')
                );
                $this->db->where($arr);
            }
            return $this->db->get_where("consigneemaster", ["isactive" => 1]);
        }
        public function GetAllLocation(){
            $this->db->join("usermaster", "usermaster.location = locationmaster.LOCID");
            return $this->db->get_where("locationmaster", ["locationmaster.isactive" => 1]);
        }
        public function ConsigneeCount(){
            return $this->db->get_where("consigneemaster", ["isactive" => 1])->num_rows();
        }
        public function ProductCount(){
            return $this->db->get_where("productmaster", ["isactive" => 1])->num_rows();
        }

        public function GetUserLogs($location){
            $this->db->join("usermaster", "usermaster.UID = userlogs.UID");
            return $this->db->get_where("userlogs", ["userlogs.UID" => $location]);
        }

        public function GetAllLocations(){
            $this->db->join("usermaster", "usermaster.location = locationmaster.LOCID");
            return $this->db->get_where("locationmaster", ["locationmaster.isactive" => 1]);
        }

    }
?>