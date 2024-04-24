function displayNewLocation(locationName) {
    var newLocationContainer = document.getElementById("newLocationContainer");
    
    // Create a new input element
    var newLocationInput = document.createElement("input");
    newLocationInput.setAttribute("type", "text");
    newLocationInput.setAttribute("name", "new_location");
    newLocationInput.setAttribute("value", locationName);
    newLocationInput.setAttribute("readonly", "true"); // Assuming you want to make it readonly
    
    // Create edit and delete buttons
    var editButton = document.createElement("button");
    editButton.textContent = "Edit location";
    editButton.classList.add("edit-button");
    
    var deleteButton = document.createElement("button");
    deleteButton.textContent = "Delete location";
    deleteButton.classList.add("delete-button");
    
    // Append the input and buttons to the container
    newLocationContainer.appendChild(newLocationInput);
    newLocationContainer.appendChild(editButton);
    newLocationContainer.appendChild(deleteButton);
}
function deleteLocation(button) {
    var locationId = button.getAttribute("data-location-id");
    var confirmation = confirm("Are you sure you want to delete this location?");
    if (confirmation) {
        // Send AJAX request to delete location
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Remove the deleted location from the UI
                    var locationDiv = button.parentElement;
                    locationDiv.parentNode.removeChild(locationDiv);
                    alert("Location deleted successfully.");
                } else {
                    alert("Error deleting location.");
                }
            }
        };
        xhr.open("POST", "add_location.php");
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("delete_location=" + locationId);
    }
}