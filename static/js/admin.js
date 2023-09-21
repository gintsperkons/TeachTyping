const lessonRequests = (event) => {
  var functionName = event.target.getAttribute("data");
  var lessonID = document.getElementById("lessonID").value;
  var lessonNumber = document.getElementById("lessonNumber").value;
  var categoryName = document.getElementById("categoryName").value;
  var lessonTitle = document.getElementById("lessonTitle").value;
  var lessonText = document.getElementById("lessonText").value;
  jQuery
    .ajax({
      type: "POST",
      url: "lessonRequests.php",
      dataType: "json",
      data:
        "functionname=" +
        functionName +
        "&lessonID=" +
        lessonID +
        "&lessonNumber=" +
        lessonNumber +
        "&categoryName=" +
        categoryName +
        "&lessonTitle=" +
        lessonTitle +
        "&lessonText=" +
        lessonText,
    })
    .done(function (msg) {
      if (msg["error"]) {
        document.getElementById("errorText").textContent = msg["error"];
        console.log(msg["error"]);
        return;
      }
      if (functionName == "get") {
        document.getElementById("lessonNumber").value = msg["lessonNumber"];
        document.getElementById("categoryName").value = msg["categoryName"];
        document.getElementById("lessonTitle").value = msg["lessonTitle"];
        document.getElementById("lessonText").value = msg["lessonText"];
      } else {
        document.getElementById("lessonID").value = "";
        document.getElementById("lessonNumber").value = "";
        document.getElementById("categoryName").value = "";
        document.getElementById("lessonTitle").value = "";
        document.getElementById("lessonText").value = "";
      }
      document.getElementById("errorText").value = "";
      console.log(msg);
    });
};

document
  .getElementById("getLessonData")
  .addEventListener("click", lessonRequests);
document.getElementById("insertData").addEventListener("click", lessonRequests);
document.getElementById("updateData").addEventListener("click", lessonRequests);
document.getElementById("deleteData").addEventListener("click", lessonRequests);
