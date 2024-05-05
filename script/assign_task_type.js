function searchUsers() {
    var input = document.getElementById('searchBar');
    var filter = input.value.toLowerCase();
    var userBoxes = document.querySelectorAll('.user-box');

    userBoxes.forEach(function(box) {
        var userName = box.querySelector('.user-box p').textContent.toLowerCase();
        if (userName.includes(filter)) {
            box.style.display = 'block';
        } else {
            box.style.display = 'none';
        }
    });
}

function confirmAssignment() {
    return confirm("Are you sure you want to assign this task type to the selected user?");
}