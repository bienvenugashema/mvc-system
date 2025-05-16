<?php

function initMessages() {
    if (!isset($_SESSION['messages'])) {
        $_SESSION['messages'] = [
            'errors' => [],
            'success' => [],
            'info' => [],
            'warnings' => []
        ];
    }
}

function addError($message) {
    initMessages();
    $_SESSION['messages']['errors'][] = $message;
}


function addSuccess($message) {
    initMessages();
    $_SESSION['messages']['success'][] = $message;
}


function addInfo($message) {
    initMessages();
    $_SESSION['messages']['info'][] = $message;
}


function addWarning($message) {
    initMessages();
    $_SESSION['messages']['warnings'][] = $message;
}


function displayMessages() {
    initMessages();
    
    $alertTypes = [
        'errors' => 'danger',
        'success' => 'success',
        'info' => 'info',
        'warnings' => 'warning'
    ];
    
    foreach ($alertTypes as $messageType => $bootstrapClass) {
        if (!empty($_SESSION['messages'][$messageType])) {
            echo '<div class="alert alert-'.$bootstrapClass.' alert-dismissible fade show mb-4">';
            echo '<div class="d-flex align-items-center">';
            echo '<div class="flex-grow-1">';
            
            foreach ($_SESSION['messages'][$messageType] as $message) {
                echo '<p>'.$message.'</p>';
            }
            
            echo '</div>';
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
            echo '</div>';
        }
    }
    
    $_SESSION['messages'] = [
        'errors' => [],
        'success' => [],
        'info' => [],
        'warnings' => []
    ];
}


function hasMessages($type = null) {
    initMessages();
    
    if ($type) {
        return !empty($_SESSION['messages'][$type]);
    }
    
    foreach ($_SESSION['messages'] as $messages) {
        if (!empty($messages)) {
            return true;
        }
    }
    
    return false;
}