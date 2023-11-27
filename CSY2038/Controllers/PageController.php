<?php

namespace CSY2038\Controllers;

use CSY\DatabaseTable;
use CSY\MyPDO;

class PageController
{

    private $pdo;

    private $dbJobs;
    private $dbCat;
    private $dbApp;
    private $dbPat;
    private $dbContact;

    private $get;

    private $post;
    
    public function __construct(
        DatabaseTable $dbUsers,
        DatabaseTable $dbPat,
        DatabaseTable $dbContact,
        array $get,
        array $post
    ) {
        $myDb = new MyPDO();
        $this->pdo = $myDb->db();
        $this->dbContact = $dbContact;
        $this->get = $get;
        $this->post = $post;
    }

    public function home()
    {
        return ['template' => 'index.html.php', 'title' => 'Home', 'variables' => []];
    }



    public function about()
    {
        return ['template' => 'about.html.php', 'title' => 'About', 'variables' => []];
    }

    public function contact()
    {
        $me = '';
        if (isset($this->post['submit'])) {
            $contact = [
                'name' => $this->post['name'],
                'telephone' => $this->post['telephone'],
                'email' => $this->post['email'],
                'enquiry' => $this->post['enquiry']
            ];

            $this->dbContact->insert($contact);

            $me = 'Complaint Received';
        }
        return ['template' => 'contact.html.php', 'title' => 'Contact', 'variables' => ['me' => $me]];
    }

    public function faqs()
    {
        return ['template' => 'faqs.html.php', 'title' => 'FAQs', 'variables' => []];
    }

    public function apply()
    {
        if (isset($this->post['submit'])) {
            $fileName = '';
            if (isset($_FILES['cv'])) {
                if ($_FILES['cv']['error'] == 0) {

                    $parts = explode('.', $_FILES['cv']['name']);

                    $extension = end($parts);

                    $fileName = uniqid() . '.' . $extension;

                    move_uploaded_file($_FILES['cv']['tmp_name'], 'cvs/' . $fileName);
                }
            }
            $applicants = [
                'name' => $this->post['name'],
                'email' => $this->post['email'],
                'details' => $this->post['details'],
                'jobId' => $this->post['jobId'],
                'cv' => $fileName
            ];
            $applicants = $this->dbApp->insert($applicants);
            return ['template' => 'complete.html.php', 'title' => 'Apply', 'variables' => []];
        } else {
            $job = $this->dbJobs->find("id", $this->get['id'])[0];
            return ['template' => 'apply.html.php', 'title' => 'Apply', 'variables' => ["job" => $job]];
        }
    }
}
