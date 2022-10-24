<?php

require_once 'includes/startSession.php';

if(!isset($_SESSION['id']) && $_SESSION['status'] !== 'admin') {
    header("location: index.php");
}

// connect to database -> '$conn'

include('includes/db_connect.php');
include('includes/fetch_teachers-inc.php');


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="This website allows school children to share their book reviews.
    It is a reading resource for primary/ elementary schools.">
<!-- auto logout after 10 minutes of inactivity --> 
<meta http-equiv="refresh" content="600;url=includes/logout-inc.php" />
<title>The Little Book Barn</title>
<link rel="shortcut icon" type="image/png" href="templates/bookicon.png">
<!-- font-awesome -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
/>
<!-- styles -->
<link type="text/css" rel="stylesheet" href="stylesheets/styles2.css" /> 
</head>
<body class="body">
<!-- create navbar -->
<div class="books__header--cont">
    <div class="books__header">
        <a href="books.php" class="books__header--link">Go to Reviews</a>
    </div>
</div>
<!-- create section where books are displayed -->
<section class="menu">
    <!-- title -->
    <div class="title">
        <h2>
        <i>Our Teachers</i>
        </h2>
        <div class="underline"></div>
    </div>
    <div class="unapproved-table">
        <!-- approved pupils -->
        <h3>Approved Teachers</h3>
        <table class="content-table">
        <thead>
                <tr>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Approve?</th>
                        <th>Delete?</th>
                </tr>
        </thead>
        <tbody class="approved-items"></tbody>
        </table>
        <br/>

        <h3>Unapproved Teachers</h3>
        <table class="content-table">
        <thead>
                <tr>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Unapprove?</th>
                        <th>Delete?</th>
                </tr>
        </thead>
        <tbody class="unapproved-items"></tbody>
        </table>
    </div>

</section>

    <div>
        <p class="form-text">
            <a class="form-link" href="books.php" id="linkReviews">
                Go to Reviews
            </a>
        </p>
        <p class="form-text">
            <a class="form-link" href="includes/logout-inc.php" id="linkLogout">
                Logout
            </a>
        </p>
    </div>

<script src="buttonConfirm/btnConfirm.js"></script>
<script type="text/javascript">

const teachers = <?php echo json_encode($teachers); ?>;

let approvedTeachers = [];
let unapprovedTeachers = [];

teachers.forEach((teacher) => {
    newTeacher = {
        'id': teacher.id,
        'name': teacher.name,
        'surname': teacher.surname,
        'email': teacher.email,
        'schoolClass': teacher.schoolClass,
        'username': teacher.username,
        'status': teacher.status,
        'emailVerified': teacher.emailVerified,
        'teacherApproved': teacher.teacherApproved,
    };

    if(newTeacher.emailVerified == 1 && newTeacher.teacherApproved == 0) {
        unapprovedTeachers.push(newTeacher);
    } else if(newTeacher.emailVerified == 1 && newTeacher.teacherApproved == 1) {
        approvedTeachers.push(newTeacher);
    }
});

const unapproved = document.querySelector(".unapproved-items");
const approved = document.querySelector(".approved-items");

window.addEventListener("DOMContentLoaded", () => {
    displayUnapproved(unapprovedTeachers);
    displayApproved(approvedTeachers);

    const approveBtns = document.querySelectorAll(".approve-button");
    const unapproveBtns = document.querySelectorAll(".unapprove-button");
    const deleteBtns = document.querySelectorAll(".delete-button");

    approveBtns.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            var selectedTeacher = e.currentTarget.dataset.id;

            Confirm.open({ 
                title: 'Approve Teacher?',
                message: 'Are you sure you want to approve this teacher? They will have instant access to the site',
                okText: "Confirm",
                cancelText: "Cancel",
                onok: () => window.document.location = "includes/adminApprovesTeacher-inc.php" + "?id=" + selectedTeacher,
                oncancel: () => console.log("You cancelled. Review not deleted")
            });
        });
    });

    unapproveBtns.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            var selectedTeacher = e.currentTarget.dataset.id;

                Confirm.open({ 
                    title: 'Unapprove Pupil?',
                    message: 'Are you sure you want to unapprove this teacher? Their access will be suspended',
                    okText: "Confirm",
                    cancelText: "Cancel",
                    onok: () => window.document.location = "includes/adminUnapprovesTeacher-inc.php" + "?id=" + selectedTeacher,
                    oncancel: () => console.log("You cancelled. Review not deleted")
                });
        });
    });

    deleteBtns.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            var selectedTeacher = e.currentTarget.dataset.id;

            Confirm.open({ 
                title: 'Delete Pupil?',
                message: 'Are you sure you want to proceed? Data or reviews cannot be retrieved',
                okText: "Confirm",
                cancelText: "Cancel",
                onok: () => window.document.location = "includes/adminDeletesTeacher-inc.php" + "?id=" + selectedTeacher,
                oncancel: () => console.log("You cancelled. Review not deleted")
            });
        });
    });
});

const displayUnapproved = ((list) => {

if(list.length === 0) {
unapproved.innerHTML = "<p>&emsp; None</p>";
} else {

    let displayTeachers = list.map((teacher) => {
return `
    <tr>
        <td data-col-title="Name">${teacher.name}</td>
        <td data-col-title="Surname">${teacher.surname}</td>
        <td data-col-title="Username">${teacher.username}</td>
        <td data-col-title="Email">${teacher.email}</td>
        <td data-col-title="Status">${teacher.status}</td>
        <td data-col-title="Approve?"><button class="approve-button" type="button" 
        data-id="${teacher.id}">Approve</button></td>
        <td data-col-title="Delete?"><button class="delete-button" type="button" 
        data-id="${teacher.id}">Delete</button></td>
    </tr>
`
}).join('');
unapproved.innerHTML = displayTeachers;
}
})

const displayApproved = ((list) => {

    if(list.length === 0) {
    approved.innerHTML = "<p>&emsp; None</p>";
    } else {

        let displayTeachers = list.map((teacher) => {
    return `
        <tr>
            <td data-col-title="Name">${teacher.name}</td>
            <td data-col-title="Surname">${teacher.surname}</td>
            <td data-col-title="Username">${teacher.username}</td>
            <td data-col-title="Email">${teacher.email}</td>
            <td data-col-title="Status">${teacher.status}</td>
            <td data-col-title="Unapprove?"><button class="unapprove-button" type="button" 
            data-id="${teacher.id}">Unapprove</button></td>
            <td data-col-title="Delete?"><button class="delete-button" type="button" 
            data-id="${teacher.id}">Delete</button></td>
        </tr>
    `
}).join('');
approved.innerHTML = displayTeachers;
}
})

</script>
</body>
</html>