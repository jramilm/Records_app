<?php
require 'vendor/autoload.php';
require('config/config.php');
require('config/db.php');
global $conn;

function addFakeEmployeesRow(int $limit, $conn) : void {
    global $f;

    for($i = 0; $i < $limit; $i++) {
        $lastName = $f -> lastName;
        $firstName = $f -> firstName;
        $officeID = $f -> numberBetween(1, 50);
        $address = $f -> address;

        $stmt = $conn -> prepare("INSERT INTO employee (lastname, firstname, office_id, address) VALUES (?, ?, ?, ?)");
        $stmt -> bind_param("ssis", $lastName, $firstName, $officeID, $address);

        if ($stmt -> execute()) {
            echo "New record created successfully";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
        echo "\n";
    }
}

function addFakeOfficeRow(int $limit, $conn) : void {
    global $f;

    for($i = 0; $i < $limit; $i++) {
        $name = $f -> company;
        $contactNum = $f -> phoneNumber;
        $email = $f -> companyEmail;
        $address = $f -> address;
        $city = $f -> city;
        $country = $f -> country;
        $postal = $f -> postcode;

        $stmt = $conn -> prepare("INSERT INTO office (name, contactnum, email, address, city, country, postal) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt -> bind_param("sssssss", $name, $contactNum, $email, $address, $city, $country, $postal);

        if ($stmt -> execute()) {
            echo "New record created successfully";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
        echo "\n";
    }
}

function addFakeTransactionRow(int $limit, $conn) : void {
    global $f;

    for($i = 0; $i < $limit; $i++) {
        $employeeID = $f -> numberBetween(1, 200);
        $officeID = $f -> numberBetween(1, 50);
        $date = $f -> date();
        $action = $f -> randomElement(array('Cash Payment', 'Loan', 'Loan Payment', 'Order Purchase'));
        $remarks = $f -> optional(0.7) -> word;
        $documentCode = $f -> countryCode;

        $stmt = $conn -> prepare("INSERT INTO transaction(employee_id, office_id, datelog, action, remarks, documentcode)
                                  VALUES (?, ?, ?, ?, ?, ?)");
        $stmt -> bind_param("iissss", $employeeID, $officeID, $date, $action, $remarks, $documentCode);
        $stmt -> execute();
    }
    echo "$limit transaction records created successfully.";
}

addFakeOfficeRow(50, $conn);
addFakeEmployeesRow(200, $conn);
addFakeTransactionRow(500, $conn);