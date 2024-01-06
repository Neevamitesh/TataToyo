<?php
    
    class GetLocation extends CI_Model{
        private $data = array(
            'itype' => '',
            'iid' => 0,
            'ilocationcode' => '',
            'ilocationname' => '',
            'iother1' => '',
            'iother2' => '',
            'iother3' => '',
            'icreatedby' => '',
            'iupdatedflag' => '',
            'irefdate' => '',
            'ifinyear' => '',
            'icreatedthrough' => '',
            'iupdatedthrough' => '',
            'iupdatedtimestamp' => '',
        );    
        function __construct(){
            parent::__construct();
            $this->load->database();
        }

        public function AddLocation($values){
            $this->data['itype'] = "INS";
            $this->data['ilocationcode'] = $values[0];
            $this->data['ilocationname'] = $values[1];
            $stored_proc = "CALL locationmaster(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $Result = $this->db->query($stored_proc, $this->data);
            $Result->next_result();
            return $Result->row();
        }

        public function AddPrinter($PrinterMaster){
            return $this->db->insert_batch("Printers", $PrinterMaster);
        }

        public function GetAllLocation(){
            $this->data['itype'] = "GET";
            $this->data['iid'] = 0;
            //$this->data['isactive'] = 1;
            $stored_proc = "CALL locationmaster(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $res = $this->db->query($stored_proc, $this->data);
            $res->next_result();
            return $res;
        }

        public function GetGetLocationByID($LOCID){
            $this->data['itype'] = "GET";
            $this->data['iid'] = $LOCID;
            $stored_proc = "CALL locationmaster(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            return $this->db->query($stored_proc, $this->data)->row();
        }

        public function UpdateLocation($values){
            $this->data['itype'] = "UPD";
            $this->data['iid'] = $values[2];
            $this->data['ilocationcode'] = $values[0];
            $this->data['ilocationname'] = $values[1];
            $stored_proc = "CALL locationmaster(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            return $this->db->query($stored_proc, $this->data)->row();
        }

        public function DeleteLocation($LOCID){
            $this->data['itype'] = "DeleteLocation";
            $this->data['iid'] = $LOCID;
            $stored_proc = "CALL locationmaster(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            return $this->db->query($stored_proc, $this->data)->row();
        }

    }
?>