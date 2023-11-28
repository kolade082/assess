<?php

namespace CSY2038\Controllers;

use CSY\DatabaseTable;
use CSY\MyPDO;

class AdminController
{
    private $dbUsers;
    private $dbPat;

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
        }else {
            // Decode the JSON response into a PHP array
            $jsonArray = json_decode($response, true);

            $nhsData = array();
            $nhsData['id'] = $jsonArray['id'];
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
                        $nhsData['firstname'] = implode(' ', $name['given']);
                        $nhsData['lastname'] = $name['family'];
                    }
                }
                if (isset($jsonArray['birthDate'])) {
                    $nhsData['dob'] = $jsonArray['birthDate'];
                }

                // Check if "telecom" is present in the array
                if (isset($jsonArray['telecom']) && is_array($jsonArray['telecom']) && count($jsonArray['telecom']) > 0) {
                    foreach ($jsonArray['telecom'] as $contact) {
                        // Check if "system" is "phone" or "email" and add to selected data
                        if (isset($contact['system']) && isset($contact['value'])) {
                            if ($contact['system'] == 'phone') {
                                $nhsData['phone'] = $contact['value'];
                            } elseif ($contact['system'] == 'email') {
                                $nhsData['email'] = $contact['value'];
                            }
                        }
                    }
                }
            }

            // Output the response data

            //Detect Anomalies:
            $this->session();
            $this->chklogin();

            $patients = $this->dbPat->findAll();
            $anomalies = [];
            $html = '';
            foreach ($patients as $patient) {

                if ($patient['firstname'] != $nhsData['firstname'] ||
                    $patient['lastname'] != $nhsData['lastname'] ||
                    $patient['dob'] != $nhsData['dob'] ||
                    $patient['phone_number'] != $nhsData['phone'] ||
                    $patient['email'] != $nhsData['email']) {

                    $anomalies[] = [
                        'patientId' => $patient['id'],
                        'yourData' => $patient,
                        'nhsData' => $nhsData
                    ];

                    $html .= '<div class="anomaly-item">
                    <div class="anomaly-description">
                        <p>Anomaly Detected: Discrepancy in patient record for Patient ID ' . $patient['id'] . '</p>
                    </div>
                    <div class="view-details">
                        <button onclick="viewDetails(this)"
                                data-your-data="' . htmlspecialchars(json_encode($patient), ENT_QUOTES) . '"
                                data-nhs-data="' . htmlspecialchars(json_encode($nhsData), ENT_QUOTES) . '">
                            View Details
                        </button>
                    </div>
                </div>';

                }
            }

        }

        return [
            'template' => 'admin/dashboard.html.php',
            'title' => 'Dashboard',
            'variables' => ['html' => $html
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
