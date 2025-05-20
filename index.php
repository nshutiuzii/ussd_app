<?php
require_once 'config.php';

// Get Africa's Talking USSD POST data
$sessionId = $_POST['sessionId'] ?? '';
$serviceCode = $_POST['serviceCode'] ?? '';
$phoneNumber = $_POST['phoneNumber'] ?? '';
$text = $_POST['text'] ?? '';

// Log session if new
function logSession($pdo, $sessionId, $phoneNumber) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM sessions WHERE session_id = ?");
    $stmt->execute([$sessionId]);
    if ($stmt->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO sessions (session_id, phone_number) VALUES (?, ?)");
        $stmt->execute([$sessionId, $phoneNumber]);
    }
}

// Log user input
function logInput($pdo, $sessionId, $userInput) {
    $stmt = $pdo->prepare("INSERT INTO user_inputs (session_id, user_input) VALUES (?, ?)");
    $stmt->execute([$sessionId, $userInput]);
}

// Process USSD input
function processUSSD($text, $pdo, $sessionId, $phoneNumber) {
    logSession($pdo, $sessionId, $phoneNumber);
    logInput($pdo, $sessionId, $text);

    $response = "";
    $textArray = explode('*', $text);

    if (empty($text)) {
        // Initial menu
        $response = "CON Welcome to USSD App\n1. Register\n2. Check Balance\n3. Exit";
    } else {
        switch ($textArray[0]) {
            case '1':
                if (count($textArray) == 1) {
                    $response = "CON Enter your name:";
                } elseif (count($textArray) == 2) {
                    $response = "CON Enter your email:";
                } else {
                    $response = "END Registration successful! Name: {$textArray[1]}, Email: {$textArray[2]}";
                }
                break;
            case '2':
                $response = "END Your balance is $100.00";
                break;
            case '3':
                $response = "END Goodbye!";
                break;
            default:
                $response = "END Invalid option. Please try again.";
        }
    }

    return $response;
}

// Set response headers
header('Content-type: text/plain');

// Process and output response
echo processUSSD($text, $pdo, $sessionId, $phoneNumber);