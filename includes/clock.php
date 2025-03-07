<div id="clock"></div>
<style>
  #clock {
    position: fixed;
    top: 10px;
    right: 10px;
    background-color: white;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    z-index: 1050; /* Ensure the clock is above the navbar */
  }
</style>
<script>
  function updateClock() {
    var now = new Date();
    var hours = now.getHours().toString().padStart(2, '0');
    var minutes = now.getMinutes().toString().padStart(2, '0');
    var seconds = now.getSeconds().toString().padStart(2, '0');
    document.getElementById('clock').textContent = hours + ':' + minutes + ':' + seconds;
  }
  setInterval(updateClock, 1000);
  updateClock(); // initial call
</script>
