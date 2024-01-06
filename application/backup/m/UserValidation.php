<?php
    class userValidation extends CI_Model{
        public function validateUser($email,$password){
            $this->load->database();
            $query=$this->db->get_where("usermaster",array('Email'=>$email,'Role !='=>'Admin'));
            if($query->num_rows()>0){
                if($query){
                    foreach($query->result() as $row){
                        if($row->Password==$password){
                            if($row->Valids==1){
                                $this->load->library('session');
                                $this->session->set_userdata('user_id-PB',$row->UID);
                                $this->session->set_userdata('role-PB',$row->Role);
                                return $row->Valids;   
                            }else{
                                return $row->Valids;
                            }
                        }
                        else{
                            return "error";
                        }
                    }
                }
            }else{
                return "nouser";
            }
        }

        public function AdminValidation($email, $password){
            $this->load->database();
            // $this->db->select("*");
            $query=$this->db->get_where("usermaster",array('username'=>$email, 'password' => $password));
            if($query->num_rows()>0){
                    $row = $query->row();
                    if($row->password==$password){
                        $this->load->library('session');
                        $this->session->sess_expiration = '1000';
                        $this->session->set_userdata('admin_id',$row->UID);
                        $this->session->set_userdata('role',$row->usertype);
                        return $row->UID;
                    }
                    else{
                        return "error";
                    }
            }
            else{
                return "error";
            }
        }

        public function LogOutAll($UID){
            $this->db->set("Session_State", 0);
            $this->db->where("UID", $UID);
            return $this->db->update("userlogs");
        }

    }
?>