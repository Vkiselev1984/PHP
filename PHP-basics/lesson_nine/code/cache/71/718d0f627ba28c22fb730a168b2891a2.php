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

/* user-index.twig */
class __TwigTemplate_cfd61cb0b4a86367897caeb62be5e508 extends Template
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
                <th scope=\"col\">Действия</th> <!-- Новый столбец для действий -->
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
                    <a href=\"/user/edit/";
            // line 27
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "getUserId", [], "method", false, false, false, 27), "html", null, true);
            echo "\" class=\"btn btn-warning btn-sm\">Редактировать</a>
                </td> <!-- Кнопка редактирования -->
            </tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['user'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 31
        echo "        </tbody>
    </table>
</div>

<script>
    let maxId = \$('.table-responsive tbody tr:last-child td:first-child').html();
  
    setInterval(function () {
        \$.ajax({
            method: 'POST',
            url: \"/user/indexRefresh/\",
            data: { maxId: maxId }
        }).done(function (response) {
            let users = \$.parseJSON(response);
            
            if(users.length != 0){
                for(var k in users){
                    let row = \"<tr>\";
                    row += \"<td>\" + users[k].id + \"</td>\";
                    maxId = users[k].id;
                    row += \"<td>\" + users[k].username + \"</td>\";
                    row += \"<td>\" + users[k].userlastname + \"</td>\";
                    row += \"<td>\" + users[k].userbirthday + \"</td>\";
                    row += \"<td><a href=\"/user/edit/";
        // line 54
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "getUserId", [], "method", false, false, false, 54), "html", null, true);
        echo "\" class=\"btn btn-warning btn-sm\">Редактировать</a></td>\"; // Кнопка редактирования для новых пользователей
                    row += \"</tr>\";
                    \$('.content-template tbody').append(row);
                }
            }
        });
    }, 10000);
</script>";
    }

    public function getTemplateName()
    {
        return "user-index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  123 => 54,  98 => 31,  88 => 27,  84 => 25,  80 => 23,  74 => 21,  72 => 20,  68 => 19,  64 => 18,  60 => 17,  57 => 16,  53 => 15,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "user-index.twig", "/data/mysite.local/src/Domain/Views/user-index.twig");
    }
}
