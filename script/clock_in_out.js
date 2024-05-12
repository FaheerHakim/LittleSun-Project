$(document).ready(function() {
    // Function to update the current time and date
    function updateCurrentTimeAndDate() {
        var currentDate = new Date();
        var currentTimeElement = document.getElementById("currentTime");
        if (currentTimeElement) {
            currentTimeElement.textContent = "Current Time: " + currentDate.toLocaleString();
        } else {
            console.error("Element with ID 'currentTime' not found.");
        }
    }

    // Call the function to update the current time and date initially
    updateCurrentTimeAndDate();

    // Update the current time and date every second
    setInterval(updateCurrentTimeAndDate, 1000);

    // AJAX request for clocking in
    $("#clockInBtn").click(function() {
        $.ajax({
            url: "clock_in_out.php",
            type: "POST",
            data: { action: "clockIn" },
            success: function(response) {
                alert(response);
            }
        });       
    });

    // AJAX request for clocking out
    $("#clockOutBtn").click(function() {
        $.ajax({
            url: "clock_in_out.php",
            type: "POST",
            data: { action: "clockOut" },
            success: function(response) {
                alert(response);
            }
        });
    });
});
