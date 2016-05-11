<?php


//mb_internal_encoding('UTF-8'); 

$gender_options = [0 => 'Мъж', 1 => 'Жена', 2 => 'Не казвам'];
$languages_options = ['Английски', 'Френски', 'Български'];

$mysqli = new mysqli('localhost', 'root', '0896482336', 'db_users');

if ($mysqli->connect_error) 
{
    die($mysqli->connect_error);
} 

$error = FALSE;
$error_message = [];

if (isset($_POST['form_send']) && $_POST['form_send'] == 'yes')
{
  if (!isset($_POST['names']) || strlen($_POST['names']) == 0)
  {
    $error = TRUE;
    $error_message[] = 'Въведете име';
  }

  if (!isset($_POST['languages']) || !is_array($_POST['languages']))
  {
    $error = TRUE;
    $error_message[] = 'Изберете говорим език';
  }

  if (!$error)
  {
    $names = $_POST['names'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : 2;
    $birth_date = date('Y-m-d H:i:s', strtotime($_POST['birth_date']));
    $city = $_POST['city'];
    $languages = $_POST['languages'];
    $phone = $_POST['phone'];
    $comment = $_POST['comment'];

    $name_escaped = $mysqli->real_escape_string($names);
    $gender_escaped = $mysqli->real_escape_string($gender);
    $birth_date_escaped = $mysqli->real_escape_string($birth_date);
    $city_escaped = $mysqli->real_escape_string($city);
    $phone_escaped = $mysqli->real_escape_string($phone);
    $comment_escaped = $mysqli->real_escape_string($comment);

    $update = FALSE;
    if (isset($_POST['id']) && (int) $_POST['id'] > 0)
    {
      $update = TRUE;
      $id_escaped = $mysqli->real_escape_string($_POST['id']);
      $sql = "UPDATE users SET
        names = '$name_escaped',
        gender = '$gender_escaped',
        birth_date = '$birth_date_escaped',
        city = '$city_escaped',
        phone = '$phone_escaped',
        comment = '$comment_escaped' WHERE id = '$id_escaped'";
    }
    else
    {
      $sql = "INSERT INTO users SET
        names = '$name_escaped',
        gender = '$gender_escaped',
        birth_date = '$birth_date_escaped',
        city = '$city_escaped',
        phone = '$phone_escaped',
        comment = '$comment_escaped'";
    }

    $mysqli->query($sql);

    if ($update)
    {
      $mysqli->query("DELETE FROM languages WHERE user_id = '$id_escaped'");
      foreach ($languages as $language)
      {
        $language_escaped = $mysqli->real_escape_string($language);
        $sql = "INSERT INTO languages SET
          user_id = '$id_escaped',
          name = '$language_escaped'";
        $mysqli->query($sql);
      }
    }
    else
    {
      $user_id = $mysqli->insert_id;

      foreach ($languages as $language)
      {
        $language_escaped = $mysqli->real_escape_string($language);
        $sql = "INSERT INTO languages SET
          user_id = '$user_id',
          name = '$language_escaped'";
        $mysqli->query($sql);
      }
    }
  }
}

$id = NULL;
$names = '';
$gender = '';
$birth_date = '';
$city = '';
$phone = '';
$comment = '';
$known_languages = [];


if (isset($_GET['id']))
{
  $id_escaped = $mysqli->real_escape_string($_GET['id']);
  $result = $mysqli->query("SELECT * FROM users WHERE id = '$id_escaped'");
  $data = $result->fetch_object();
  $id = $data->id;
  $names = $data->names;
  $gender = $data->gender;
  $birth_date = $data->birth_date;
  $city = $data->city;
  $phone = $data->phone;
  $comment = $data->comment;

  $result = $mysqli->query("SELECT * FROM languages WHERE user_id = '$data->id'");
  while ($data_languages = $result->fetch_object())
  {
    $known_languages[] = $data_languages->name;
  }
}

if (isset($_GET['delete_id']))
{
  $id_escaped = $mysqli->real_escape_string($_GET['delete_id']);
  $mysqli->query("DELETE FROM users WHERE id = '$id_escaped'");
  $mysqli->query("DELETE FROM languages WHERE user_id = '$id_escaped'");
}


?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title></title>
    <style>
    .error {
      color: red;
      display: block;
    }
    
    table {
      border-collapse: collapse;
    }

    table, th, td {
      border: 1px solid #ccc;
    }
    </style>
  </head>
  <body>
    <section>
      <?php
      if ($error)
      {
        foreach ($error_message as $message)
        {
          echo '<strong class="error">' . $message . '</strong>';
        }
      }
      ?>
      <form method="post">
        <div>Три имена*: <input type="text" name="names" value="<?php echo $names; ?>"></div>
        <div>Пол: 
        <?php
        foreach ($gender_options as $k => $v)
        {
          $selected = '';
          if ($k == $gender)
          {
            $selected = 'checked="checked"';
          }

          echo '<input type="radio" name="gender" value="' . $k. '" ' . $selected . '> ' . $v;
        }
        ?>
        <div>Дата на раждане: <input type="date" name="birth_date" value="<?php echo $birth_date; ?>"></div>
        <div>Роден град: <select name="city">
          <option value="Sofia">Sofia</option>
          <option value="Plovdiv">Plovdiv</option>
          <option value="Varn">Varna</option>
        </select></div>
        <div>Говорими езици: 
        <?php
        foreach ($languages_options as $k => $v)
        {
          $selected = '';
          if (in_array($v, $known_languages))
          {
            $selected = 'checked="checked"';
          }

          echo '<input type="checkbox" name="languages[]" value="' . $v . '" ' . $selected . '> ' . $v;
        }
        ?>
        </div>
        <div>Телефон за контакт: <input type="text" name="phone" value="<?php echo $phone; ?>"></div>
        <div><textarea name="comment" placeholder="Моля напишете нещо интересно" cols="50"><?php echo $comment; ?></textarea></div>
        <div>
          <?php
          if ($id !== NULL)
          {
            echo '<input type="hidden" name="id" value="' . $id . '">';
          }
          ?>
          <input type="hidden" name="form_send" value="yes">
          <input type="submit" value="Submit">
          <input type="reset" name="reset" value="Reset">

        </div>
      </form>
    </section>

    <section>
      <table>
      <tr>
        <th>Имена</th>
        <th>Пол</th>
        <th>Дата</th>
        <th>Град</th>
        <th>Телефон</th>
        <th>Езици</th>
        <th>Коментар</th>
        <th></th>
        <th></th>
      </tr>
      <?php
      mb_internal_encoding('UTF-8'); 
      $result = $mysqli->query("SELECT * FROM users");
      while ($data = $result->fetch_object())
      {
        $result_languages = $mysqli->query("SELECT * FROM languages WHERE user_id = '$data->id'");
      ?>
      <tr>
        <td><?php echo $data->names; ?></td>
        <td><?php echo $gender_options[$data->gender]; ?></td>
        <td><?php echo date('d/m/Y', strtotime($data->birth_date)); ?></td>
        <td><?php echo $data->city; ?></td>
        <td><?php echo $data->phone; ?></td>
        <td>
          <ul>
          <?php
          while ($data_languages = $result_languages->fetch_object())
          {
            echo '<li>' . $data_languages->name . '</li>';
          }
          ?>
          </ul>
        </td>
        <td><?php echo $data->comment; ?></td>
        <td><a href="?id=<?php echo $data->id; ?>">Редакция</a></td>
        <td><a href="?delete_id=<?php echo $data->id; ?>">Изтриване</a></td>
      </tr>
      <?php
      }
      ?>
      </table>
    </section>
  </body>
</html>
