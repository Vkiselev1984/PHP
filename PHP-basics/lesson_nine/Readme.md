Adjust the list of users so that all users with administrator rights in the table see two additional links - editing and deleting a user. In this case, editing will go to the form, and deleting in asynchronous mode will delete the user from both the table and the database.

To add the functionality to edit and delete users for admins, you'll need to make changes to the actionIndex method of your UserController, as well as the template that displays the list of users.
Steps to implement Change the actionIndex method: You'll need to pass information about whether the current user is an admin to the template.

Template change: In the template that displays the list of users, add links to edit and delete users if the current user has admin rights.

Create an asynchronous delete: Implement JavaScript to handle deleting a user without reloading the page.

Example changes

1. Change the actionIndex method
   In the actionIndex method, add a check for admin rights and pass that information to the template:

```php
public function actionIndex(): string
{
$users = User::getAllUsersFromStorage();
$isAdmin = in_array('admin', Application::$auth->getUserRoles()); // Assumes you have a method to get user roles

if (!$users) {
return $this->render->renderPage(
'user-empty.twig',
[
'title' => 'List of users in storage',
'message' => "List is empty or not found",
'isAdmin' => $isAdmin
]
);
}

return $this->render->renderPage(
'user-index.twig',
[
'title' => 'List of users in storage',
'users' => $users,
'isAdmin' => $isAdmin
]
);
}
```

2. Modify the user-index.twig template

In the template, add links to edit and delete users if the current user is an administrator:

```twig
{% for user in users %}
<tr>
<td>{{ user.name }}</td>
<td>{{ user.lastname }}</td>
<td>
{% if isAdmin %}
<a href="/user/edit/{{ user.id_user }}">Edit</a>
<button class="delete-user" data-user-id="{{ user.id_user }}">Delete</button>
{% endif %}
</td>
</tr>
{% endfor %}
```

3. Asynchronously delete a user
   Add JavaScript to handle deleting a user:

```javascript
<script>
document.addEventListener('DOMContentLoaded', function() {
const deleteButtons = document.querySelectorAll('.delete-user');

 deleteButtons.forEach(button => {
 button.addEventListener('click', function() {
 const userId = this.getAttribute('data-user-id');

 if (confirm('Are you sure you want to delete this user?')) {
 fetch(`/user/delete/${userId}`, {
 method: 'DELETE'
 })
 .then(response => response.json())
 .then(data => {
 if (data.success) {
 // Delete a row from the table
 this.closest('tr').remove();
 } else {
 alert(data.message);
 }
 })
 .catch(error => console.error('Error:', error));
 }
 });
 });
});
</script>
```

This code listens for clicks on delete buttons and sends a DELETE request to the server when the user confirms the deletion. The response from the server is then processed to either remove the user from the table or display an error message.

4. Implement the delete action in the UserController
   In the UserController, implement the delete action to handle the DELETE request:

```php
public function actionDelete(int $id_user): string
{
if (!$this->isAdmin()) {
return $this->render->renderPage(
'user-empty.twig',
[
'title' => 'List of users in storage',
'message' => "You don't have permission to delete users",
]
);
}

if ($this->deleteUserFromStorage($id_user)) {
return $this->render->renderPage(
'user-empty.twig',
[
'title' => 'List of users in storage',
'message' => "User with ID {$id_user
} has been deleted",
]
);
} else {
return $this->render->renderPage(
'user-empty.twig',
[
'title' => 'List of users in storage',
'message' => "User with ID {$id_user} not found",

]
);
}
}
```

This code checks if the user is an admin and then deletes the user from the storage. If the user is not found, it displays an error message.

5. Implement the delete method in the User class
   In the User class, implement the deleteUserFromStorage method to delete the user from the storage:

```php
public function deleteUserFromStorage(): bool
{
// Implement the logic to delete the user from the storage
}
```

This method should delete the user from the storage, such as removing it from an array or database table.

6. Test the functionality

Test the functionality by creating some users, logging in as an admin, and verifying that the edit and delete links appear for users with admin rights.

7. In the User class, add the getUserRoles method:

```php
public function getUserRoles(int $userId): array
{
    $sql = "SELECT role FROM user_roles WHERE user_id = :user_id";
    $handler = Application::$storage->get()->prepare($sql);
    $handler->execute(['user_id' => $userId]);
    $roles = $handler->fetchAll(PDO::FETCH_COLUMN);

    return $roles ?: [];
}
```

Now you can use this method in your UserController to get the roles of the current user:

```php
public function actionIndex(): string
{
    $users = User::getAllUsersFromStorage();
    $userId = $_SESSION['id_user'];
    $user = User::getUserById($userId);
    $isAdmin = in_array('admin', $user->getUserRoles($userId));
}
```
