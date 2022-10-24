<?php

require_once 'includes/startSession.php';

if(!isset($_SESSION['id'])) {
    header("location: index.php");
}

// connect to database -> '$conn'
include('includes/db_connect.php');

// fetch books reviewed in the last 3 weeks
include('includes/fetchTopReviews-inc.php');

// get the previous month

$d = new DateTime( date("Y-m-d") );
$d->modify( 'last day of previous month' );

$month = $d->format( 'F' );

$year = $d->format( 'Y' );

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
<body class="body review-of-month">
<!-- create navbar -->
<div class="books__header--cont header-review-of-month">
    <div class="books__header">
        <a href="books.php" class="books__header--link">Back to Reviews</a>
    </div>
</div>
<!-- create section where books are displayed -->
<section class="menu">
<!-- title -->
<div class="title trending-title">
    <h2><i>Reviews of the Month</i></h2>
    <h4><?php echo $month. " ". $year; ?></h4>
    <div class="underline"></div>
</div>

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
        <a class="form-link" href="includes/logout-inc.php" id="linkLogout">
        Logout
        </a>
    </p>
</div>


<script type="text/javascript">

var booksObj = <?php echo json_encode($reviewsOfMonth); ?>;

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
        'approved': book.approved
    };
    newBookReviews.push(newBook);
});

const sectionCentre = document.querySelector(".section-center");

window.addEventListener("DOMContentLoaded", () => {
    displayBooks(newBookReviews);
});

const displayBooks = (list) => {
    const displayBooks = list.map((book, index) => {
    return `
        <div>
            <h3 class="review-title">${book.schoolClass} Top Review</h3>
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
            </div>
            </article>
        </div>
        `
    }).join('');
    sectionCentre.innerHTML = displayBooks;
}

</script>
</body>
</html>

    

