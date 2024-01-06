<?php
    class Test extends CI_Controller{
       function index($i=15){
        // $this->load->model("GetProducts");
        //     $arr= $this->GetProducts->GetAllProducts();
        //     $res = $this->GetProducts->GetProductDetail(2);
            
        //     echo "<pre>";
        //     print_r ($res);
        //     print_r ($arr);
        //     echo "</pre>";
        $Test = "~SP21Sam~SP21Sam~SP21Sam~SP21Sam";
        $Level = 0;
        $index = 0;
        $Level = empty($Level)? (substr($Test,(strpos($Test,'~SD',$index) + 3),2)) : $Level;

        Bri:
        $Start = strpos($Test,'~SP',$index);
        $Br = "~SP{$Level}";
        $Test = substr_replace($Test,$Br,$Start,5);
        $index++;
        if((strpos($Test,'~SP',$index)) != false){
            goto Bri;
        }
        echo $Test;
       }
       function upload(){
            $data = $_FILES['file'];
            $config = array(
                'upload_path' => 'upload/test',
                'allowed_types' => '*'
            );  
            $myfiles = array();
            $this->load->library('upload',$config);
            for($i=0;$i<count($data);$i++){                
                $_FILES['myfile']['name'] = $data['name'][$i];
                $_FILES['myfile']['tmp_name'] = $data['tmp_name'][$i];
                $_FILES['myfile']['size'] = $data['size'][$i];
                $_FILES['myfile']['error'] = $data['error'][$i];
                $_FILES['myfile']['type'] = $data['type'][$i];
                $res = $this->upload->do_upload('myfile');
                print_r($this->upload->display_errors());
                $name = $this->upload->data();
                array_push($myfiles,$name);
            }
            print_r($myfiles[0]);
       }
    }
?>