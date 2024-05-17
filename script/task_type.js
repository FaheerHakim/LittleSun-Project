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
