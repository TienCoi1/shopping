<?php
namespace app\controller;
use app\controller\Controller;
class RegisterController extends Controller{
    private $allowtypefile = ['image/jpeg','image/jpg','image/png'];
    private $allowsizefile = 1024*5;
    public function index() {
        $this->loadView('register/index_view',['title'=>'register page']);

    }
    private function checkTypeFileAvatar($file)
    {
        //png.jpg.jpeg
        return in_array($file,$this->allowtypefile);
    }
    private function  checkSizeFileAvatar($size)
    {
        //5mb
        return $size < $this->allowsizefile;
    }
    public function handle(){
        if (isset($_POST['btnRegister'])){
           $firstName= $_POST['first_name']?? null;
           $firstName= strip_tags($firstName);
           $lastName =  trim($_POST['lastName'] ?? null);
           $lastName = strip_tags($lastName);
           $username= trim($_POST['username'] ?? null);
           $username= strip_tags($username);
           $password = trim($_POST['password'] ?? null);
           $password = strip_tags($password);
           $email= trim($_POST['email'] ?? null);
           $email= strip_tags($email);
           $phone= trim($_POST['phone'] ?? null);
           $phone= strip_tags($phone);
           $gender= trim($_POST['gender'] ?? null);
           $gender= strip_tags($gender);
           $address= trim($_POST['address'] ?? null);
           $address= strip_tags($address);
           $birthday= trim($_POST['birthday'] ?? null);
           $birthday= strip_tags($birthday);
           $birthday = date("y-m-d",strtotime($birthday));//mysql:y-m-d
            //strtotime:đổi ngày tháng ra số giây tính từ  0h 1/1/1970
            //nếu có upload file
            $fileName = null;
            if (empty($_FILES['avatar'])){
                //người dùng có upload files
                //chỉ cần lấy tên file của người dùng up load lên
                $name = $_FILES['avatar']['name'];
                $name= time().'-'.$name;
                $type= $_FILES['avatar']['type'];
                $size =$_FILES['avatar']['size'];
                $tmpName = $_FILES['avatar']['tmp_name'];
                if($_FILES['avatar']['error'] ==0){
                    if ($this->checkSizeFileAvatar($size) && $this->checkTypeFileAvatar($type)){
                        if(move_uploaded_file($tmpName,APP_PATH_IMG_UPLOAD.$name)){
                            $fileName=$name;
                            if (!empty($_SESSION['err_register']['avatar'])){
                                unset($_SESSION['err_register']['avatar']);
                            }
                        }
                    }else{
                        $_SESSION['err_register']['avatar'] = 'avatar is type.png.jpg.jpeg';
                    }
                }
            }
            //validate information
            if (empty($firstName)){
                $_SESSION['err_register']['first_name'] = "first name is not empty";
            }else{
                if (empty( $_SESSION['err_register']['first_name'])){
                    unset( $_SESSION['err_register']['first_name']);
                }
            }
            if (empty($lasttName)) {
                $_SESSION['err_register']['last_name'] = "last name is not empty";
            }else{
                if (empty( $_SESSION['err_register']['last_name'])){
                    unset( $_SESSION['err_register']['last_name']);
                }
            }
            if (empty($username)) {
                $_SESSION['err_register']['username'] = "username is not empty";
            }else{
                if (empty($_SESSION['err_register']['username'])){
                    unset($_SESSION['err_register']['username']);
                }
            }
            if (empty($password)) {
                $_SESSION['err_register']['password'] = "password is not empty";
            }else{
                if (empty(  $_SESSION['err_register']['password'])){
                    unset(  $_SESSION['err_register']['password']);
                }
            }
           if (filter_var($email,FILTER_VALIDATE_EMAIL)){
               $_SESSION['err_register']['email']='email is invalid';
           }else{
               if (empty( $_SESSION['err_register']['email'])){
                   unset( $_SESSION['err_register']['email']);
               }
           }
           if (empty($phone)){
               $_SESSION['err_register']['phone'] = 'phone is not empty';
           }else{
               if (empty( $_SESSION['err_register']['phone'])){
                   unset( $_SESSION['err_register']['phone']);
               }
           }
           //validate done
            if(!isset($_SESSION['err_register'])||empty($_SESSION['err_register'])){
                //ngườu dùng đã nhập đầy đủ các thông tin bắt buộc
            }else{
                redirect_action("register","index",["state"=>"failure"]);
            }
        }
    }

}