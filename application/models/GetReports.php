<?php
    class GetReports extends CI_Model{
        function __construct(){
            parent::__construct();
            $this->load->database();
            
        }
        public function GetBatchReport($CMID = null, $fromdate = null, $todate = null){
            $this->db->join("consigneemaster", "consigneemaster.CMID = createbatch.CMID");
            $this->db->join("productmaster", "productmaster.id = createbatch.PID");
            $this->db->order_by("CBID", "desc");
            if(!empty($CMID) && !empty($fromdate) && !empty($todate)){
                $todate = date("Y-m-d H:i:s", strtotime("{$todate} +1 day"));
                $this->db->where([
                    "createbatch.CMID" => $CMID,
                    "createbatch.CreatedAt >=" => $fromdate,
                    "createbatch.CreatedAt <=" => $todate
                ]);
            }
            return $this->db->get_where("createbatch", ["createbatch.BitStatus" => 1]);
        }
        public function GetAllConsignees(){
            return $this->db->get_where('consigneemaster', ["isactive" => 1]);
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
            // $this->db->join("usermaster", "usermaster.location = locationmaster.LOCID");
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
            // $this->db->join("usermaster", "usermaster.location = locationmaster.LOCID");
            return $this->db->get_where("locationmaster", ["locationmaster.isactive" => 1]);
        }

        public function GetVerifyReport($CMID = null, $fromdate = null, $todate = null){
            $this->db->join("consigneemaster", "consigneemaster.CMID = verifybarcode.CMID");
            $this->db->join("productmaster", "productmaster.id = verifybarcode.PID");
            $this->db->order_by("VerifyID", "desc");
            if(!empty($CMID) && !empty($fromdate) && !empty($todate)){
                $todate = date("Y-m-d H:i:s", strtotime("{$todate} +1 day"));
                $this->db->where([
                    "verifybarcode.CMID" => $CMID,
                    "verifybarcode.CreatedAt >=" => $fromdate,
                    "verifybarcode.CreatedAt <=" => $todate
                ]);
            }
            return $this->db->get_where("verifybarcode", ["verifybarcode.BitStatus" => 1]);
        }

        public function GetEOLVerifyReport($CMID = null, $fromdate = null, $todate = null){
            $this->db->join("consigneemaster", "consigneemaster.CMID = verifybarcodeeol.CMID");
            $this->db->join("productmaster", "productmaster.id = verifybarcodeeol.PID");
            $this->db->order_by("VerifyID", "desc");
            if(!empty($CMID) && !empty($fromdate) && !empty($todate)){
                $todate = date("Y-m-d H:i:s", strtotime("{$todate} +1 day"));
                $this->db->where([
                    "verifybarcodeeol.CMID" => $CMID,
                    "verifybarcodeeol.CreatedAt >=" => $fromdate,
                    "verifybarcodeeol.CreatedAt <=" => $todate
                ]);
            }
            return $this->db->get_where("verifybarcodeeol", ["verifybarcodeeol.BitStatus" => 1]);
        }

        public function GetWCPrintReport($CMID = null, $fromdate = null, $todate = null){
            $this->db->join("consigneemaster", "consigneemaster.CMID = createbatchwc.CMID");
            $this->db->join("productmaster", "productmaster.id = createbatchwc.PID");
            // $this->db->join("qtymasterwc", "qtymasterwc.CBID = createbatchwc.CBID");
            $this->db->order_by("createbatchwc.CBID", "desc");
            if(!empty($CMID) && !empty($fromdate) && !empty($todate)){
                $todate = date("Y-m-d H:i:s", strtotime("{$todate} +1 day"));
                $this->db->where([
                    "createbatchwc.CMID" => $CMID,
                    "createbatchwc.CreatedAt >=" => $fromdate,
                    "createbatchwc.CreatedAt <=" => $todate
                ]);
            }
            return $this->db->get_where("createbatchwc", ["createbatchwc.BitStatus" => 1]);
        }

        public function GetWCErrorReport($CMID = null, $fromdate = null, $todate = null){
            $this->db->join("consigneemaster", "consigneemaster.CMID = printerror.CMID");
            //$this->db->join("productmaster", "productmaster.id = printerror.PID");
            $this->db->order_by("printerror.PErrorID", "desc");
            if(!empty($CMID) && !empty($fromdate) && !empty($todate)){
                $todate = date("Y-m-d H:i:s", strtotime("{$todate} +1 day"));
                $this->db->where([
                    "printerror.CMID" => $CMID,
                    "printerror.CreatedAt >=" => $fromdate,
                    "printerror.CreatedAt <=" => $todate
                ]);
            }
            return $this->db->get_where("printerror", ["printerror.BitStatus" => 1]);
        }

    }
?>