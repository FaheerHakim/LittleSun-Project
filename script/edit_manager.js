function searchUsers() {
    var input = document.getElementById('searchBar');
    var filter = input.value.toLowerCase();
    var userBoxes = document.querySelectorAll('.user-box');

    userBoxes.forEach(function(box) {
        var userName = box.querySelector('.user-info h2').textContent.toLowerCase();
        if (userName.includes(filter)) {
            box.style.display = 'block';
        } else {
            box.style.display = 'none';
        }
    });
}