<?php

namespace CSY2038\Controllers;

use CSY\DatabaseTable;
use CSY\MyPDO;

class AdminController
{

    private $dbJobs;
    private $dbCat;
    private $dbUsers;
    private $dbPat;

    private $post;
    private $pdo;
    private $get;
    private $validator;
    private $dbContact;

    public function __construct(
        DatabaseTable $dbUsers,
        DatabaseTable $dbPat,
        DatabaseTable $dbContact,
        array $get,
        array $post
    ) {
        $myDb = new MyPDO();
        $this->pdo = $myDb->db();
        $this->dbUsers = $dbUsers;
        $this->dbPat = $dbPat;
        $this->dbContact = $dbContact;
        $this->get = $get;
        $this->post = $post;
        $validator = new Validations();
        $this->validator = $validator;
    }

    public function index()
    {
        $this->session();
        $this->chklogin();

        return ['template' => 'admin/index.html.php', 'title' => 'Admin', 'variables' => []];
    }

    public function dashboard()
    {
        $this->session();
        $this->chklogin();

        // Example: Fetch recent patient records or any other relevant data
        // $recentPatients = $this->dbPatients->findRecent();

        // You can add more data fetching logic here as needed

        // Define the NHS Digital FHIR API sandbox endpoint
        $apiUrl = "https://sandbox.api.service.nhs.uk/personal-demographics/FHIR/R4/Patient/9000000009";

        // Set the request headers
        $headers = [
            "accept: application/fhir+json",
            "authorization: Bearer g1112R_ccQ1Ebbb4gtHBP1aaaNM",
            "nhsd-end-user-organisation-ods: Y12345",
            "nhsd-session-urid: 555254240100",
            "x-correlation-id: 11C46F5F-CDEF-4865-94B2-0EE0EDCC26DA",
            "x-request-id: 60E0B220-8136-4CA5-AE46-1D97EF59D068",
        ];
        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if(curl_errno($ch)){
            echo 'Error Occurred: '. curl_error($ch);
        }else{
            // Decode the JSON response into a PHP array
            $jsonArray = json_decode($response, true);

            $selectedData = array();
            $selectedData['id'] = $jsonArray['id'];
            if (isset($jsonArray['name']) && is_array($jsonArray['name']) && count($jsonArray['name']) > 0) {
                // Get the first element of the "name" array
                $name = $jsonArray['name'][0];
            
                // Check if "given" is present in the "name" element
                if (isset($jsonArray['name']) && is_array($jsonArray['name']) && count($jsonArray['name']) > 0) {
                    // Get the first element of the "name" array
                    $name = $jsonArray['name'][0];
                
                    // Check if "given" and "family" are present in the "name" element
                    if (isset($name['given']) && is_array($name['given']) && count($name['given']) > 0
                        && isset($name['family'])) {
                        // Store "given" and "family" names in the selected data array
                        $selectedData['givenName'] = implode(' ', $name['given']);
                        $selectedData['familyName'] = $name['family'];
                    }
                }
                if (isset($jsonArray['birthDate'])) {
                    $selectedData['dateOfBirth'] = $jsonArray['birthDate'];
                }
                
                // Check if "telecom" is present in the array
                if (isset($jsonArray['telecom']) && is_array($jsonArray['telecom']) && count($jsonArray['telecom']) > 0) {
                    foreach ($jsonArray['telecom'] as $contact) {
                        // Check if "system" is "phone" or "email" and add to selected data
                        if (isset($contact['system']) && isset($contact['value'])) {
                            if ($contact['system'] == 'phone') {
                                $selectedData['phone'] = $contact['value'];
                            } elseif ($contact['system'] == 'email') {
                                $selectedData['email'] = $contact['value'];
                            }
                        }
                    }
                }
            }

            // Output the response data
            print_r($selectedData);
        }


        
        return [
            'template' => 'admin/dashboard.html.php',
            'title' => 'Dashboard',
            'variables' => [
                // 'recentPatients' => $recentPatients
                // Include other variables as needed
            ]
        ];
    }

    public function patients()
    {
        $this->session();
        $this->chklogin();
        $patients = $this->dbPat->findAll();

        return [
            'template' => 'admin/patients.html.php',
            'title' => 'Patients',
            'variables' => ["patients" => $patients]
        ];
    }

