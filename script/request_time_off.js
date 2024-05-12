window.onload = function() {
    var reasonSelect = document.getElementById('reason');
    var additionalNotes = document.getElementById('additional_notes');

    // Function to toggle additional notes field based on selected reason
    function toggleAdditionalNotes() {
        additionalNotes.required = (reasonSelect.value === 'Other');
        additionalNotes.disabled = (reasonSelect.value !== 'Other');
    }

    // Add event listener to the reason select element
    reasonSelect.addEventListener('change', toggleAdditionalNotes);

    // Initial toggle based on the default selected value
    toggleAdditionalNotes();
};