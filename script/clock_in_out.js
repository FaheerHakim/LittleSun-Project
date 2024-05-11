$(document).ready(function() {
    function updateCurrentTimeAndDate() {
        var currentDate = new Date();
        var currentTimeElement = document.getElementById("currentTime");
        if (currentTimeElement) {
            currentTimeElement.textContent = "Current Time: " + currentDate.toLocaleString();
        } else {
            console.error("Element with ID 'currentTime' not found.");
        }
    }

    // Call the function to update the current time and date
    updateCurrentTimeAndDate();

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
