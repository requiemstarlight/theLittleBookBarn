<?php

require_once 'includes/startSession.php';

if(!isset($_SESSION['id']) && $_SESSION['status'] !== 'teacher') {
    header("location: index.php");
}

// connect to database -> '$conn'

include('includes/db_connect.php');
include('includes/fetch_pupils-inc.php');


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
        <i><?php echo $_SESSION['schoolClass']; ?> Pupils</i>
        </h2>
        <div class="underline"></div>
    </div>
    <div class="unapproved-table">
        <!-- approved pupils -->
      <h3>Approved Pupils</h3>
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

      <h3>Unapproved Pupils</h3>
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
            <a class="form-link" href="reviewOfTheMonth.php" id="linkReviews">
                Choose Review of the Month
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

const pupils = <?php echo json_encode($pupils); ?>;

let approvedPupils = [];
let unapprovedPupils = [];

pupils.forEach((pupil) => {
    newPupil = {
        'id': pupil.id,
        'name': pupil.name,
        'surname': pupil.surname,
        'email': pupil.email,
        'schoolClass': pupil.schoolClass,
        'username': pupil.username,
        'status': pupil.status,
        'emailVerified': pupil.emailVerified,
        'teacherApproved': pupil.teacherApproved,
    };

    if(newPupil.emailVerified == 1 && newPupil.teacherApproved == 0) {
        unapprovedPupils.push(newPupil);
    } else if(newPupil.emailVerified == 1 && newPupil.teacherApproved == 1) {
        approvedPupils.push(newPupil);
    }
});

const unapproved = document.querySelector(".unapproved-items");
const approved = document.querySelector(".approved-items");

window.addEventListener("DOMContentLoaded", () => {
    displayUnapproved(unapprovedPupils);
    displayApproved(approvedPupils);

    const approveBtns = document.querySelectorAll(".approve-button");
    const unapproveBtns = document.querySelectorAll(".unapprove-button");
    const deleteBtns = document.querySelectorAll(".delete-button");

    approveBtns.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            var selectedPupil = e.currentTarget.dataset.id;

            Confirm.open({ 
                title: 'Approve Pupil?',
                message: 'Are you sure you want to approve this pupil? They will have instant access to the site',
                okText: "Confirm",
                cancelText: "Cancel",
                onok: () => window.document.location = "includes/teacherApprovesPupil-inc.php" + "?id=" + selectedPupil,
                oncancel: () => console.log("You cancelled. Review not deleted")
            });
        });
    });

    unapproveBtns.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            var selectedPupil = e.currentTarget.dataset.id;

            Confirm.open({ 
                  title: 'Unapprove Pupil?',
                  message: 'Are you sure you want to unapprove this pupil? Their access will be suspended',
                  okText: "Confirm",
                  cancelText: "Cancel",
                  onok: () => window.document.location = "includes/teacherUnapprovesPupil-inc.php" + "?id=" + selectedPupil,
                  oncancel: () => console.log("You cancelled. Review not deleted")
              });
        });
    });

    deleteBtns.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            var selectedPupil = e.currentTarget.dataset.id;

            Confirm.open({ 
                title: 'Delete Pupil?',
                message: 'Are you sure you want to proceed? Data or reviews cannot be retrieved',
                okText: "Confirm",
                cancelText: "Cancel",
                onok: () => window.document.location = "includes/teacherDeletesPupil-inc.php" + "?id=" + selectedPupil,
                oncancel: () => console.log("You cancelled. Review not deleted")
            });
        });
    });
});

const displayUnapproved = ((list) => {

  if(list.length === 0) {
    unapproved.innerHTML = "<p>&emsp; None</p>";
  } else {

  displayPupils = list.map((pupil) => {
    return `
      <tr>
        <td data-col-title="Name">${pupil.name}</td>
        <td data-col-title="Surname">${pupil.surname}</td>
        <td data-col-title="Username">${pupil.username}</td>
        <td data-col-title="Email">${pupil.email}</td>
        <td data-col-title="Status">${pupil.status}</td>
        <td data-col-title="Approve?">
          <button class="approve-button" type="button" 
          data-id="${pupil.id}">Approve</button>
        </td>
        <td data-col-title="Delete?">
          <button class="delete-button" type="button" 
          data-id="${pupil.id}">Delete</button>
        </td>
      </tr>
    `
  }).join('');

        unapproved.innerHTML = displayPupils;
  }
})

const displayApproved = ((list) => {

  if(list.length === 0) {
    approved.innerHTML = "<p>&emsp; None</p>";
  } else {
  
  displayPupils = list.map((pupil) => {
    return `
      <tr>
        <td data-col-title="Name">${pupil.name}</td>
        <td data-col-title="Surname">${pupil.surname}</td>
        <td data-col-title="Username">${pupil.username}</td>
        <td data-col-title="Email">${pupil.email}</td>
        <td data-col-title="Status">${pupil.status}</td>
        <td data-col-title="Unapprove?"><button class="unapprove-button" type="button" 
        data-id="${pupil.id}">Unapprove</button></td>
        <td data-col-title="Delete?"><button class="delete-button" type="button" 
        data-id="${pupil.id}">Delete</button></td>
      </tr>
    `
  }).join('');
        
        approved.innerHTML = displayPupils;
  }
})


</script>
</body>
</html>