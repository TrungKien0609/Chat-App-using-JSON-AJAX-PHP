<?php
   session_start();
   include_once "config.php";
   $fname = mysqli_real_escape_string($conn,$_POST["fname"]); // $_POST["fname"] to get the value from input field (index.php)
   $lname = mysqli_real_escape_string($conn,$_POST["lname"]);
   $email = mysqli_real_escape_string($conn,$_POST["email"]);
   $password = mysqli_real_escape_string($conn,$_POST["password"]);

   if(!empty($fname) && !empty($lname) && !empty($email) && !empty($password))
   {
      //let's check  user email validated or not
      if(filter_var($email,FILTER_VALIDATE_EMAIL)){// IF email is valid
        // let's check that email already exist in the Database or not ?
        $sql = mysqli_query($conn,"SELECT email FROM users WHERE  email='{$email}'");
        if(mysqli_num_rows($sql) > 0){ // if email already exist
            echo " $email - This email already exist";
        }
        else{
           // let's check user upload file or not
           if(isset($_FILES['image'])){ // if file is upploaded
              $img_name = $_FILES['image']['name'] ; // getting user uploaded image name
              $tmp_name =  $_FILES['image']['tmp_name'];  // chứa temporary name  của ảnh người dùng trong thư mục $tmp_name   
              // let's explode image and get the last extension like jpg png
              $img_explode = explode('.',$img_name);
              $img_ext = end($img_explode); // hear we get the extension of an user uploaded img file
              $extensions = ['png','jepg','jpg']; // these are some valid img ext and  we've stored them in array
              if(in_array($img_ext,$extensions)=== true) {// if  user uploaded img ext is matched with any array extension
                 $time = time(); // this will return us current time
                                 // we need this time because when you uploading user img to in our folder, we rename user file with current time 
                                  // so all the img file will have a unique name
                  // let's move the user uploaded img to our particular folder
                  $new_img_name = $time.$img_name; // dấu . là ý ghép chuổi , lay ten name bang thoi diem upload file ( co ich khi ng dung upload 2 file anh khac nhau nhung trung tên)
                  if(move_uploaded_file($tmp_name,"images/".$new_img_name)){ // nếu nư ảnh người dùng được upload thành công thì sẽ chuyển file ( tên tạm được tạo ra để move /save file trong thư mục ở máy chủ)
                     // nếu hợp lệ sẽ chuyển file ( tạm đó) trong thư mục ($tmp_name) đến thư mục chứa file images/...... , khúc này sẽ xóa đi file image tạm trong folder
                     $status = 'Active now' ; // once user signed up then his status will be active now
                     $random_id =  rand(time(),10000000) ;// creating ramdom id for user
                     // let's insert all user data into table 
                     $sql2  = mysqli_query($conn,"INSERT INTO users (unique_id,fname,lname,email,password,img,status) 
                                          VALUES ('$random_id','$fname','$lname','$email','$password','$new_img_name', '$status' )");
                     if($sql2){ // if these data inserted
                           $sql3 = mysqli_query($conn,"SELECT * FROM users WHERE email = '{$email}'");
                           if(mysqli_num_rows($sql3)>0)
                           {
                              $row = mysqli_fetch_assoc($sql3);
                              $_SESSION['unique_id'] = $row['unique_id']; // using this session we used user unique_id in other php file
                              echo 'success';
                           }
                     }
                     else{
                        echo 'something went wrong';
                     }
                    
                  }
                  // now creating a folder to save user uploaded images
                  $status = "Active now"  ;
              }
              else
              {
                 echo " Please select an Image file -  .jpg, .jepg, .png" ;
              }
            }
        }
      } 
      else{
            echo " $email - This is a not valid email";
      }
   }
   else
   {
      echo "All input field are required";
   }
   // Remember we don't upload user uploaded file in the 
   // database we just save file url thier. Actual
   // file will be saved in our particular folder ( for line 28 -> line 33)
?>


