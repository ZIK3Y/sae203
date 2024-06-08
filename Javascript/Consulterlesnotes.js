function toggleAssignments(assignmentsId) {
    var assignments = document.getElementById(assignmentsId);
    if (assignments.style.display === "none" || assignments.style.display === "") {
        assignments.style.display = "block";
    } else {
        assignments.style.display = "none";
    }
}
