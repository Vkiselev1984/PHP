<?php

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Render;

class ErrorController
{
    private Render $render;

    public function __construct()
    // Class constructor that is called when a new ErrorController object is created.
    {
        $this->render = new Render(); // Create an instance of the Render class
// Initializes the $render property with a new Render object that will be used to render templates.
    }

    public function error404()
    // Defines the error404 method that will handle 404 errors (page not found).
    {
        http_response_code(404);
        // Sets the HTTP status code to 404, indicating that the requested page was not found.

        return $this->render->render('error.twig', ['message' => 'Page not found']);
        // Calls the render method of the Render class, passing it the template name 'error.twig' and an array with the error message.
        // This method returns the generated HTML code that will be displayed to the user.
    }
}