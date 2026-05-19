<?php
// public/index.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/HousekeepingController.php';
require_once __DIR__ . '/../controllers/AjaxController.php';

// Get controller and action from URL
$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

// Route to appropriate controller
switch($controller) {
    case 'auth':
        $authController = new AuthController();
        if ($action == 'login') {
            $authController->login();
        } elseif ($action == 'profile') {
            $authController->profile();
        } elseif ($action == 'logout') {
            $authController->logout();
        } else {
            $authController->login();
        }
        break;
        
    case 'housekeeping':
        $housekeepingController = new HousekeepingController();
        if ($action == 'dashboard') {
            $housekeepingController->dashboard();
        } elseif ($action == 'roomStatus') {
            $housekeepingController->roomStatus();
        } elseif ($action == 'createTask') {
            $housekeepingController->createTask();
        } elseif ($action == 'manageTasks') {
            $housekeepingController->manageTasks();
        } elseif ($action == 'assignTask') {
            $housekeepingController->assignTask();
        } elseif ($action == 'maintenance') {
            $housekeepingController->maintenance();
        } elseif ($action == 'checkouts') {
            $housekeepingController->checkouts();
        } elseif ($action == 'checkins') {
            $housekeepingController->checkins();
        } elseif ($action == 'report') {
            $housekeepingController->report();
        } elseif ($action == 'updateRoomStatus') {
            $housekeepingController->updateRoomStatus();
        } elseif ($action == 'getDashboardStats') {
            $housekeepingController->getDashboardStats();
        } else {
            $housekeepingController->dashboard();
        }
        break;
        
    case 'ajax':
        $ajaxController = new AjaxController();
        if ($action == 'getRoomStatus') {
            $ajaxController->getRoomStatus();
        } elseif ($action == 'updateRoomStatus') {
            $ajaxController->updateRoomStatus();
        } elseif ($action == 'getTaskDetails') {
            $ajaxController->getTaskDetails();
        } elseif ($action == 'getMaintenanceDetails') {
            $ajaxController->getMaintenanceDetails();
        } elseif ($action == 'getDashboardStats') {
            $ajaxController->getDashboardStats();
        } else {
            $ajaxController->getDashboardStats();
        }
        break;
        
    default:
        header("Location: index.php?controller=auth&action=login");
        exit();
}
?>