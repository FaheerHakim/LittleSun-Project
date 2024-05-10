let timer = null;
        let startTime = null;

        function formatTime(date) {
            const hours = date.getHours().toString().padStart(2, '0');
            const minutes = date.getMinutes().toString().padStart(2, '0');
            const seconds = date.getSeconds().toString().padStart(2, '0');
            return `${hours}:${minutes}:${seconds}`;
        }

        function formatDate(date) {
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        function updateCurrentTimeAndDate() {
            const now = new Date();
            document.getElementById("currentDateDisplay").textContent = `Date: ${formatDate(now)}`;
           
        }

        setInterval(updateCurrentTimeAndDate, 1000); // Update current time and date every second

        function startTimer() {
            if (timer === null) {
                startTime = new Date(); // Record the start time
                document.getElementById("startTimeDisplay").textContent = `Start Time: ${formatTime(startTime)}`;

                timer = setInterval(() => {
                    const now = new Date();
                    const elapsed = Math.floor((now - startTime) / 1000);
                    const hours = Math.floor(elapsed / 3600).toString().padStart(2, '0');
                    const minutes = Math.floor((elapsed % 3600) / 60).toString().padStart(2, '0');
                    const seconds = (elapsed % 60).toString().padStart(2, '0');
                    document.getElementById("elapsedTimeDisplay").textContent = `Elapsed Time: ${hours}:${minutes}:${seconds}`;
                }, 1000);

                document.getElementById("startButton").disabled = true;
                document.getElementById("stopButton").disabled = false;
            }
        }

        function stopTimer() {
            if (timer !== null) {
                clearInterval(timer);
                timer = null;

                const stopTime = new Date(); // Record the stop time
                const stopTimeText = `Stopped at: ${formatTime(stopTime)}`;
                document.getElementById("elapsedTimeDisplay").textContent += ` (${stopTimeText})`;

                document.getElementById("startButton").disabled = false;
                document.getElementById("stopButton").disabled = true;
            }
        } 