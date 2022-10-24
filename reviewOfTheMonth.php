<?php

require_once 'includes/startSession.php';

if(($_SESSION['status']) !== 'teacher') {
    header("location: index.php");
}

// connect to database -> '$conn'

include('includes/db_connect.php');
include('includes/fetchCurrentMonth.php');

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
        <a href="books.php" class="books__header--link">Back to Top</a>
        <form action="searchedBooks.php" method="GET" class="books__form">
        <input type="text" name="search" placeholder="Search" class="books__header--input" />
        <button type="submit" class="books__search-button">
            <img src="templates/search.png">
        </button>
    </div>
    </div>
<!-- create section where books are displayed -->
<section class="menu">
    <!-- title -->
    <div class="title">
        <h2>
        <i>Review of the Month</i>
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
            <a class="form-link" href="add.php" id="linkReviews">
            Create a Book Review
            </a>
        </p>

        <!-- create a link for teachers and a different one for pupils -->
        <?php 
            if($_SESSION['status'] === 'teacher') {
                echo "<p class='form-text'>
                        <a class='form-link' href='books.php' id='linkReviews'>
                        Return to Reviews
                        </a>
                        </p>";
                echo "<p class='form-text'>
                        <a class='form-link' href='myPupils.php'>
                        Manage Pupils
                        </a>
                        </p>";
            }

        ?>

        <p class="form-text">
            <a class="form-link" href="includes/logout-inc.php" id="linkLogout">
            Logout
            </a>
        </p>
    </div>


<script type="text/javascript">

var booksObj = <?php echo json_encode($thisMonthBooks); ?>;

const schoolClass = <?php echo json_encode($_SESSION['schoolClass']); ?>;

const newBookReviews = [];

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
        'reviewOfMonth': book.review_of_month,
        'approved': book.approved,
        'dateCreated': book.date_created
    };

    if(book.schoolClass === schoolClass) {
        newBookReviews.push(newBook);
    }
});

const sectionCentre = document.querySelector(".section-center");

window.addEventListener("DOMContentLoaded", () => {
    displayBooks(newBookReviews);

    const monthlyBtns = document.querySelectorAll(".month-button");

    monthlyBtns.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            
            let selected = e.currentTarget.dataset.id;

            window.document.location = "includes/selectReviewMonth-inc.php" + "?id=" + selected;
        })
    })
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
        </footer>
        <form class="radio-form">
            <button type="submit" class="month-button" data-id="${book.id}">
            Click to Nominate
            </button>
        </form>
        </div>
        </article>
        `
}).join('');
sectionCentre.innerHTML = displayBooks;
}


</script>
</body>
</html>

    

