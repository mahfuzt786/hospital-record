<?php

session_start();
require_once 'constants.php';
require_once 'database-config.php'; /* Database Settings */
require_once 'util.php';


/*  When a user click the login button */
$action = $_REQUEST['action']; //Calling program must pass action

if ($action == "confirmLogin") {
    confirmLogin($mysqli);
} else {
    throw new Exception("Unknown Action: " + $action);
}

//if (isset($_POST['frmLogin']))
function confirmLogin($mysqli) {
    $error = 'false';
    $erroremail = '';

    //$emailId= trim(stripslashes($_POST['email']));
    //$pass= trim(stripslashes($_POST['password']));

    $emailId = getRequestPostDefault('email', 'null');
    $pass = getRequestPostDefault('password', 'null');

    if (isValidIdPassword($emailId, $pass, $mysqli)) {
        echo 'done';
    } else {
        echo 'Invalid username or password.' . "<br/>" .
        'If you do not know the password, Contact us at customerservice@wtf.ind.in';
    }
}

function isValidIdPassword($loginId, $password, $mysqli) {

    if (!filter_var($loginId, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $sql = "SELECT user.*
					FROM wtfindin_hms.user
				   WHERE loginId='$loginId'
					 AND password='$password'";
    $arRes = $mysqli->query($sql);
    if (!$arRes) {
        throw new Exception($mysqli->error);
    }

    if (mysqli_num_rows($arRes) == 0) {
        return false;
    } else {
        $row = $arRes->fetch_object();
        //$_SESSION[SESS_USER_TYPE] = $row->userType;

        /* if ($row->userType == 'admin') {

          $_SESSION[SESS_LOGIN_ID] = $row->userId;
          $_SESSION[SESS_LOGIN_NAME] = $row->firstName . ' ' . $row->lastName;
          $agentId = '1';
          $_SESSION[SESS_ADMIN_ID] = $agentId;
          $_SESSION[SESS_ADMIN_NAME] = 'WTF Admin';
          } else { */

        $_SESSION[SESS_LOGIN_ID] = $row->userId;
        $_SESSION[SESS_LOGIN_NAME] = $row->first_name . ' ' . $row->last_name;
        //$agentId = $row->userId;
        //$_SESSION[SESS_ADMIN_ID] = $agentId;
        //$_SESSION[SESS_ADMIN_NAME] = $row->firstName . ' ' . $row->lastName;
        //}

        /**
         * Track user login date/time & ip address
         */
        $remote_addr = $_SERVER['REMOTE_ADDR'];
        $sql = "INSERT  
						INTO wtfindin_hms.userlogin 
							 (userId,remote_addr)
					   VALUE (" . $_SESSION[SESS_LOGIN_ID] . ",'" . $remote_addr . "')";
        $arRes = $mysqli->query($sql);
        if (!$arRes) {
            throw new Exception($mysqli->error);
        }

        return true;
    }
}

?>