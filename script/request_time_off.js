window.onload = function() {
    var reasonSelect = document.getElementById('reason');
    var additionalNotes = document.getElementById('additional_notes');

    function toggleAdditionalNotes() {
        additionalNotes.required = (reasonSelect.value === 'Other');
        additionalNotes.disabled = (reasonSelect.value !== 'Other');
    }

    reasonSelect.addEventListener('change', toggleAdditionalNotes);


    toggleAdditionalNotes();
};