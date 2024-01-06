<?php
    class User extends CI_Controller{
        public $admin_id;
        public $role;
        function __construct(){
            parent::__construct();
            $this->load->library('session');
            $this->admin_id = $this->session->userdata('admin_id');
            $this->role = $this->session->userdata('role');
            if(empty($this->admin_id)){
                redirect("Admin");
            }
            $this->load->model('GetLocation');
            $this->load->model('GetUser');
            $this->load->model("GetUserData");
            $this->load->model("GetReports");
        }

        public function NavData(){
            $NavData =  array(
                'error'=>''
            );
            return $NavData;
        }

        private function Support($view,$data=[]){
            $NavData = $this->GetUserData->NavData();
            $data = array_merge_recursive($NavData,$data);
            $this->load->view("NavFoot/navbar",$data);
            $this->load->view($view);
            $this->load->view("NavFoot/footer");
        }

        public function index($msg = null){
            $msg = $this->session->flashdata('msg');
            $location = $this->GetLocation->GetAllLocation();
            $users = $this->GetReports->GetUserReport();
            $view = "User/usermaster";
            $data = array(
                'page' => 'usermaster',
                'type' => '',
                'title' => 'User Master',
                'msg' => $msg,
                'location' => $location,
                'users' => $users,
                'role' => $this->role,
            );
            $this->Support($view, $data);
        }
        public function CreateUser(){
            $username = $this->input->post('username', true);
            $email = $this->input->post('email', true);
            $contact = $this->input->post('contact', true);
            $usertype = $this->input->post('type', true);
            $password = $this->input->post('password', true);
            $location = $this->input->post('location', true);
            $userData = $this->GetUser->IsUserExist($email);
            if(!empty($userData)){
                $msg = array(
                    'msg' => 'User Already Exists !',
                    'type' => 'error'
                );
                $this->session->set_flashdata('msg', $msg);
                redirect('User');
                // echo "<pre>";
                // print_r($userData);
                // echo "</pre>";
                
            }
            else{
                // $data = array($username, $email, $contact, $usertype, $password, $location);
                $data = array(
                    'username' => $username, 
                    'emailid' => $email, 
                    'mobileno' => $contact, 
                    'usertype' => $usertype, 
                    'password' => $password, 
                    'confirmpassword' => $password, 
                    'location' => $location
                );

                $products = $this->input->post('product', true);
                $consignee = $this->input->post('consignee', true);
                $user = $this->input->post('user', true);
                $label = $this->input->post('label', true);
                $batch = $this->input->post('batch', true);
                $printing = $this->input->post('print', true);
                $reprint = $this->input->post('reprint', true);
                $reports = $this->input->post('reports', true);
                $UID = $this->GetUser->CreateUser($data);
                if($UID > 0){
                    $typeArray = array(
                        "1" => $products, 
                        "2" => $consignee, 
                        "3" => $user, 
                        "4" => $label,
                        "5" => $batch, 
                        "6" => $printing, 
                        "7" => $reprint, 
                        "8" => $reports
                    );
                    $count = 1;
                    $RiResult = 1;
                    foreach($typeArray as $type => $value){
                        if(!empty($value)){
                            foreach($value as $val){
                                $rights[] = array(
                                    'UID' => $UID,
                                    'Type' => $type,
                                    'Rights' => $val 
                                );
                                $count++;
                            }
                            $RiResult += $this->GetUser->AddRights($rights);
                            unset($rights);
                        }
                    }
                    if($RiResult == $count){
                        $msg = array(
                            'msg' => 'User Created Successfully !',
                            'type' => 'success'
                        );
                        $this->session->set_flashdata('msg', $msg);
                        redirect('User');
                    }
                    else{
                        $msg = array(
                            'msg' => 'Error While Assigning Rights!',
                            'type' => 'error'
                        );
                        $this->session->set_flashdata('msg', $msg);
                        redirect('User');
                    }
                }
                else{
                    $msg = array(
                        'msg' => 'Something went wrong !',
                        'type' => 'error'
                    );
                    $this->session->set_flashdata('msg', $msg);
                    redirect('User');
                }

            }
            
            // echo "<br><pre>";
            // print_r($_POST);
            // echo "</pre>";
        }
        public function EditUser($UID = null){
            if(!empty($UID)){
                $msg = $this->session->flashdata('msg');
                $location = $this->GetLocation->GetAllLocation();
                $userdata = $this->GetUser->GetUserDetail($UID);
                $rights = $this->GetUser->GetUserRights($UID);
                $view = "User/edituser";
                $rightArray = array();
                foreach($rights->result() as $right){
                    array_push($rightArray, $right->TypeName."".$right->RightName);
                }
                $data = array(
                    'page' => 'usermaster',
                    'type' => '',
                    'title' => 'Edit user',
                    'msg' => $msg,
                    'location' => $location,
                    'user' => $userdata,
                    'rights' => $rightArray
                );
                
                // echo "<pre>";
                // print_r ($rightArray);
                // echo "</pre>";
                $this->Support($view, $data);
            }
            else{

            }
        }

        public function UpdateUser(){
            $UID = $this->input->post('uid', true);
            $username = $this->input->post('username', true);
            $email = $this->input->post('email', true);
            $contact = $this->input->post('contact', true);
            $usertype = $this->input->post('type', true);
            $password = $this->input->post('password', true);
            $location = $this->input->post('location', true);

            $data = array(
                'username' => $username, 
                'emailid' => $email, 
                'mobileno' => $contact, 
                'usertype' => $usertype, 
                'password' => $password, 
                'confirmpassword' => $password, 
                'location' => $location
            );
            
                $products = $this->input->post('product', true);
                $consignee = $this->input->post('consignee', true);
                $user = $this->input->post('user', true);
                $label = $this->input->post('label', true);
                $batch = $this->input->post('batch', true);
                $printing = $this->input->post('print', true);
                $reprint = $this->input->post('reprint', true);
                $reports = $this->input->post('reports', true);
                
                $updateRes = $this->GetUser->UpdateUser($data, $UID);
                $delRes = $this->GetUser->DeleteRights($UID);
                
                // echo "<pre>";
                // print_r ($products);
                // echo "</pre>";
                
                if($delRes > 0){
                    
                    $typeArray = array(
                        "1" => $products, 
                        "2" => $consignee, 
                        "3" => $user, 
                        "4" => $label,
                        "5" => $batch, 
                        "6" => $printing, 
                        "7" => $reprint, 
                        "8" => $reports
                    );
                    $count = 1;
                    $RiResult = 1;
                    foreach($typeArray as $type => $value){
                        if(!empty($value)){
                            foreach($value as $val){
                                $rights[] = array(
                                    'UID' => $UID,
                                    'Type' => $type,
                                    'Rights' => $val 
                                );
                                $count++;
                            }
                            // echo "<pre>";
                            // print_r ($rights);
                            // echo "</pre>";
                            $RiResult += $this->GetUser->AddRights($rights);
                            unset($rights);
                        }
                    }
                    // exit;
                    if($RiResult == $count){
                        $msg = array(
                            'msg' => 'User Updated Successfully !',
                            'type' => 'success'
                        );
                        $this->session->set_flashdata('msg', $msg);
                        redirect('User');
                    }
                    else{
                        $msg = array(
                            'msg' => 'Error While Updating Rights!',
                            'type' => 'error'
                        );
                        $this->session->set_flashdata('msg', $msg);
                        redirect('User');
                    }
                }
                else{
                    $msg = array(
                        'msg' => 'Something went wrong !',
                        'type' => 'error'
                    );
                    $this->session->set_flashdata('msg', $msg);
                    redirect('User');
                }
        }

    }
?>