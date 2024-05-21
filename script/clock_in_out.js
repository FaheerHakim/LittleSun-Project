$(document).ready(function() {
   
    function updateCurrentTimeAndDate() {
        var currentDate = new Date();
        var currentTimeElement = document.getElementById("currentTime");
        if (currentTimeElement) {
            currentTimeElement.innerHTML = "<b>Current Time</b>: <br>" + currentDate.toLocaleString();
                } else {
            console.error("Element with ID 'currentTime' not found.");
        }
    }

 
    updateCurrentTimeAndDate();

 
    setInterval(updateCurrentTimeAndDate, 1000);


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
