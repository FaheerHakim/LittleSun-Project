function confirmDelete(event, taskTypeId) {
    event.stopPropagation();
    var form = document.getElementById('delete_task_type_' + taskTypeId);
    if (form) {
        if (confirm("Are you sure you want to delete this task type?")) {
            form.submit();
        }
    } else {
        console.error("Form with ID delete_task_type_" + taskTypeId + " not found.");
    }
}
function editTaskType(taskTypeId) {
    // Get the updated task type name
    var updatedTaskTypeName = document.getElementById('task_type_' + taskTypeId).value;

    // Send the updated task type name and ID to the server
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add_task_types.php", true); // Assuming your PHP script is add_task_types.php
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Handle the response from the server if needed
            console.log(xhr.responseText);
        }
    };
    xhr.send("update_task_type=1&task_type_id=" + taskTypeId + "&task_type_name=" + encodeURIComponent(updatedTaskTypeName));
}