  <html>
 <head>
  <title>Добавление пользователей</title>
  <script src="assets/jquery.min.js"></script>
  <link rel="stylesheet" href="assets/bootstrap.min.css" />
  <script src="assets/bootstrap.min.js"></script>
  <style>
   body
   {
    margin:0;
    padding:0;
    background-color:#f1f1f1;
   }
   .box
   {
    width:1270px;
    padding:20px;
    background-color:#fff;
    border:1px solid #ccc;
    border-radius:5px;
    margin-top:100px;
   }
  </style>
 </head>
 <body>
  <div class="container box">
   <h1 align="center">Добавление записей</h1>
   <br />
   <div align="right">
    <button type="button" id="modal_button" class="btn btn-info">Добавить запись</button>
    <!-- При нажатии кнопки Добавить запись подгружается модальное окно !-->
   </div>
   <br />
   <div id="result" class="table-responsive"> 
	<!-- В этот тэг будут подгружаться информация из базы!-->
   </div>
  </div>
 </body>
</html>

<!-- Модальное окно которое будем использовать для добавления или изменения информации, в данный момент скрыто!-->
<div id="customerModal" class="modal fade">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <h4 class="modal-title">Добавить запись</h4>
   </div>
   <div class="modal-body">
    <label>Введите имя</label>
    <input type="text" name="firstname" id="firstname" class="form-control" />
    <br />
    <label>Введите отчество</label>
    <input type="text" name="lastname" id="lastname" class="form-control" />
    <br />
   </div>
   <div class="modal-footer">
    <input type="hidden" name="customer_id" id="customer_id" />
    <input type="submit" name="action" id="action" class="btn btn-success" />
    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
   </div>
  </div>
 </div>
</div>

<script>
$(document).ready(function(){
 fetchUser(); //Функция подгружает данные после загрузки страницы в тэг <div id="result">
 function fetchUser() 
 {
  var action = "Load";
  $.ajax({
   url : "action.php", //Ajax запрос отсылается к "action.php"
   method:"POST", //Используем POST метод
   data:{action:action}, //пересылаем данные на сервер
   success:function(data){
    $('#result').html(data); //После получения результата отображаем в информацию в тэге <div id="result">
   }
  });
 }

 //Очищаем поля ввода данных модального окна для заполнения информацией
 $('#modal_button').click(function(){
  $('#customerModal').modal('show'); //Отображаем модальное окно
  $('#firstname').val(''); 
  $('#lastname').val(''); 
  $('.modal-title').text("Добавление новой записи"); //Заголовок модального окна
  $('#action').val('Добавить'); //Кнопка добавления модального окна
 });

 //Этот JQuery код отправляет данные введенные в модальном окне на сервер, код используется как для добавления так и для обновления записей
 $('#action').click(function(){
  var firstName = $('#firstname').val(); //Получаем значение Имени.
  var lastName = $('#lastname').val(); //Получаем значение Отчества.
  var id = $('#customer_id').val();  //Значение ключа id
  var action = $('#action').val();  //Получаем значение кнопки - добавить или обновить
  if(firstName != '' && lastName != '') //Проверка есть ли значения в переменных
  {
   $.ajax({
    url : "action.php",    //Обращаемся к "action.php page"
    method:"POST",     //Для отправки используем POST метод
    data:{firstName:firstName, lastName:lastName, id:id, action:action}, //Посылаем данные на сервер
    success:function(data){
     alert(data);    //Отображаем сообщение от сервера о успешном добавлении или обновлении информации
     $('#customerModal').modal('hide'); //Прячем модальное окно
     fetchUser();    // загружаем измененную информацию из таблицы clients
    }
   });
  }
  else
  {
   alert("Все поля должны быть заполнены"); //Проверяем все ли поля заполнены
  }
 });

 //JQuery код для обновления записи без перезагрузки страницы
 $(document).on('click', '.update', function(){
  var id = $(this).attr("id"); //определяем id записи для дальнейшего обновления данных 
  var action = "Select";   //Определяем действие Select
  $.ajax({
   url:"action.php",   //Обращаемся к "action.php"
   method:"POST",    //Для отправки используем POST метод
   data:{id:id, action:action},//Посылаем данные на сервер
   dataType:"json",   //Определяем тип пересылаемых данных в формате JSON
   success:function(data){
    $('#customerModal').modal('show');   //Отображаем модальное окно для обновления записей
    $('.modal-title').text("Обновить записи"); //Заголовок модального окна
    $('#action').val("Обновить");     //Название кнопки окна
    $('#customer_id').val(id);     
    $('#firstname').val(data.firstname); 
    $('#lastname').val(data.lastname);  
   }
  });
 });

 //JQuery код для удаления записи без перезагрузки страницы
 $(document).on('click', '.delete', function(){
  var id = $(this).attr("id"); //определяем id записи для дальнейшего удаления 
  if(confirm("Вы уверены что хотите удалить эти данные?")) //Проверка
  {
   var action = "Delete"; //Определяем действие 
   $.ajax({
    url:"action.php",    //Обращаемся к "action.php"
    method:"POST",     //Методом POST
    data:{id:id, action:action}, //пересылаем данные на сервер
    success:function(data)
    {
     fetchUser();    // при успешном выполнении обновляем список абонентов
     alert(data);    //информационное сообщение о успешном удалении
    }
   })
  }
  else  //Если нажали отмена в вопросе о удалении записи
  {
   return false; //ничего не делаем
  }
 });
});
</script>
 