    public function register()
    {
        $template = 'admin/register.html.php';
        $errors = [];
        if (isset($this->post['submit'])) {
            $fullname = $this->post['fullname'] ?? '';
            $username = $this->post['username'] ?? '';
            $password = $this->post['password'] ?? '';
            $usertype = $this->post['usertype'] ?? '';

            $errors = $this->validator->validateRegisterForm(
                $fullname,
                $username,
                $password,
                $usertype
            );

            if (empty($errors)) {
                $password = password_hash(
                    $this->post['password'],
                    PASSWORD_DEFAULT
                );

                $register = [
                    'fullname' => $this->post['fullname'],
                    'username' => $this->post['username'],
                    'password' => $password,
                    'usertype' => $this->post['usertype']
                ];

                $registers = $this->dbUsers->insert($register);
                header('Location: users');
            }
        }
        return [
            'template' => $template, 'title' => 'register',
            'variables' => ['errors' => $errors]
        ];
    }

    public function login()
    {
        $template = 'admin/login.html.php';
        $errors = [];
        $message = '';

        if ($this->post) {
            if (isset($this->post['submit'])) {
                $username = $this->post['username'] ?? '';
                $password = $this->post['password'] ?? '';

                $errors = $this->validator->validateLoginForm($username, $password);
                if (empty($errors)) {
                    $admin = $this->dbUsers->find(
                        "username",
                        $this->post['username']
                    );
                    if ($admin) {
                        $chkPassword = password_verify(
                            $this->post['password'],
                            $admin[0]["password"]
                        );
                        if ($chkPassword) {
                            //  valid
                            $this->session();
                            $_SESSION['loggedin'] = $admin[0]['id'];
                            $_SESSION['userDetails'] = $admin[0];

                            header('Location: dashboard');
                        } else {
                            $message = "Invalid Cred"; // password
                        }
                    } else {
                        $message = "Invalid Cred"; // username
                    }
                }
            }
        }
        return [
            'template' => $template, 'title' => 'login',
            'variables' => [
                'errors' => $errors,
                'message' => $message
            ]
        ];
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        header('Location: index');
    }

    // public function categories()
    // {
    //     $this->session();
    //     $this->chklogin();

    //     $categories = $this->dbCat->findAll();

    //     return ['template' => "admin/categories.html.php", 'title' =>
    //     'categories', 'variables' => ["categories" => $categories]];
    // }

    // public function applicants()
    // {
    //     $this->session();
    //     $this->chklogin();

    //     $job = $this->dbJobs->find("id", $this->get['id'])[0];
    //     $applicants = $this->dbApp->find("jobId", $this->get['id']);

    //     return ['template' => "admin/applicants.html.php", 'title' => 'applicants', 'variables' =>
    //     ["job" => $job, "applicants" => $applicants]];
    // }

    // public function jobs()
    // {
    //     $this->session();
    //     $this->chklogin();

    //     $criteria = [];
    //     $statment = 'SELECT j.*, c.name as catName, (SELECT count(*) 
    //                     as count FROM applicants a WHERE a.jobId = j.id) as count FROM job j LEFT JOIN 
    //                     category c ON c.id = j.categoryId';
    //     if (isset($this->get['category_name']) && $this->get['category_name'] != "All") {
    //         $statment .= ' WHERE j.categoryId =:categoryId ';
    //         $criteria = [
    //             'categoryId' => $this->get['category_name']
    //         ];
    //     }
    //     if ($_SESSION['userDetails']['usertype'] == 'CLIENT') {
    //         if (isset($this->get['category_name']) && $this->get['category_name'] != "All") {
    //             $statment .= ' AND j.userId =:userId ';
    //             $criteria['userId'] = $_SESSION['userDetails']['id'];
    //         } else {
    //             $statment .= ' WHERE j.userId =:userId ';
    //             $criteria['userId'] = $_SESSION['userDetails']['id'];
    //         }
    //     }

    //     $jobs = $this->dbJobs->customFind($statment, $criteria);
    //     $categories = $this->dbCat->findAll();
    //     return ['template' => "admin/jobs.html.php", 'title' => 'jobs', 'variables' =>
    //     ["jobs" => $jobs, "categories" => $categories]];
    // }

    // public function addEditCategory()
    // {
    //     $this->session();
    //     $this->chklogin();
    //     $template = "admin/editcategory.html.php";
    //     if (isset($this->post['submit'])) {
    //         $criteria = [
    //             'name' => $this->post['name'],

    //         ];
    //         if (isset($this->post['id']) && !empty($this->post['id'])) {
    //             $criteria['id'] = $this->post['id'];
    //             $this->dbCat->update($criteria);
    //         } else {
    //             $this->dbCat->insert($criteria);
    //         }
    //         header('Location: categories');
    //     }

