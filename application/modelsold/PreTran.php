<?php
    class PreTran extends CI_Model{
        public function __construct(){
            parent::__construct();
            $this->load->database();
        }
        public function Available(){
            $this->db->join('ConsigneeMaster', 'ConsigneeMaster.CMID = Preview.CMID AND ConsigneeMaster.isactive = 1');
            return $this->db->get_where('Preview',[
                "Preview.BitStatus" => 1
            ]);
        }
        public function GetNewConsignee(){
            $CMID = array_column($this->Available()->result_array(),"CMID");
            (count($CMID)<=0)?:$this->db->where_not_in("ConsigneeMaster.CMID",$CMID); 
            return $this->db->get_where('ConsigneeMaster',[
                "ConsigneeMaster.isactive" => 1
            ]);
        }
        public function UploadFile($Data){
            $this->db->update('Preview',[
                "BitStatus" => 0
            ],[
                "CMID" => $Data['CMID']
            ]);
            $this->db->insert('Preview', $Data);
            return $this->db->affected_rows();
        }
        public function GetFile($CMID){
            return $this->db->get_where('Preview',[
                "BitStatus" => 1,
                "CMID" => $CMID
            ]);
        }
    }
    
?>