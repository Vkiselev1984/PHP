<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* user-index.tpl */
class __TwigTemplate_9e876dc8b08b34ddae441d2fe0085022 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<p>Список пользователей в хранилище</p>
<h2>Роли пользователя: <?php echo implode(', ', \$userRoles); ?></h2>
<div class=\"table-responsive small\">
    <table class=\"table table-striped table-sm\">
        <thead>
            <tr>
                <th scope=\"col\">ID</th>
                <th scope=\"col\">Имя</th>
                <th scope=\"col\">Фамилия</th>
                <th scope=\"col\">День рождения</th>
                <th scope=\"col\">Действия</th>
            </tr>
        </thead>
        <tbody>
            ";
        // line 15
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["users"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["user"]) {
            // line 16
            echo "            <tr>       
                <td>";
            // line 17
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserId", [], "method", false, false, false, 17), "html", null, true);
            echo "</td>   
                <td>";
            // line 18
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserName", [], "method", false, false, false, 18), "html", null, true);
            echo "</td>
                <td>";
            // line 19
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserLastName", [], "method", false, false, false, 19), "html", null, true);
            echo "</td>
                <td>";
            // line 20
            if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, $context["user"], "getUserBirthday", [], "method", false, false, false, 20))) {
                // line 21
                echo "                        ";
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserBirthday", [], "method", false, false, false, 21), "d.m.Y"), "html", null, true);
                echo "
                    ";
            } else {
                // line 23
                echo "                        <b>Не задан</b>
                    ";
            }
            // line 25
            echo "                </td>
                <td>
                    ";
            // line 27
            if (twig_in_filter("admin", ($context["userRoles"] ?? null))) {
                // line 28
                echo "                        <a href=\"/user/updateUser/?id=";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserId", [], "method", false, false, false, 28), "html", null, true);
                echo "\">Редактировать</a> | 
                        <a href=\"javascript:void(0);\" onclick=\"deleteUser(";
                // line 29
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserId", [], "method", false, false, false, 29), "html", null, true);
                echo ")\">Удалить</a>
                    ";
            } else {
                // line 31
                echo "                        <span>Нет доступа</span>
                    ";
            }
            // line 33
            echo "                </td>
            </tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['user'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 36
        echo "        </tbody>
    </table>
</div>

<script src=\"https://code.jquery.com/jquery-3.7.0.min.js\"></script>
<script>
    let maxId = \$('.table-responsive tbody tr:last-child td:first-child').html();
  
    setInterval(function () {
        \$.ajax({
            method: 'POST',
            url: \"/user/indexRefresh/\",
            data: { maxId: maxId }
        }).done(function (response) {
            console.log(response); // Логируем ответ сервера
            try {
                let users = \$.parseJSON(response);
                
                if(users.length != 0){
                    for(var k in users){
                        let row = \"<tr>\";
                        row += \"<td>\" + users[k].id + \"</td>\";
                        maxId = users[k].id;
                        row += \"<td>\" + users[k].username + \"</td>\";
                        row += \"<td>\" + users[k].userlastname + \"</td>\";
                        row += \"<td>\" + (users[k].userbirthday ? users[k].userbirthday : \"<b>Не задан</b>\") + \"</td>\";
                        row += \"<td>\";
                        row += \"<a href='edit_user.php?id=\" + users[k].id + \"'>Редактировать</a> | <a href='javascript:void(0);' onclick='deleteUser(\" + users[k].id + \")'>Удалить</a>\";
                        row += \"</td>\";
                        row += \"</tr>\";

                        \$('.table-responsive tbody').append(row);
                    }
                }
            } catch (e) {
                console.error('Ошибка парсинга JSON:', e);
            }
        });
    }, 10000);

    function deleteUser(userId) {
        console.log(\"Delete user called with ID:\", userId);
        if (confirm(\"Вы уверены, что хотите удалить пользователя?\")) {
            \$.ajax({
                type: \"POST\",
                url: \"/user/DeleteUser\",
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
        \$.ajax({
            method: 'POST',
            url: \"/user/indexRefresh/\",
            data: { maxId: maxId }
        }).done(function (response) {
            console.log(response); // Логируем ответ сервера
            try {
                let users = \$.parseJSON(response);
                \$('.table-responsive tbody').empty(); // Очищаем текущий список
                if(users.length != 0){
                    for(var k in users){
                        let row = \"<tr>\";
                        row += \"<td>\" + users[k].id + \"</td>\";
                        maxId = users[k].id;
                        row += \"<td>\" + users[k].username + \"</td>\";
                        row += \"<td>\" + users[k].userlastname + \"</td>\";
                        row += \"<td>\" + (users[k].userbirthday ? users[k].userbirthday : \"<b>Не задан</b>\") + \"</td>\";
                        row += \"<td>\";
                        row += \"<a href='edit_user.php?id=\" + users[k].id + \"'>Редактировать</a> | <a href='javascript:void(0);' onclick='deleteUser(\" + users[k].id + \")'>Удалить</a>\";
                        row += \"</td>\";
                        row += \"</tr>\";

                        \$('.table-responsive tbody').append(row);
                    }
                }
            } catch (e) {
                console.error('Ошибка парсинга JSON:', e);
            }
        });
    }
</script>";
    }

    public function getTemplateName()
    {
        return "user-index.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  112 => 36,  104 => 33,  100 => 31,  95 => 29,  90 => 28,  88 => 27,  84 => 25,  80 => 23,  74 => 21,  72 => 20,  68 => 19,  64 => 18,  60 => 17,  57 => 16,  53 => 15,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "user-index.tpl", "/data/mysite.local/src/Domain/Views/user-index.tpl");
    }
}
