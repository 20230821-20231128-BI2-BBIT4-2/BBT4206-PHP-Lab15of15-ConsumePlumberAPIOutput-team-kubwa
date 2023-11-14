<?php
# *****************************************************************************
# Lab 14: Consume data from the Plumber API Output (using PHP) ----
#
# Course Code: BBT4206
# Course Name: Business Intelligence II
# Semester Duration: 21st August 2023 to 28th November 2023
#
# Lecturer: Allan Omondi
# Contact: aomondi [at] strathmore.edu
#
# Note: The lecture contains both theory and practice. This file forms part of
#       the practice. It has required lab work submissions that are graded for
#       coursework marks.
#
# License: GNU GPL-3.0-or-later
# See LICENSE file for licensing information.
# *****************************************************************************

// Full documentation of the client URL (cURL) library: https://www.php.net/manual/en/book.curl.php

$apiUrl = 'http://127.0.0.1:5023/Status';
$curl = curl_init();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form data is set
    if (
        isset($_POST['Gender']) && isset($_POST['Married']) && isset($_POST['Dependents']) &&
        isset($_POST['Education']) && isset($_POST['SelfEmployed']) && isset($_POST['ApplicantIncome']) &&
        isset($_POST['CoapplicantIncome']) && isset($_POST['LoanAmount']) && isset($_POST['LoanAmountTerm']) &&
        isset($_POST['CreditHistory']) && isset($_POST['PropertyArea'])
    ) {
        // Check if the form values are numeric
        if (
            is_numeric($_POST['Dependents']) && is_numeric($_POST['ApplicantIncome']) &&
            is_numeric($_POST['CoapplicantIncome']) && is_numeric($_POST['LoanAmount']) &&
            is_numeric($_POST['LoanAmountTerm']) && is_numeric($_POST['CreditHistory']) &&
            is_numeric($_POST['PropertyArea'])
        ) {
            $formData = array(
                'arg_Gender' => $_POST['Gender'],
                'arg_Married' => $_POST['Married'],
                'arg_Dependents' => $_POST['Dependents'],
                'arg_Education' => $_POST['Education'],
                'arg_SelfEmployed' => $_POST['SelfEmployed'],
                'arg_ApplicantIncome' => $_POST['ApplicantIncome'],
                'arg_CoapplicantIncome' => $_POST['CoapplicantIncome'],
                'arg_LoanAmount' => $_POST['LoanAmount'],
                'arg_LoanAmountTerm' => $_POST['LoanAmountTerm'],
                'arg_CreditHistory' => $_POST['CreditHistory'],
                'arg_PropertyArea' => $_POST['PropertyArea'],
            );

            // Set cURL options
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $formData);

            // Build the query string here
            $apiUrl = $apiUrl . '?' . http_build_query($formData);

            curl_setopt($curl, CURLOPT_URL, $apiUrl);

            // For testing:
            echo "The generated URL sent to the API is:<br>" . $apiUrl . "<br><br>";

            // Make a POST request
            $response = curl_exec($curl);

            // Check for cURL errors
            if (curl_errno($curl)) {
                $error = curl_error($curl);
                // Handle the error appropriately
                die("cURL Error: $error");
            }

            // Process the response
            $data = json_decode($response, true);

            // Check if the response was successful
            if (isset($data['0'])) {
                // API request was successful
                // Access the data returned by the API
                echo "The predicted loan status is:<br>";

                // Process the data
                foreach ($data as $repository) {
                    echo $repository['0'], $repository['1'], $repository['2'], "<br>";
                }
            } else {
                // API request failed or returned an error
                // Handle the error appropriately
                echo "API Error: " . $data['message'];
            }
        } else {
            echo "Form values must be numeric.";
        }
    } else {
        echo "All form fields are required.";
    }
}

// Close cURL session/resource
curl_close($curl);
?>



<!DOCTYPE html>
<html>

<head>
    <title>POST Body</title>
    <style>
        form {
            margin: 30px 0px;
        }

        input {
            display: block;
            margin: 10px 15px;
            padding: 8px 10px;
            font-size: 16px;
        }

        div {
            font-size: 20px;
            margin: 0px 15px;
        }

        h2 {
            color: green;
            margin: 20px 15px;
        }
    </style>
</head>

<body>
    <h2>Loan Appraisal prediction</h2>
    <form method="post">
        <input type="text" name="Gender" placeholder="Enter client Gender">
        <input type="text" name="Married" placeholder="Marital Status(Yes or No)">
        <input type="text" name="Dependents" placeholder="Dependents 0,1,2,3+">
        <input type="text" name="Education" placeholder="Graduate or Not Graduate">
        <input type="Text" name="SelfEmployed" placeholder=" Self Employed (Yes or No)">
        <input type="number" name="ApplicantIncome" placeholder="Enter monthly Income">
        <input type="number" name="CoapplicantIncome" placeholder="Coapplicant monthly Income if applicable">
        <input type="number" name="LoanAmount" placeholder="Enter Loan amount">
        <input type="number" name="LoanAmountTerm" placeholder="Enter loan Term in days">
        <input type="number" name="CreditHistory" placeholder="0-350 and below, 1-350 to 500">
        <input type="text" name="PropertyArea" placeholder="Urban, SemiUrban, Rural">
        <input type="submit" name="submit-btn" value="submit">
    </form>
    <br>
</body>

</html>

