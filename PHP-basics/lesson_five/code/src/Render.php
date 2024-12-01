<?php

namespace Geekbrains\Application1;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Render
{
    private string $viewFolder = '/src/Views/';
    // Declares a private property $viewFolder that contains the path to the templates folder.

    private FilesystemLoader $loader;
    // Declares a private property $loader that will store an instance of the FilesystemLoader class.

    private Environment $environment;
    // Declares a private property $environment that will store an instance of the Environment class.

    public function __construct()
    // Class constructor that is called when a new Render object is created.
    {
        $this->loader = new FilesystemLoader(dirname(__DIR__) . $this->viewFolder);
        // Initializes the $loader property with a new FilesystemLoader object, passing it the path to the templates folder.

        $this->environment = new Environment($this->loader, [
            // 'cache' => $_SERVER['DOCUMENT_ROOT'].'/cache/',
            // Initializes the $environment property with a new Environment object, passing it the template loader.
            // The commented line can be used to specify the template cache.
        ]);
    }

    public function renderPage(string $contentTemplateName = 'page-index.twig', array $templateVariables = []): string
    // Defines the renderPage method, which renders the page using the specified template and variables.
    {
        $mainTemplate = $this->environment->load('main.twig');
        // Loads the main template 'main.twig' from the Twig environment.

        $templateVariables['content_template_name'] = $contentTemplateName;
        // Adds the name of the content template to the variables array for the template.

        return $mainTemplate->render($templateVariables);
        // Renders the main template with the passed variables and returns the generated HTML.
    }

    public function renderErrorPage(string $errorMessage): string
    // Defines the renderErrorPage method, which renders an error page with the specified message.
    {
        $errorTemplate = $this->environment->load('error.twig');
        // Loads the 'error.twig' error template from the Twig environment.

        $templateVariables = ['message' => $errorMessage];
        // Creates an array of variables containing the error message.

        return $errorTemplate->render($templateVariables);
        // Renders the error template with the passed variables and returns the generated HTML.
    }

    public function render(string $template, array $data): string
    // Defines a render method that renders the specified template with the passed data.
    {
        $templatePath = dirname(__DIR__) . $this->viewFolder . $template;
        // Generates the full path to the template using the directory and template name.

        if (!file_exists($templatePath)) {
            return "Template not found: $templatePath";
            // Checks if the template file exists. If not, returns an error message.
        }

        ob_start();
        // Starts output buffering to capture the output into a variable.

        extract($data);
        // Extracts the variables from the $data array and makes them available in the current scope.

        include $templatePath;
        // Includes the template file, allowing its contents to be rendered.

        return ob_get_clean();
        // Stops output buffering and returns the buffer contents as a string.
    }
}