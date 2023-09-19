<?php

namespace App\Controllers;

use Exception;
use \Core\{View};
use App\Helpers\{Functions};
use App\Models\{Select, MenuTree, Registration, LoginModel, Update};

class Send extends \Core\Controller
{

    public function sendEmail($request): void
    {
        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve form data

            $name = $request->name;
            $surname = $request->surname;

            $errors = [];

            if (empty($name)) {
                $errors['name'] = 'Please enter your name';
            }

            if (empty($surname)) {
                $errors['surname'] = 'Please enter your surname';
            }

            if (count($errors) > 0) {
                echo json_encode($errors);
            }

            // Set up email parameters
            $to = 'wk.kondraciuk@gmail.com'; // Replace with the recipient's email address
            $subject = 'New Contact Form Submission';
            $body = "Name: $name\nSurname: $surname";
            $headers = 'From: nefren.games@gmail.com'; // Replace with the sender's email address

            try {
                mail($to, $subject, $body, $headers);
            } catch (Exception $e) {
                echo 'Failed to send email. Error: ' . $e->getMessage();
            }
        }
    }
}
