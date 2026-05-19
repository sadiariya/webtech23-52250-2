// public/js/housekeeping.js

// Auto-refresh dashboard stats
function refreshDashboardStats() {
    $.ajax({
        url: "index.php?controller=housekeeping&action=getDashboardStats",
        method: "GET",
        dataType: "json",
        success: function(data) {
            $(".card-stats .h2").eq(0).text(data.dirty_rooms);
            $(".card-stats .h2").eq(1).text(data.cleaning_rooms);
            $(".card-stats .h2").eq(2).text(data.pending_tasks);
            $(".card-stats .h2").eq(3).text(data.open_maintenance);
        }
    });
}

// Update room status via AJAX
function updateRoomStatus(roomId, status) {
    $.ajax({
        url: "index.php?controller=housekeeping&action=updateRoomStatus",
        method: "POST",
        data: {room_id: roomId, status: status},
        dataType: "json",
        success: function(response) {
            if(response.success) {
                location.reload();
            } else {
                alert("Failed to update room status");
            }
        }
    });
}

// Auto-refresh if on dashboard
if(window.location.href.indexOf("dashboard") > -1) {
    setInterval(refreshDashboardStats, 30000);
}

// Confirm task completion
function confirmComplete(taskId) {
    if(confirm("Mark this task as completed?")) {
        $.ajax({
            url: "index.php?controller=housekeeping&action=updateTaskStatus",
            method: "POST",
            data: {task_id: taskId, status: "completed"},
            success: function() {
                location.reload();
            }
        });
    }
}