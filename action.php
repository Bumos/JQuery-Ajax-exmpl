 <?php
//Для соединения используем PHP PDO
$username = 'root';
$password = '';
// Создаем объект PDO соединения с базой данных
$connection = new PDO( 'mysql:host=localhost;dbname=registratura', $username, $password ); 
$connection -> exec("SET CHARACTER SET utf8");
if(isset($_POST["action"])) //Проверяем что значение для переменной $_POST["action"] не назначены
{
//Рисуем таблицу и выводим список имен и фамилий из базы данных из таблицы clients 
 if($_POST["action"] == "Load") 
 {
  $statement = $connection->prepare("SELECT * FROM clients ORDER BY id DESC");
  $statement->execute();
  $result = $statement->fetchAll();
  $output = '';
  $output .= '
   <table class="table table-bordered">
    <tr>
     <th width="40%">ИМЯ</th>
     <th width="40%">Отчество</th>
     <th width="10%">Обновить</th>
     <th width="10%">Удалить</th>
    </tr>
  ';
  if($statement->rowCount() > 0)
  {
   foreach($result as $row)
   {
    $output .= '
    <tr>
     <td>'.$row["firstname"].'</td>
     <td>'.$row["lastname"].'</td>
     <td><button type="button" id="'.$row["id"].'" class="btn btn-warning btn-xs update">Обновить</button></td>
     <td><button type="button" id="'.$row["id"].'" class="btn btn-danger btn-xs delete">Удалить</button></td>
    </tr>
    ';
   }
  }
  else
  {
   $output .= '
    <tr>
     <td align="center">Данных нет</td>
    </tr>
   ';
  }
  $output .= '</table>';
  echo $output;
 }

 //Добавляем новую запись в базу данных
 if($_POST["action"] == "Добавить")
 {
  $statement = $connection->prepare("
   INSERT INTO clients (firstname, lastname) 
   VALUES (:firstname, :lastname)
  ");
  $result = $statement->execute(
   array(
    ':firstname' => $_POST["firstName"],
    ':lastname' => $_POST["lastName"]
   )
  );
  if(!empty($result))
  {
   echo 'Данные записаны';
  }
 }
 //При нажатии кнопки обновить подгружаем информацию из таблицы clients
 //в модальное окно для изменения
 if($_POST["action"] == "Select")
 {
  $output = array();
  $statement = $connection->prepare(
   "SELECT * FROM clients 
   WHERE id = '".$_POST["id"]."' 
   LIMIT 1"
  );
  $statement->execute();
  $result = $statement->fetchAll();
  foreach($result as $row)
  {
   $output["firstname"] = $row["firstname"];
   $output["lastname"] = $row["lastname"];
  }
  echo json_encode($output);
 }

 if($_POST["action"] == "Обновить")
 {
  $statement = $connection->prepare(
   "UPDATE clients 
   SET firstname = :firstname, lastname = :lastname 
   WHERE id = :id
   "
  );
  $result = $statement->execute(
   array(
    ':firstname' => $_POST["firstName"],
    ':lastname' => $_POST["lastName"],
    ':id'   => $_POST["id"]
   )
  );
  if(!empty($result))
  {
   echo 'Данные обновлены';
  }
 }
//Удаляем из базы запись
 if($_POST["action"] == "Delete")
 {
  $statement = $connection->prepare(
   "DELETE FROM clients WHERE id = :id"
  );
  $result = $statement->execute(
   array(
    ':id' => $_POST["id"]
   )
  );
  if(!empty($result))
  {
   echo 'Данные удалены';
  }
 }

}

?>
 