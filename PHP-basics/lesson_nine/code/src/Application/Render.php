<?php

namespace Geekbrains\Application1\Application;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Render
{

    private string $viewFolder = '/src/Domain/Views/';
    private FilesystemLoader $loader;
    private Environment $environment;

    public function __construct()
    {
        $this->loader = new FilesystemLoader($_SERVER['DOCUMENT_ROOT'] . '/../' . $this->viewFolder);
        $this->environment = new Environment($this->loader, [
            'cache' => $_SERVER['DOCUMENT_ROOT'] . '/../cache/',
        ]);
        echo "Render initialized with view folder: " . $this->viewFolder . "\n"; // Отладочное сообщение
    }

    public function renderPage(string $contentTemplateName = 'page-index.tpl', array $templateVariables = []): string
    {
        echo "Rendering page: $contentTemplateName\n"; // Отладочное сообщение
        $template = $this->environment->load('main.tpl');

        $templateVariables['content_template_name'] = $contentTemplateName;

        if (isset($_SESSION['user_name'])) {
            $templateVariables['user_authorized'] = true;
            echo "User is authorized: " . $_SESSION['user_name'] . "\n"; // Отладочное сообщение
        } else {
            echo "User is not authorized.\n"; // Отладочное сообщение
        }

        return $template->render($templateVariables);
    }

    public function renderPageWithForm(string $contentTemplateName = 'page-index.tpl', array $templateVariables = []): string
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        echo "Generated CSRF token: " . $_SESSION['csrf_token'] . "\n"; // Отладочное сообщение

        $templateVariables['csrf_token'] = $_SESSION['csrf_token'];

        return $this->renderPage($contentTemplateName, $templateVariables);
    }

    public function renderPartial(string $contentTemplateName, array $templateVariables = []): string
    {
        echo "Rendering partial: $contentTemplateName\n"; // Отладочное сообщение
        $template = $this->environment->load($contentTemplateName);

        if (isset($_SESSION['user_name'])) {
            $templateVariables['user_authorized'] = true;
            echo "User is authorized: " . $_SESSION['user_name'] . "\n"; // Отладочное сообщение
        } else {
            echo "User is not authorized.\n"; // Отладочное сообщение
        }

        return $template->render($templateVariables);
    }
}