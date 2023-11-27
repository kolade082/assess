<?php

namespace CSY2038;

use CSY\DatabaseTable;
use CSY\MyPDO;
use CSY2038\Controllers\PageController;

class Routes implements \CSY\Routes
{
    public function getPage()
    {
        $myDb = new MyPDO();
        $pdo = $myDb->db();
        $databaseJobs = new DatabaseTable($pdo, 'job', 'id');
        $databaseCategories = new DatabaseTable($pdo, 'category', 'id');
        $databaseApplicants = new DatabaseTable($pdo, 'applicants', 'id');
        $databaseUsers = new DatabaseTable($pdo, 'user', 'id');
        $databasePat = new DatabaseTable($pdo, 'patients', 'id');
        $databaseContact = new DatabaseTable($pdo, 'contact', 'id');
        $pageController = new PageController($databaseUsers, $databasePat, $databaseContact, $_GET, $_POST);
        $page = $pageController->home();
        if ($_SERVER['REQUEST_URI'] !== '/') {
            $functionName = ltrim(explode('?', $_SERVER['REQUEST_URI'])[0], '/');
            if (str_contains($functionName, "/")) {
                //                $page = $pageController->$functionName();
                $r = explode("/", $functionName);

                $pageController = "CSY2038\Controllers\\" . ucfirst($r[0]) . "Controller";
                $functionName = $r[1];
                $pageController = new $pageController($databaseUsers, $databasePat, $databaseContact, $_GET, $_POST);
                $page = $pageController->$functionName();
            } else {
                $page = $pageController->$functionName();
            }
        }
        return $page;
    }
}
