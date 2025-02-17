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
        // line 14
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["users"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["user"]) {
            // line 15
            echo "            <tr>       
                <td>";
            // line 16
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserId", [], "method", false, false, false, 16), "html", null, true);
            echo "</td>   
                <td>";
            // line 17
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserName", [], "method", false, false, false, 17), "html", null, true);
            echo "</td>
                <td>";
            // line 18
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserLastName", [], "method", false, false, false, 18), "html", null, true);
            echo "</td>
                <td>";
            // line 19
            if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, $context["user"], "getUserBirthday", [], "method", false, false, false, 19))) {
                // line 20
                echo "                        ";
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserBirthday", [], "method", false, false, false, 20), "d.m.Y"), "html", null, true);
                echo "
                    ";
            } else {
                // line 22
                echo "                        <b>Не задан</b>
                    ";
            }
            // line 24
            echo "                </td>
                <td>
                    ";
            // line 26
            if (twig_in_filter("admin", ($context["userRoles"] ?? null))) {
                // line 27
                echo "                        <a href=\"/user/updateUser/?id=";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserId", [], "method", false, false, false, 27), "html", null, true);
                echo "\">Редактировать</a> | 
                        <a href=\"javascript:void(0);\" onclick=\"deleteUser(";
                // line 28
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserId", [], "method", false, false, false, 28), "html", null, true);
                echo ")\">Удалить</a>
                    ";
            } else {
                // line 30
                echo "                        <span>Нет доступа</span>
                    ";
            }
            // line 32
            echo "                </td>
            </tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['user'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 35
        echo "        </tbody>
    </table>
</div>

<meta name=\"csrf-token\" content=\"";
        // line 39
        echo twig_escape_filter($this->env, ($context["csrf_token"] ?? null), "html", null, true);
        echo "\">

<script src=\"https://code.jquery.com/jquery-3.7.0.min.js\"></script>
<script>
    // Получаем CSRF-токен из мета-тега
    let csrfToken = \$('meta[name=\"csrf-token\"]').attr('content');
    console.log(\"CSRF Token:\", csrfToken);

    function deleteUser(userId) {
        console.log(\"Delete user called with ID:\", userId);
        if (confirm(\"Вы уверены, что хотите удалить пользователя?\")) {
            \$.ajax({
                type: \"POST\",
                url: \"/user/DeleteUser\",
                data: { id: userId, csrf_token: csrfToken }, 
                success: function(response) {
                    console.log(\"Response from server:\", response);
                    if (typeof response === \"string\") {
                        try {
                            response = JSON.parse(response);
                        } catch (e) {
                            console.error(\"Ошибка парсинга JSON:\", e);
                        }
                    }
                    if (response.success) {
                        console.log(\"Пользователь успешно удален.\");
                        location.reload();
                    } else {
                        alert('Ошибка: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(\"Ошибка AJAX:\", error);
                    alert('Ошибка при удалении пользователя. Статус: ' + status + ', Ошибка: ' + error);
                }
            });
        }
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
        return array (  117 => 39,  111 => 35,  103 => 32,  99 => 30,  94 => 28,  89 => 27,  87 => 26,  83 => 24,  79 => 22,  73 => 20,  71 => 19,  67 => 18,  63 => 17,  59 => 16,  56 => 15,  52 => 14,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "user-index.tpl", "/data/mysite.local/src/Domain/Views/user-index.tpl");
    }
}
