<?php
namespace CSY;
class EntryPoint
{
    private $routes;
    public function __construct(Routes $routes){
        $this->routes = $routes;
    }
    public function run(): void
    {

            $page = $this->routes->getPage();
            $output = $this->loadTemplate('../templates/' . $page['template'], $page['variables']);
            $title = $page['title'];

            require '../templates/layout.html.php';

    }
    public function loadTemplate($fileName, $templateVars)
    {
        extract($templateVars);
        ob_start();
        require $fileName;
        $output = ob_get_clean();
        return $output;
    }
}
