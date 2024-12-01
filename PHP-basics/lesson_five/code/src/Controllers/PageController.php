<?php

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Render;

class PageController
{
    public function actionIndex()
    // Defines the actionIndex method that will handle requests to the home page (or index page).
    {
        $render = new Render();
        // Creates a new instance of the Render class that will be used to render templates.

        return $render->renderPage('page-index.twig', ['title' => 'Home page']);
        // Calls the renderPage method of the Render class, passing it the name of the 'page-index.twig' template and an array with data.
        // In this case, the array contains the 'title' key with the value 'Home page'.
        // The method returns the generated HTML code that will be displayed to the user.
    }
}