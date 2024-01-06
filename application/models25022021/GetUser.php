<?php
    class GetUser extends CI_Model{
        
        function __construct(){
            parent::__construct();
            $this->load->library('session');
            $this->load->database();
            $this->db->reconnect();
        }

        public function CreateUser($data){
            // $data = array(
            //     'itype' => 'INS',
            //     'iid' => 0,
            //     'iusername' => $data[0],
            //     'iemailid' => $data[1],
            //     'imobileno' => $data[2],
            //     'ipassword' => $data[4],
            //     'iconfirmpassword' => $data[4],
            //     'iusertype' => $data[3],
            //     'ilocation' => $data[5],
            //     'iother1' => '',
            //     'iother2' => '',
            //     'iother3' => '',
            //     'icreatedby' => '',
            //     'iupdatedflag' => '',
            //     'irefdate' => '',
            //     'ifinyear' => '',
            //     'icreatedthrough' => '',
            //     'iupdatedthrough' => '',
            //     'iupdatedtimestamp' => '',
            // );
            // $stored_proc = "CALL spusermaster(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            // return $this->db->query($stored_proc, $data)->row();
            $this->db->insert("usermaster", $data);
            return $this->db->insert_id();
        }

        public function AddRights($rights){
            return $this->db->insert_batch("UserRights", $rights);
        }

        public function IsUserExist($email){
            // $data = array(
            //     'itype' => 'IsUserExist',
            //     'iid' => 0,
            //     'iusername' => '',
            //     'iemailid' => $email,
            //     'imobileno' => '',
            //     'ipassword' => '',
            //     'iconfirmpassword' => '',
            //     'iusertype' => '',
            //     'ilocation' => '',
            //     'iother1' => '',
            //     'iother2' => '',
            //     'iother3' => '',
            //     'icreatedby' => '',
            //     'iupdatedflag' => '',
            //     'irefdate' => '',
            //     'ifinyear' => '',
            //     'icreatedthrough' => '',
            //     'iupdatedthrough' => '',
            //     'iupdatedtimestamp' => '',
            // );
            // $stored_proc = "CALL spusermaster(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            // return $this->db->query($stored_proc, $data);
            return $this->db->get_where("usermaster", ["emailid" => $email])->row();
            // $this->db->close();
            // return $query->row();
        }

        public function GetUserDetail($UID){
            return $this->db->get_where("usermaster", ["UID" => $UID, "isactive" => 1])->row();
        }

        public function GetUserRights($UID){
            $this->db->join("moduletype", "moduletype.MTID = userrights.Type");
            $this->db->join("rights", "rights.RID = userrights.Rights");
            return $this->db->get_where("userrights", ["UID" => $UID, "userrights.BitStatus" => 1]);
        }

        public function DeleteRights($UID){
            return $this->db->update("userrights", ["BitStatus" => 0], ["UID" => $UID]);
        }

        public function UpdateUser($data, $UID){
            return $this->db->update("usermaster", $data, ["UID" => $UID]);
        }

    }
?>