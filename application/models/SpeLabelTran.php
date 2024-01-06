<?php
    class SpeLabelTran extends CI_Model{
        public function __construct(){
            $this->load->database();
        }
        private function Api($Name,$Param=[]){ 
            $Result = null;
            try{
                $Param = $this->FilterArray($Param);
                $Q = $this->QM($Param);
                $Result = $this->db->query("CALL {$Name}({$Q})",$Param);
                $Result->next_result();
            }catch(Exception $e){
                echo $e->getMessage();
            }
            return $Result;
        }
        private function callProc($Name,$Param=[]){ 
            $Result = null;
            try{
                #$Param = $this->FilterArray($Param);
                $Q = $this->QM($Param);
                $Result = $this->db->query("CALL {$Name}({$Q})",$Param);
                $Result->next_result();
            }catch(Exception $e){
                echo $e->getMessage();
            }
            return $Result;
        }
        private function QM($Array){
            $Q = [];
            for($i=0;$i<count($Array);$i++){
                $Q[] = "?";
            }
            return implode(",",$Q);
        }
        private function FilterArray($Array=[]){
            foreach ($Array as $key => $value) {
                if(empty($value)){
                    $Array[$key] = "null";
                }
            }
            return $Array;
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
        public function AddLabel_old($CMID,$Data){
            $this->db->update('AdditionalLabel',[
                'BitStatus' => 0
            ],[
                "CMID" => $CMID
            ]); 
            $this->db->insert('AdditionalLabel',$Data);
            return $this->db->affected_rows();
        }

        public function AddLabel($Data){
            return $this->Api("AdditionalLabelUpload",$Data);
        }
    }
?>