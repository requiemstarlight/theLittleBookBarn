<?php

require_once 'includes/startSession.php';

if($_SESSION['status'] !== 'teacher') {
  header("location: index.php");
}

include('includes/db_connect.php');
include('includes/db_fetch_unapproved.php');

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
    <div class="books__header--cont">
        <div class="books__header">
            <a href="books.php" class="books__header--link">Back to Reviews</a>
        </div>
    </div>
    <section class="menu">
      <!-- title -->
      <div class="title">
          <h2>
            <i>Reviews for Approval</i>
          </h2>
            <div class="underline"></div>
      </div>
        <!-- button container and buttons -->
      <div class="btn-container"></div>

      <!-- book listings-->
      <div class="section-center"></div>

    </section>

      <div class>
      <p class="form-text">
              <a class="form-link" href="books.php" id="linkReviews">
              Return to Reviews
              </a>
          </p>
          <p class="form-text">
              <a class="form-link" href="add.php" id="linkReviews">
              Create a Book Review
              </a>
          </p>
          <p class='form-text'>
            <a class='form-link' href='editDelete.php' id='linkReviews'>
            Edit or Delete Reviews
            </a>
          </p>
          <p class="form-text">
              <a class="form-link" href="includes/logout-inc.php" id="linkLogout">
              Logout
              </a>
          </p>
      </div>
  </body>
</html>


<script src="buttonConfirm/btnConfirm.js"></script>
<script type="text/javascript">

var booksObj = <?php echo json_encode($books); ?>;

const newBookReviews = [];

// create a variable referring to the teacher's class, in order to search relevant unapproved books.

const cls = "<?php echo $_SESSION['schoolClass']; ?>";

booksObj.forEach((book) => {
  newBook = {
    'id': book.id,
    'user_id': book.user_id,
    'title': book.title,
    'genre': book.genre,
    'author': book.author,
    'rating': book.rating,
    'desc': book.book_description,
    'reviewer': book.reviewer,
    'schoolClass': book.schoolClass,
    'img': book.image_file,
    'approved': book.approved
  };

  newBookReviews.push(newBook);
});

const filteredBooks = newBookReviews.filter((book) => book.schoolClass === cls);

const sectionCentre = document.querySelector(".section-center");
const container = document.querySelector(".btn-container");

window.addEventListener("DOMContentLoaded", () => {
    displayBooks(filteredBooks);

    const approveBtns = document.querySelectorAll(".btn-approve");
    const deleteBtns = document.querySelectorAll(".btn-delete");

    approveBtns.forEach((btn) => {
      btn.addEventListener('click', (e) => {
        var selectedBook = e.currentTarget.dataset.id;

        Confirm.open({ 
                    title: 'Approve Review',
                    message: 'Are you sure you want to approve this review? It will be available for all pupils to view',
                    okText: "Confirm",
                    cancelText: "Cancel",
                    onok: () => window.document.location = window.document.location = window.document.location = "includes/approve-inc.php" + "?bookId=" + selectedBook,
                    oncancel: () => console.log("You cancelled. Review not deleted")
                });
      });
    });

    deleteBtns.forEach((btn) => {
      btn.addEventListener('click', (e) => {
        var selectedBook = e.currentTarget.dataset.id;

        // console.log(selectedBook);

        Confirm.open({ 
                    title: 'Delete Review',
                    message: 'Are you sure you want to delete this review? It cannot be retrieved',
                    okText: "Confirm",
                    cancelText: "Cancel",
                    onok: () => window.document.location = window.document.location = "includes/delete-inc.php" + "?bookId=" + selectedBook + "&location=approve",
                    oncancel: () => console.log("You cancelled. Review not deleted")
                });
      });
    });

});

const displayBooks = (list) => {
  const displayBooks = list.map((book) => {
    return `
    <article class="menu-item">
    <img src=${book.img} alt=${book.title} 
    class="photo" />
    <div class="item-info">
      <header>
        <h4>${book.title}</h4>
        <h5 class="author">
          <i>${book.author}</i>
        </h5>
        <h4 class="rating">${book.rating}</h4>
      </header>
      <p class="item-text">
      ${book.desc}
      </p>
      <footer>
      <h6 class="reviewer">
      <i> Reviewed by ${book.reviewer}</i>
      </h6>
      <div class="btn-container">
        <button class="btn-approve" type="button" 
        data-id="${book.id}">
        approve </button>
        <button class="btn-delete" type="button" 
        data-id="${book.id}">
        delete </button>
    </footer>
    </div>
  </article>
    `
  }).join('');
  sectionCentre.innerHTML = displayBooks;
};

</script>
</body>
</html>

    

