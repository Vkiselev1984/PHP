<form method="POST" action="/user/updateUser">
    <input type="hidden" name="id" value="<?php echo $userId; ?>">
    
    <label for="name">Имя:</label>
    <input type="text" name="name" id="name" placeholder="Введите имя">
    
    <label for="lastname">Фамилия:</label>
    <input type="text" name="lastname" id="lastname" placeholder="Введите фамилию">
    
    <label for="login">Логин:</label>
    <input type="text" name="login" id="login" placeholder="Введите логин">
    
    <label for="birthday">День рождения:</label>
    <input type="date" name="birthday" id="birthday">
    
    <button type="submit">Обновить</button>
</form>