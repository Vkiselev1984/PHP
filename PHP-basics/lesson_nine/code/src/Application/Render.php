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
    }

    public function renderPage(string $contentTemplateName = 'page-index.tpl', array $templateVariables = []): string
    {
        try {
            $template = $this->environment->load('main.tpl');
        } catch (\Exception $e) {
            throw new \Exception("Ошибка загрузки шаблона: " . $e->getMessage());
        }

        $templateVariables['content_template_name'] = $contentTemplateName;
        return $template->render($templateVariables);
    }

    public function renderExceptionPage(string $errorMessage): string
    {
        return $this->renderPage(
            'error.tpl',
            [
                'error_message' => $errorMessage,
            ]
        );
    }

    public function renderPageWithForm(string $contentTemplateName = 'page-index.tpl', array $templateVariables = []): string
    {
        $templateVariables['csrf_token'] = $this->generateCsrfToken();

        return $this->renderPage($contentTemplateName, $templateVariables);
    }

    private function generateCsrfToken(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public function renderPartial(string $contentTemplateName, array $templateVariables = []): string
    {
        try {
            $template = $this->environment->load($contentTemplateName);
        } catch (\Exception $e) {
            throw new \Exception("Ошибка загрузки шаблона: " . $e->getMessage());
        }

        if (isset($_SESSION['user_name'])) {
            $templateVariables['user_authorized'] = true;
        }

        return $template->render($templateVariables);
    }
}