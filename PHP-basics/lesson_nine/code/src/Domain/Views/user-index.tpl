<p>Список пользователей в хранилище</p>
<h2>Роли пользователя: <?php echo implode(', ', $userRoles); ?></h2>
<div class="table-responsive small">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Имя</th>
                <th scope="col">Фамилия</th>
                <th scope="col">День рождения</th>
                <th scope="col">Действия</th>
            </tr>
        </thead>
        <tbody>
            {% for user in users %}
            <tr>       
                <td>{{ user.getUserId() }}</td>   
                <td>{{ user.getUserName() }}</td>
                <td>{{ user.getUserLastName() }}</td>
                <td>{% if user.getUserBirthday() is not empty %}
                        {{ user.getUserBirthday() | date('d.m.Y') }}
                    {% else %}
                        <b>Не задан</b>
                    {% endif %}
                </td>
                <td>
                    {% if 'admin' in userRoles %}
                        <a href="/user/updateUser/?id={{ user.getUserId() }}">Редактировать</a> | 
                        <a href="javascript:void(0);" onclick="deleteUser({{ user.getUserId() }})">Удалить</a>
                    {% else %}
                        <span>Нет доступа</span>
                    {% endif %}
                </td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
    let maxId = $('.table-responsive tbody tr:last-child td:first-child').html();
  
    setInterval(function () {
        $.ajax({
            method: 'POST',
            url: "/user/indexRefresh/",
            data: { maxId: maxId }
        }).done(function (response) {
            console.log(response); // Логируем ответ сервера
            try {
                let users = $.parseJSON(response);
                
                if(users.length != 0){
                    for(var k in users){
                        let row = "<tr>";
                        row += "<td>" + users[k].id + "</td>";
                        maxId = users[k].id;
                        row += "<td>" + users[k].username + "</td>";
                        row += "<td>" + users[k].userlastname + "</td>";
                        row += "<td>" + (users[k].userbirthday ? users[k].userbirthday : "<b>Не задан</b>") + "</td>";
                        row += "<td>";
                        row += "<a href='edit_user.php?id=" + users[k].id + "'>Редактировать</a> | <a href='javascript:void(0);' onclick='deleteUser(" + users[k].id + ")'>Удалить</a>";
                        row += "</td>";
                        row += "</tr>";

                        $('.table-responsive tbody').append(row);
                    }
                }
            } catch (e) {
                console.error('Ошибка парсинга JSON:', e);
            }
        });
    }, 10000);

    function deleteUser(userId) {
        console.log("Delete user called with ID:", userId);
        if (confirm("Вы уверены, что хотите удалить пользователя?")) {
            $.ajax({
                type: "POST",
                url: "/user/DeleteUser",
                data: { id: userId },
                success: function(response) {
                    console.log(response);
                    // Обновляем список пользователей после удаления
                    updateUserList();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('Ошибка при удалении пользователя.');
                }
            });
        }
    }

    function updateUserList() {
        $.ajax({
            method: 'POST',
            url: "/user/indexRefresh/",
            data: { maxId: maxId }
        }).done(function (response) {
            console.log(response); // Логируем ответ сервера
            try {
                let users = $.parseJSON(response);
                $('.table-responsive tbody').empty(); // Очищаем текущий список
                if(users.length != 0){
                    for(var k in users){
                        let row = "<tr>";
                        row += "<td>" + users[k].id + "</td>";
                        maxId = users[k].id;
                        row += "<td>" + users[k].username + "</td>";
                        row += "<td>" + users[k].userlastname + "</td>";
                        row += "<td>" + (users[k].userbirthday ? users[k].userbirthday : "<b>Не задан</b>") + "</td>";
                        row += "<td>";
                        row += "<a href='edit_user.php?id=" + users[k].id + "'>Редактировать</a> | <a href='javascript:void(0);' onclick='deleteUser(" + users[k].id + ")'>Удалить</a>";
                        row += "</td>";
                        row += "</tr>";

                        $('.table-responsive tbody').append(row);
                    }
                }
            } catch (e) {
                console.error('Ошибка парсинга JSON:', e);
            }
        });
    }
</script>