<?php
// public/api/ajax-handler.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Room.php';
require_once __DIR__ . '/../../models/HousekeepingTask.php';
require_once __DIR__ . '/../../models/MaintenanceReport.php';
require_once __DIR__ . '/../../models/Booking.php';
require_once __DIR__ . '/../../models/User.php';

session_start();

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch($action) {
    case 'getRoomStatus':
        $roomModel = new Room();
        $rooms = $roomModel->getAllRooms();
        echo json_encode(['success' => true, 'data' => $rooms]);
        break;
        
    case 'getRoomStats':
        $roomModel = new Room();
        $stats = $roomModel->getRoomStats();
        echo json_encode(['success' => true, 'data' => $stats]);
        break;
        
    case 'getTodayTasks':
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            break;
        }
        $taskModel = new HousekeepingTask();
        $tasks = $taskModel->getTodayTasks();
        echo json_encode(['success' => true, 'data' => $tasks]);
        break;
        
    case 'getPendingTasks':
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            break;
        }
        $taskModel = new HousekeepingTask();
        $tasks = $taskModel->getPendingTasks();
        echo json_encode(['success' => true, 'data' => $tasks]);
        break;
        
    case 'getOpenMaintenance':
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            break;
        }
        $maintenanceModel = new MaintenanceReport();
        $reports = $maintenanceModel->getOpenReports();
        echo json_encode(['success' => true, 'data' => $reports]);
        break;
        
    case 'updateTaskStatus':
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            break;
        }
        $task_id = $_POST['task_id'] ?? 0;
        $status = $_POST['status'] ?? '';
        if (!$task_id || !$status) {
            echo json_encode(['success' => false, 'error' => 'Missing fields']);
            break;
        }
        $taskModel = new HousekeepingTask();
        $result = $taskModel->updateTaskStatus($task_id, $status);
        echo json_encode(['success' => $result]);
        break;
        
    case 'updateMaintenanceStatus':
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            break;
        }
        $report_id = $_POST['report_id'] ?? 0;
        $status = $_POST['status'] ?? '';
        if (!$report_id || !$status) {
            echo json_encode(['success' => false, 'error' => 'Missing fields']);
            break;
        }
        $maintenanceModel = new MaintenanceReport();
        $result = $maintenanceModel->updateReportStatus($report_id, $status);
        echo json_encode(['success' => $result]);
        break;
        
    case 'createTask':
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            break;
        }
        $room_id = $_POST['room_id'] ?? 0;
        $task_type = $_POST['task_type'] ?? '';
        $priority = $_POST['priority'] ?? 'normal';
        $scheduled_date = $_POST['scheduled_date'] ?? date('Y-m-d');
        $notes = $_POST['notes'] ?? '';
        if (!$room_id || !$task_type) {
            echo json_encode(['success' => false, 'error' => 'Missing fields']);
            break;
        }
        $taskModel = new HousekeepingTask();
        $result = $taskModel->createTask($room_id, $task_type, $priority, $scheduled_date, $notes);
        echo json_encode(['success' => $result]);
        break;
        
    case 'getDashboardStats':
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            break;
        }
        $roomModel = new Room();
        $taskModel = new HousekeepingTask();
        $maintenanceModel = new MaintenanceReport();
        $room_stats = $roomModel->getRoomStats();
        $task_stats = $taskModel->getTaskStats();
        $maintenance_stats = $maintenanceModel->getMaintenanceStats();
        echo json_encode([
            'success' => true,
            'data' => [
                'dirty_rooms' => $room_stats['dirty'] ?? 0,
                'cleaning_rooms' => $room_stats['cleaning'] ?? 0,
                'pending_tasks' => $task_stats['pending'] ?? 0,
                'completed_today' => $task_stats['completed_today'] ?? 0,
                'open_maintenance' => $maintenance_stats['open'] ?? 0
            ]
        ]);
        break;
        
    case 'getStaffList':
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            break;
        }
        $userModel = new User();
        $staff = $userModel->getStaffList();
        echo json_encode(['success' => true, 'data' => $staff]);
        break;
        
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
        break;
}
?>