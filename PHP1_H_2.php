<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Document</title>
	<style>
  	form {
    		width: 340px;
    	}
    	table {
    		width: 340px
    	}
    	input[type="text"],[type="email"] {
          width: 189px;
    	}
        input[type="submit"] {
           	  float: right;
           }
	</style>
</head>
<body>
	<form action="" method="post" name="form">
		<fieldset>
			<legend>Добавяне на нов въпрос</legend>
			<table border="1px solid">
				<tr>
					<td>Вашето e-mail</td>
					<td>
						<input type="text" name="email" value="" placeholder="email" />
						<input type="hidden" name="action" value="send" /> 
					</td>
				</tr>
				<tr>
					<td>Заглавие на въпроса</td>
					<td><input type="text" name="title" placeholder="Ще се справите ли?" /></td>
				</tr>
				<tr>
					<td>Относно Курсове:</td>
					<td>
					<input type="checkbox" name="curs[]" id="fe" /><label for="fe">FE1</label>
					<input type="checkbox"  name="curs[]" id="fe2" /><label for="fe2">FE2</label>
					<input type="checkbox"  name="curs[]" id="php" /><label for="php">PHP1</label>
				</td>
				</tr>
				<tr>
					<td>Искам анонимност?</td>
					<td>
						<input type="radio" name="anonymous" value="1" id="yes" /><label for="yes">Да</label>
						<input type="radio" name="anonymous" value="0" id="no" /><label for="no">Не</label>
					</td>
				</tr>
				<tr>
					<td colspan="2">Въпрос</td>
				</tr>
				<tr>
					<td colspan="2">
						<textarea name="question" id="" cols="45" rows="5" placeholder="Моля, не се плашете! Лесно е! :)" required="required">
              <?php echo htmlentities(trim(isset($_POST['question'])));?>  
            </textarea>
					</td>
					
				</tr>
				<tr>
					<td><input type="reset" name="reset" value="Изчисти" /></td>
					<td><input type="submit" name="submit" value="Изпрати" /></td>
				</tr>
			</table>
		</fieldset>
	</form>
	<?php 
    // 1) проверка дали формата е изпратена и дали полетат не са празни
    if(isset($_POST['action']) && $_POST['action'] == 'send' && !empty($_POST)) 
    {
      
      if(!isset($_POST['anonymous'])) 
          {
            $_POST['anonymous'] = -1;
               
          }
         	echo "<pre>";
          print_r($_POST);
          echo "</pre>";
         	echo 'zaqvkata e izpratena'.'<br />';

        // $result = serialize($_POST);
        //  $exists = 'Exists';
        if(file_exists('result.txt') ) 
        { 
          if(isset($_POST['submit']) ) 
          {
              echo "file exists";

              foreach ($_POST as  $value) 
              {
                 file_put_contents('result.txt', $value.PHP_EOL, FILE_APPEND);
              }
           }
     
        } 
        else 
        {
          $errors = "no file exists ".date('Y-m-d H:i:s');
          echo Errors($errors);
          // file_put_contents('result.txt', $exists . PHP_EOL, FILE_APPEND);
          foreach ($_POST as  $value) 
          {
              file_put_contents('result.txt', $value.PHP_EOL, FILE_APPEND);
          }
         
        }
    } 
     else
     { 
     	  echo "no";
     }

      function Errors($errors) 
      {
          if(file_exists('errors.txt')) {
             echo "file exists";
             file_put_contents('errors.txt', $errors.PHP_EOL, FILE_APPEND);
          } else {
            echo "not file exists";
            file_put_contents('errors.txt', $errors.PHP_EOL, FILE_APPEND);
          }
      }

     function checkEmail($mail) 
     {
       	$allow = array(
          'mitak.1985', 
          'abv.bg'
          );
       	$domain = explode("@", $mail);

       	if(isset($domain[1]) && in_array($domain[1], $allow)) 
        {
       		return true;
       	} 
        else 
        {
       		return false;
       	}
    }

    // 2) проверка дали се съдържа знака @

    if(isset($_POST['submit']) )
    {

      $email = addslashes(strip_tags($_POST['email']));

      if($email != NULL && strlen($email) > 5 )
      {
                
          if(checkEmail($email) )
          {
              echo "Succsess";
          }
          else
          {
              $errors = "Your email must be @ ".date('Y-m-d H:i:s');  
              echo Errors($errors);
          }
      }
      else 
      {
          $errors = "Enter email. ".date('Y-m-d H:i:s'); 
          echo Errors($errors);
      }

    }

    $curs = array();

    if(is_array($curs) ) 
    {
    	echo "<pre>";
    	print_r($_POST, true);
    	echo "</pre>";
      echo "Field, is array".'<br />';       
    } 
    else 
    {
      $errors = "Error, is not array ".date('Y-m-d H:i:s'); 
    	echo  Errors($errors);
    }

   
	?>
</body>
</html>