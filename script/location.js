function confirmDelete(locationId) {
    const form = document.getElementById(`delete_location_${locationId}`);
    form.submit();
}

function editLocation(locationId) {
    // Get the updated location name
    var updatedLocationName = document.getElementById('locationInput_' + locationId).value;

    // Send the updated location name and ID to the server
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add_location.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Handle the response from the server if needed
            console.log(xhr.responseText);
        }
    };
    xhr.send("update_location=1&location_id=" + locationId + "&location_name=" + encodeURIComponent(updatedLocationName));
}