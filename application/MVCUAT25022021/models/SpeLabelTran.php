<?php
    class SpeLabelTran extends CI_Model{
        public function __construct(){
            $this->load->database();
        }
        //@@
        public function GetLabel($Only=null){
            
            $this->db->select("ConsigneeMaster.*,locationmaster.locationname LOC");
            $this->db->join("ConsigneeMaster","ConsigneeMaster.CMID = AdditionalLabel.CMID AND ConsigneeMaster.isactive = 1");
            $this->db->join("locationmaster", "locationmaster.LOCID = ConsigneeMaster.location", "LEFT");
            return $this->db->get_where("AdditionalLabel",[
                "AdditionalLabel.BitStatus" => 1
            ]);
        }
        //@@ end
        //@@
        public function Consignee(){
            $CMIDs = array_column(($this->GetLabel()->result_array()),'CMID');
            empty($CMIDs)?:$this->db->where_not_in('CMID',$CMIDs);
            $this->db->select("ConsigneeMaster.*,locationmaster.locationname LOC");
            $this->db->join("locationmaster", "locationmaster.LOCID = ConsigneeMaster.location", "LEFT");
            return $this->db->get_where("ConsigneeMaster",[
                "ConsigneeMaster.isactive" => 1,"locationmaster.isactive" => 1
            ]);
        }
        //@@ end
        public function AddLabel($CMID,$Data){
            $this->db->update('AdditionalLabel',[
                'BitStatus' => 0
            ],[
                "CMID" => $CMID
            ]); 
            $this->db->insert('AdditionalLabel',$Data);
            return $this->db->affected_rows();
        }
    }
?>