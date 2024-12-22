# Improving the application

## Task

Adjust the list of users so that all users with administrator rights in the table see two additional links - editing and deleting a user. In this case, editing will go to the form, and deleting in asynchronous mode will delete the user from both the table and the database.

## Solution

To make the requested changes, we'll fix the part of the code that handles user roles and permissions. Specifically, we'll add logic to check if the user has admin rights, and if so, provide them with additional links to edit and delete users.

Let's change the checkAccessToMethod function to include logic for admins.
Let's make sure that if the user has admin rights, they'll see links to edit and delete users.

```javascript
private function checkAccessToMethod(AbstractController $controllerInstance, string $methodName): bool
{
    $userRoles = $controllerInstance->getUserRoles();
    $rules = $controllerInstance->getActionsPermissions($methodName);
    $isAllowed = false;

    echo "Проверка доступа к методу: " . $methodName . "\n";
    echo "Роли пользователя: " . implode(', ', $userRoles) . "\n";
    echo "Необходимые разрешения: " . implode(', ', $rules) . "\n";

    if (in_array('admin', $userRoles)) {
        if (in_array($methodName, ['actionEditUser', 'actionDeleteUser'])) {
            return true;
        }
    }

    if (!empty($rules)) {
        foreach ($rules as $rolePermission) {
            if (in_array($rolePermission, $userRoles)) {
                $isAllowed = true;
                break;
            }
        }
    } else {
        $isAllowed = true;
    }

    return $isAllowed;
}
```

Let's add two new columns to the table in the HTML code that displays the list of users: one for editing and one for deleting, and implement the deletion function via AJAX.

```html
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
        <!-- Новый заголовок для действий -->
      </tr>
    </thead>
    <tbody>
      {% for user in users %}
      <tr>
        <td>{{ user.getUserId() }}</td>
        <td>{{ user.getUserName() }}</td>
        <td>{{ user.getUserLastName() }}</td>
        <td>
          {% if user.getUserBirthday() is not empty %} {{ user.getUserBirthday()
          | date('d.m.Y') }} {% else %}
          <b>Не задан</b>
          {% endif %}
        </td>
        <td>
          <a href="edit_user.php?id={{ user.getUserId() }}">Редактировать</a> |
          <a href="#" onclick="deleteUser({{ user.getUserId() }})">Удалить</a>
        </td>
        <!-- Ссылки для редактирования и удаления -->
      </tr>
      {% endfor %}
    </tbody>
  </table>
</div>

<script>
  let maxId = $(".table-responsive tbody tr:last-child td:first-child").html();

  setInterval(function () {
    $.ajax({
      method: "POST",
      url: "/user/indexRefresh/",
      data: { maxId: maxId },
    }).done(function (response) {
      let users = $.parseJSON(response);

      if (users.length != 0) {
        for (var k in users) {
          let row = "<tr>";
          row += "<td>" + users[k].id + "</td>";
          maxId = users[k].id;
          row += "<td>" + users[k].username + "</td>";
          row += "<td>" + users[k].userlastname + "</td>";
          row += "<td>" + users[k].userbirthday + "</td>";
          row +=
            "<td><a href='edit_user.php?id=" +
            users[k].id +
            "'>Редактировать</a> | <a href='#' onclick='deleteUser(" +
            users[k].id +
            ")'>Удалить</a></td>";
          row += "</tr>";

          $(".content-template tbody").append(row);
        }
      }
    });
  }, 10000);

  function deleteUser(userId) {
    if (confirm("Вы уверены, что хотите удалить пользователя?")) {
      $.ajax({
        method: "DELETE",
        url: "delete_user.php?id=" + userId,
      }).done(function (response) {
        if (response.success) {
          alert("Пользователь удален.");
          location.reload(); // Перезагружаем страницу после удаления
        } else {
          alert("Ошибка при удалении пользователя.");
        }
      });
    }
  }
</script>
```
