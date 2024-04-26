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
function validateAndSubmit(event) {
    var locationInput = document.getElementById("new_location");
    if (locationInput.value.trim() === "") {
        alert("Location name is required.");
        event.preventDefault(); // Prevent form submission
    }
}
document.addEventListener("DOMContentLoaded", function() {
    // Add event listeners to delete buttons
    var deleteButtons = document.querySelectorAll(".delete-button");
    deleteButtons.forEach(function(button) {
        button.addEventListener("click", function(event) {
            var locationId = this.getAttribute('data-location-id');
            if (confirm("Are you sure you want to delete this location?")) {
                deleteLocation(locationId);
            }
        });
    });
});

function deleteLocation(locationId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "delete_location.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Location deleted successfully, remove it from the DOM
                var locationElement = document.getElementById("location" + locationId);
                if (locationElement) {
                    locationElement.remove();
                }
                alert(xhr.responseText);
            } else {
                alert("Error: " + xhr.statusText);
            }
        }
    };
    xhr.send("location_id=" + encodeURIComponent(locationId));
}
