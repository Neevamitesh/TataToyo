<?php
    class GetUserData extends CI_Model{
        private $user_id;
        private $role;
        public function __construct(){
            parent::__construct();
            $this->user_id = $this->session->userdata('admin_id');
            $this->role = $this->session->userdata('role');
            date_default_timezone_set('Asia/Kolkata');
        }
        public function  NavData(){
            $info = $this->GetName($this->user_id);
            return [
                "user_id" => $this->user_id,
                "Name" => $info->row()->username,
                "role" => $this->role
            ];
        }

        public function GetUserState($email){
            $this->db->select("Session_State");
            $this->db->join("userlogs", "userlogs.UID = usermaster.UID");
            return $this->db->get_where("usermaster", ["username" => $email, "isactive" => 1, "Session_State" => 1])->row();
        }

        public function changeUserState($UID, $state, $ULID){
            $cond = array(
                'UID' => $UID,
                'ULID' => $ULID
            );
            $this->db->set("Session_State", $state);
            $this->db->where($cond);
            return $this->db->update("userlogs");
        }

        public function addLog($UID){
            $this->db->insert("userlogs", ["UID" => $UID]);
            return $this->db->insert_id();
        }

        public function GetUIDByEmail($email){
            $this->db->select("UID");
            return $this->db->get_where("usermaster", ["username" => $email])->row()->UID;
        }

        public function GetName($user_id){
            try{
                $res = $this->db->get_where("usermaster",[
                    "UID" =>$user_id
                ]);
                $res->next_result();
            }catch(Exception $e){
                echo $e->getMessage();
            }
            return $res;
        }
        public function CheckState($state, $ULID){
            return $this->db->get_where("userlogs", ["Session_State" => 1, "ULID" => $ULID])->row();
        }
        public function GetStates(){
            return $this->db->get_where("States",["BitStatus"=>1]);
        }
        public function GetCity($State){
            return $this->db->get_where("cities",["BitStatus"=>1,"state_id"=>$State]);
        }
        public function GetType(){
            $Array = explode(",","CONSIGNEEVAR,null,null,null,null,null,null,null,null");
            return $this->Api("P_GET_CD_MASTER_VAL",$Array);
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
    }
?>