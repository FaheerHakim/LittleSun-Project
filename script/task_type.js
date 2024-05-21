function confirmDelete(taskTypeId) {
    window.location.href = `confirm_delete_tasktype.php?delete_task_type=${taskTypeId}`;
}

var taskTypeIdToEdit = null;

function confirmEdit(taskTypeId) {
    taskTypeIdToEdit = taskTypeId;
    document.getElementById('editConfirmationModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editConfirmationModal').style.display = 'none';
    taskTypeIdToEdit = null;
}

function performEdit() {
    if (taskTypeIdToEdit !== null) {
        editTaskType(taskTypeIdToEdit);
        closeEditModal();
    }
}

function editTaskType(taskTypeId) {
 
    var updatedTaskTypeName = document.getElementById('task_type_' + taskTypeId).value;

 
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add_task_types.php", true); 
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
           
            console.log(xhr.responseText);
        }
    };
    xhr.send("update_task_type=1&task_type_id=" + taskTypeId + "&task_type_name=" + encodeURIComponent(updatedTaskTypeName));
}