{% if not user_authorized %}
    <div class="col-md-3 text-end">
        <a href="/user/login/" class="btn btn-primary">Войти</a>
    </div>
{% else %}
    <p><a href="/logout" class="btn btn-primary">Logout</a></p>
{% endif %}