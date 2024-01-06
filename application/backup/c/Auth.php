<?php
    class Auth extends CI_Controller{
        public $admin_id;
        public function __construct(){
            parent::__construct();
            $this->load->library('session');
            $this->admin_id = $this->session->userdata('admin_id');
            // if(!empty($user_id)){
            //     redirect('Home');
            // }
            // $this->load->model('UserValidation');
            $this->load->model("GetUserData");
        }
        public function Login(){
            (!empty($this->input->post('email')) || !empty($this->input->post('password')))?:redirect("Home/Login"); 
            $email = $this->input->post('email',true);
            $password = $this->input->post('password',true);
            if(empty($email) || empty($password)) {
                $this->session->set_flashdata("Login-PB",$this->msg);
            }
            $res = $this->UserValidation->validateUser($email,$password);
            if($res == "nouser"){
                $this->msg = "User Does Not Exist";
                $this->session->set_flashdata("Login-PB",$this->msg);
                redirect("Home/Login");
            }else if($res == "error"){
                $this->msg = "Password Does Not  Match";
                $this->session->set_flashdata("Login-PB",$this->msg);            
                redirect("Home/Login");
            }else{
                redirect('Home');
            }
        }

        public function OTPLogin(){
            $sessionotp = $this->session->userdata('sessionotp');
            $contact = $this->input->post('contact');
            $userotp = $this->input->post('otp');
            if($sessionotp == $userotp){
                $this->load->model("GetUserData");
                $rawData = $this->GetUserData->isContactExist($contact);
                $userData = $rawData->row();
                $res = $this->UserValidation->validateUser($userData->Email,$userData->Password);
                if($res){
                    redirect('Home');
                }
            }
            else{
                $error = "OTP Does Not  Match";
                $this->load->view("login",['otperror'=>$error]);
            }
        }

        public function UserValidation($e = null, $p = null){
            $email = "";
            $password = "";
            if($e == null && $p == null){
                $email = $this->input->post('email', true);
                $password = $this->input->post('password', true);
            }
            else{
                $email = $e;
                $password = $p;
            }
            $user = $this->GetUserData->GetUserState($email);
            if($user->Session_State == 1){
                $data = array(
                    'email' => $email,
                    'password' => $password
                );
                $this->load->view("User/forbidden", $data);
            }
            else{
                $this->load->model("userValidation");
                $result=$this->userValidation->AdminValidation($email,$password);
                if($result > 0){
                    // if($result=="SuperAdmin"){
                    //     redirect("Home");
                    // }
                    // else{
                    //     redirect("Admin");
                    // }
                    $ULID = $this->GetUserData->addLog($result);
                    $this->session->set_userdata('ulid',$ULID);
                    $msg = "Welcome to Dashboard !";
                    $this->session->set_flashdata("msg", $msg);
                    redirect("Home");
                }
                else{
                    $msg = "Password Incorrect !";
                    $this->session->set_flashdata("msg", $msg);
                    redirect("Admin");
                }
            }
        }
        public function LogOut(){
            $user_id=$this->session->userdata('admin_id');
            $ULID = $this->session->userdata('ulid');
            $res = $this->GetUserData->changeUserState($user_id, 0, $ULID);
            if($res){
                $this->session->sess_destroy();
                redirect('Admin/index');
            }
            else{
                $msg = "Something went wrong !";
                $this->session->set_flashdata("msg", $msg);
                redirect("Home");
            }
        }
        public function LogOutAll(){
            $email = $this->input->post('email', true);
            $password = $this->input->post('password', true);
            $UID = $this->GetUserData->GetUIDByEmail($email);
            $this->load->model("userValidation");
            $res = $this->userValidation->LogOutAll($UID);
            if($res){
                $this->UserValidation($email, $password);
            }
            else{
                redirect('Admin');
            }
        }

        public function CheckState(){
            $state = $this->input->post('state', true);
            $ULID = $this->session->userdata('ulid');
            $res = $this->GetUserData->CheckState($state, $ULID);
            
            // echo "<pre>";
            // print_r ($res);
            // echo "</pre>";
            
            if(empty($res)){
                echo "empty";
            }
            else{
                echo "Logged In !";
            }
        }

    }

?>