    //     if (isset($this->get['id'])) {
    //         $currentCategory = $this->dbCat->find("id", $this->get['id'])[0];
    //     } else {
    //         $currentCategory = false;
    //     }
    //     return ['template' => $template, 'title' => 'editcategories', 'variables' => ["currentCategory" => $currentCategory]];
    // }
    // public function deletecategory()
    // {
    //     $this->session();
    //     $this->chklogin();
    //     $category = $this->dbCat->delete("id", $this->post['id']);
    //     header('location: categories');
    // }

    // public function addjob()
    // {
    //     $this->session();
    //     $this->chklogin();
    //     $template = "admin/addjob.html.php";
    //     $userId = NULL;
    //     if ($_SESSION['userDetails']['usertype'] == 'CLIENT') {
    //         $userId = $_SESSION['userDetails']['id'];
    //     }

    //     if (isset($this->post['submit'])) {
    //         $criteria = [
    //             'title' => $this->post['title'],
    //             'description' => $this->post['description'],
    //             'salary' => $this->post['salary'],
    //             'location' => $this->post['location'],
    //             'categoryId' => $this->post['categoryId'],
    //             'closingDate' => $this->post['closingDate'],
    //             'userId' => $userId
    //         ];

    //         $job = $this->dbJobs->insert($criteria);

    //         header("Location: jobs");
    //     }
    //     $categories = $this->dbCat->findAll();
    //     return [
    //         'template' => $template, 'title' => 'addjob',
    //         'variables' => ["categories" => $categories]
    //     ];
    // }
    // public function editjob()
    // {
    //     $this->session();
    //     $this->chklogin();
    //     $template = "admin/editjob.html.php";
    //     $categories = $this->dbCat->findAll();

    //     $job = $this->dbJobs->find("id", $this->get['id'])[0];
    //     if (isset($this->post['submit'])) {
    //         $criteria = [
    //             'id' => $this->post['id'],
    //             'title' => $this->post['title'],
    //             'description' => $this->post['description'],
    //             'salary' => $this->post['salary'],
    //             'location' => $this->post['location'],
    //             'categoryId' => $this->post['categoryId'],
    //             'closingDate' => $this->post['closingDate'],
    //         ];

    //         $this->dbJobs->update($criteria);
    //         header("Location: jobs");
    //     }
    //     return [
    //         'template' => $template, 'title' => 'editjob',
    //         'variables' => ["job" => $job, "categories" => $categories]
    //     ];
    // }

    // public function deletejob()
    // {
    //     $this->session();
    //     $this->chklogin();
    //     $criteria = [
    //         'archive' => 1,
    //         'id' => $this->post['id']
    //     ];
    //     $job = $this->dbJobs->update($criteria);
    //     header("Location: jobs");
    // }
    // public function repostjob()
    // {
    //     $this->session();
    //     $this->chklogin();
    //     $criteria = [
    //         'archive' => null,
    //         'id' => $this->post['id']
    //     ];
    //     $job = $this->dbJobs->update($criteria);
    //     header("Location: jobs");
    // }
    // public function enquiry()
    // {
    //     $this->session();
    //     $this->chklogin();

    //     $statement = 'SELECT c.*, a.fullname FROM contact
    //     c LEFT JOIN admin a ON a.id = c.adminId';

    //     $contacts = $this->dbContact->customFind($statement, []);
    //     return [
    //         'template' => 'admin/enquire.html.php', 'title' => 'Enquiries',
    //         'variables' => ["contacts" => $contacts]
    //     ];
    // }
    // public function updateEnquiry()
    // {
    //     $this->session();
    //     $this->chklogin();

    //     $values = [

    //         'id' => $this->post['id'],
    //         'adminId' => $_SESSION['userDetails']['id']
    //     ];

    //     $statement = 'SELECT c.*, a.fullname FROM contact c 
    //                     LEFT JOIN admin a ON a.id = c.adminId';
    //     $action = $this->dbContact->update($values);

    //     $contacts = $this->dbContact->customFind($statement, []);

    //     return [
    //         'template' => 'admin/enquire.html.php', 'title' => 'Enquiries',
    //         'variables' => ["contacts" => $contacts]
    //     ];
    // }

    public function users()
    {
        $this->session();
        $this->chklogin();

        $users = $this->dbUsers->findAll();

        return [
            'template' => 'admin/user.html.php', 'title' => 'Users',
            'variables' => ["users" => $users]
        ];
    }
    public function deleteuser()
    {
        $this->session();
        $this->chklogin();
        $user = $this->dbUsers->delete("id", $this->post['id']);
        header('location: users');
        //            exit();
    }


    /**
     * @return void
     */
    public function session()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public function chklogin(): void
    {

        if (!isset($_SESSION['loggedin'])) {
            header("Location: login");
            exit();
        }
    }
}
