<form action="/user/updateUser/" method="post">
    <input type="hidden" name="id" value="{{ userId }}">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <p>
        <label for="user-name">Имя:</label>
        <input id="user-name" type="text" name="name" value="{{ name }}">
    </p>
    <p>
        <label for="user-lastname">Фамилия:</label>
        <input id="user-lastname" type="text" name="lastname" value="{{ lastname }}">
    </p>
    <p>
        <label for="user-login">Логин:</label>
        <input id="user-login" type="text" name="login" value="{{ login }}">
    </p>
    <p>
        <label for="user-password">Пароль:</label>
        <input id="user-password" type="text" name="password">
    </p>
    <p>
        <label for="user-birthday">День рождения:</label>
        <input id="user-birthday" type="text" name="birthday" value="{{ birthday }}" placeholder="ДД-ММ-ГГГГ">
    </p>
    <p><input type="submit" value="Сохранить"></p>
</form>