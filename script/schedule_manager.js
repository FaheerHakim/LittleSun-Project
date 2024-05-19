document.addEventListener('DOMContentLoaded', function() {
    const locationDropdown = document.getElementById('location');
    const allEvents = document.querySelectorAll('.event');

    locationDropdown.addEventListener('change', function() {
        const selectedLocation = this.value;
        filterEvents(selectedLocation);
    });

    function filterEvents(selectedLocation) {
        allEvents.forEach(event => {
            const eventLocationId = event.dataset.locationId;
            if (selectedLocation === 'all' || eventLocationId === selectedLocation) {
                event.style.display = 'block';
            } else {
                event.style.display = 'none';
            }
        });
    }
});