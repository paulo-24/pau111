$(document).ready(function() {
  var startTime, endTime;

  $('#time-in-btn').click(function() {
    var employeeName = $('#employeeSelect').val();
    if (employeeName) {
      openModal(employeeName + ' - Time In');
      startTime = new Date();
      $('#modal-title').text(employeeName + ' - Time In (' + getCurrentTime() + ')');
    } else {
      alert('Please select an employee first.');
    }
  });

  $('#time-out-btn').click(function() {
    var employeeName = $('#employeeSelect').val();
    if (employeeName) {
      openModal(employeeName + ' - Time Out');
      endTime = new Date();
      calculateTotalHours(startTime, endTime);
      $('#modal-title').text(employeeName + ' - Time Out (' + getCurrentTime() + ')');
    } else {
      alert('Please select an employee first.');
    }
  });

  function openModal(title) {
    $('#modal-title').text(title);
    $('.bg-modal').fadeIn(500);
    startCamera();
  }

  function startCamera() {
    const video = document.getElementById('video');
    const cameraButton = document.getElementById('startCamera');

    if (!video.srcObject) {
      navigator.mediaDevices.getUserMedia({ video: true })
        .then(function(stream) {
          video.srcObject = stream;
        })
        .catch(function(error) {
          console.error('Error accessing the camera: ', error);
        });
    }

    cameraButton.style.display = 'none';
  }

  function calculateTotalHours(start, end) {
    var diff = end - start;
    var totalSeconds = Math.floor(diff / 1000);
    var hours = Math.floor(totalSeconds / 3600);
    totalSeconds %= 3600;
    var minutes = Math.floor(totalSeconds / 60);
    var seconds = totalSeconds % 60;

    var totalTime = hours + ':' + minutes + ':' + seconds;
    $('#modal-title').text($('#modal-title').text() + ' (' + totalTime + ')');
  }

  $('.close, .close-modal').click(function() {
    closeModal();
  });

  $('#modal-action-btn').click(function() {
    var action = $('#modal-title').text().includes('Time In') ? 'Time In' : 'Time Out';
    var employeeName = $('#employeeSelect').val();
    var currentTime = $('#clock').text();
    var imgData = captureImage();

    $.ajax({
      url: 'attendance_employee.php',
      type: 'POST',
      data: {
        action: action,
        employeeName: employeeName,
        time: currentTime,
        image: imgData
      },
      success: function(response) {
        alert(response);
        closeModal();
      },
      error: function(xhr, status, error) {
        console.error(status, error);
      }
    });
  });

  // Function to delete the attendance record
function deleteAttendanceRecord(recordId) {
  $.ajax({
      url: 'delete_attendance.php',
      type: 'POST',
      dataType: 'json',
      data: {
          action: 'deleteAttendance',
          id: recordId
      },
      success: function(response) {
          console.log("Record deleted successfully:", response);
          // Fetch updated attendance data after deletion
          fetchAttendanceData();
      },
      error: function(xhr, status, error) {
          console.error("Error deleting record:", error);
      }
  });
}


  function captureImage() {
    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');
    const video = document.getElementById('video');

    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    return canvas.toDataURL('image/jpeg');
  }

  function closeModal() {
    $('.bg-modal').fadeOut(500);
  }

  function getCurrentTime() {
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();

    return hours + ':' + minutes + ':' + seconds;
  }
});