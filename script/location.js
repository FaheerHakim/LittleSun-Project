function confirmDelete(locationId) {
    const form = document.getElementById(`delete_location_${locationId}`);
    form.submit();
}

var locationIdToEdit = null;

function confirmEdit(locationId) {
    locationIdToEdit = locationId;
    document.getElementById('editConfirmationModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editConfirmationModal').style.display = 'none';
    locationIdToEdit = null;
}

function performEdit() {
    if (locationIdToEdit !== null) {
        editLocation(locationIdToEdit);
        closeEditModal();
    }
}

function editLocation(locationId) {
 
    var updatedLocationName = document.getElementById('locationInput_' + locationId).value;


    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add_location.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
        
            console.log(xhr.responseText);
        }
    };
    xhr.send("update_location=1&location_id=" + locationId + "&location_name=" + encodeURIComponent(updatedLocationName));
}