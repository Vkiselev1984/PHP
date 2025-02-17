{% if user_authorized %}
    <a href="/user/edit?id={{ user.id }}" class="btn btn-warning">Редактировать</a>
    <a href="/user/delete?id={{ user.id }}" class="btn btn-danger">Удалить</a>
{% endif %}

<p>Список пользователей в хранилище</p>
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

<meta name="csrf-token" content="{{ csrf_token }}">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
    // Получаем CSRF-токен из мета-тега
    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    console.log("CSRF Token:", csrfToken);

    function deleteUser(userId) {
        console.log("Delete user called with ID:", userId);
        if (confirm("Вы уверены, что хотите удалить пользователя?")) {
            $.ajax({
                type: "POST",
                url: "/user/DeleteUser",
                data: { id: userId, csrf_token: csrfToken }, 
                success: function(response) {
                    console.log("Response from server:", response);
                    if (typeof response === "string") {
                        try {
                            response = JSON.parse(response);
                        } catch (e) {
                            console.error("Ошибка парсинга JSON:", e);
                        }
                    }
                    if (response.success) {
                        console.log("Пользователь успешно удален.");
                        location.reload();
                    } else {
                        alert('Ошибка: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Ошибка AJAX:", error);
                    alert('Ошибка при удалении пользователя. Статус: ' + status + ', Ошибка: ' + error);
                }
            });
        }
    }
</script>