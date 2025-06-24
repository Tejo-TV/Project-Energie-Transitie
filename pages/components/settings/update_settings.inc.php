<?php
session_start();

if (isset($_POST["update_settings"])) {
    if (!isset($_SESSION['user'])) {
        header("Location: ../../login.php?error=notloggedin");
        exit();
    }

    require_once '../../../config/database.inc.php';
    require_once '../login/functions.inc.php';

    $storedUser = unserialize($_SESSION['user']);
    $userID = $storedUser->getId();
    $addressId = $storedUser->getAddressId();

    $name = $_POST['name'];
    $email = $_POST['email'];
    $street = $_POST['street'];
    $number = $_POST['number'];
    $postcode = $_POST['postcode'];
    $city = $_POST['city'];
    $country = $_POST['country'];

    // Update user table
    $sqlUser = "UPDATE user SET name = ?, email = ? WHERE ID = ?;";
    $stmtUser = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmtUser, $sqlUser)) {
        mysqli_stmt_bind_param($stmtUser, "ssi", $name, $email, $userID);
        mysqli_stmt_execute($stmtUser);
        mysqli_stmt_close($stmtUser);
    } else {
        header("Location: ../../../index.php?error=stmtfailed");
        exit();
    }

    // Check if address exists
    if ($addressId != 0) {
        // Update address table
        $sqlAddress = "UPDATE address SET street = ?, number = ?, postcode = ?, city = ?, country = ? WHERE ID = ?;";
        $stmtAddress = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmtAddress, $sqlAddress)) {
            mysqli_stmt_bind_param($stmtAddress, "sssssi", $street, $number, $postcode, $city, $country, $addressId);
            mysqli_stmt_execute($stmtAddress);
            mysqli_stmt_close($stmtAddress);
        } else {
            header("Location: ../../../index.php?error=stmtfailed");
            exit();
        }
    } else {
        // Insert new address
        $sqlInsertAddress = "INSERT INTO address (street, number, postcode, city, country) VALUES (?, ?, ?, ?, ?);";
        $stmtInsertAddress = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmtInsertAddress, $sqlInsertAddress)) {
            mysqli_stmt_bind_param($stmtInsertAddress, "sssss", $street, $number, $postcode, $city, $country);
            mysqli_stmt_execute($stmtInsertAddress);
            $newAddressId = mysqli_insert_id($conn);
            mysqli_stmt_close($stmtInsertAddress);

            // Update user with new address ID
            $sqlUpdateUserAddress = "UPDATE user SET address_id = ? WHERE ID = ?;";
            $stmtUpdateUserAddress = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmtUpdateUserAddress, $sqlUpdateUserAddress)) {
                mysqli_stmt_bind_param($stmtUpdateUserAddress, "ii", $newAddressId, $userID);
                mysqli_stmt_execute($stmtUpdateUserAddress);
                mysqli_stmt_close($stmtUpdateUserAddress);
            } else {
                header("Location: ../../../index.php?error=stmtfailed");
                exit();
            }
            $addressId = $newAddressId;
        } else {
            header("Location: ../../../index.php?error=stmtfailed");
            exit();
        }
    }
    
    // Update session data
    $updatedUser = new User($userID, $name, $email, $addressId, $storedUser->getRoleId());
    $_SESSION['user'] = serialize($updatedUser);


    header("Location: ../../../index.php?success=settingsupdated");
    exit();

} else {
    header("Location: ../../../index.php");
    exit();
} 