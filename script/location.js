function confirmDelete(event, locationId) {
    event.stopPropagation();
    var form = document.getElementById('delete_location_' + locationId);
    if (form) {
        if (confirm("Are you sure you want to delete this location?")) {
            form.submit();
        }
    } else {
        console.error("Form with ID delete_location_" + locationId + " not found.");
    }
}
