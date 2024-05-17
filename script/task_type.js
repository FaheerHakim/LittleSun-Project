function confirmDelete(taskTypeId) {
    if (confirm("Are you sure you want to delete this task type?")) {
        document.getElementById("delete_form_" + taskTypeId).submit();
    }
}

