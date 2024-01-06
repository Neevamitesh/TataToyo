<?php
    class SpeLabelTran extends CI_Model{
        public function __construct(){
            $this->load->database();
        }
        public function GetLabel($Only=null){
            $this->db->join("ConsigneeMaster","ConsigneeMaster.CMID = AdditionalLabel.CMID AND ConsigneeMaster.isactive = 1");
            return $this->db->get_where("AdditionalLabel",[
                "AdditionalLabel.BitStatus" => 1
            ]);
        }
        public function Consignee(){
            $CMIDs = array_column(($this->GetLabel()->result_array()),'CMID');
            empty($CMIDs)?:$this->db->where_not_in('CMID',$CMIDs);
            return $this->db->get_where("ConsigneeMaster",[
                "isactive" => 1
            ]);
        }
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