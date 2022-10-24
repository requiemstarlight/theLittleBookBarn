<?php

require_once 'includes/startSession.php';

if(!isset($_SESSION['id'])) {
    header("location: index.php");
}

// connect to database -> '$conn'
include('includes/db_connect.php');

// fetch books reviewed in the last 3 weeks
include('includes/fetchRecentlyReviewed-inc.php');

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
<body class="body trending">
<!-- create navbar -->
<div class="books__header--cont header-trending">
    <div class="books__header">
        <a href="books.php" class="books__header--link">Back to Reviews</a>
    </div>
</div>
<!-- create section where books are displayed -->
<section class="menu menu-trending">
<!-- title -->
<div class="title trending-title">
    <h2>
    <i>Trending Now</i>
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
        <a class="form-link" href="includes/logout-inc.php" id="linkLogout">
        Logout
        </a>
    </p>
</div>


<script type="text/javascript">

var booksObj = <?php echo json_encode($recentlyReviewed); ?>;

console.log(booksObj);

const newBookReviews = [];
const trendingList = [];

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

    if(trendingList.length === 0) {
        trendingList.push(
            {title: book.title,
            reviews: 1,
            author: book.author,
            genre: book.genre,
            image: book.image_file,
            rating: [book.rating]});
    } else {
        let found = false;
        for(item of trendingList) {
            if (item.title.toLowerCase() === book.title.toLowerCase()) {
                item.reviews +=1;
                item.rating.push(book.rating);
                found = true;
                break;
            }} 

            if (!found) {
                trendingList.push(
                {title: book.title,
                reviews: 1,
                author: book.author,
                genre: book.genre,
                image: book.image_file,
                rating: [book.rating]}); 
            };        
    }
    newBookReviews.push(newBook);
});

console.log(trendingList);

// remove any books with less than two reviews

finalList = [];

for(let i = trendingList.length - 1; i >= 0; i--) {
    if(trendingList[i].reviews >= 2) { 
        finalList.push(trendingList[i]);
    }   
}

// sort the trending list from most popular to least popular

finalList.sort(function(a, b) {
    return b.reviews - a.reviews;
}); 

// convert all titles and authors to title case

for(book of finalList) {
    const title = book.title.split(' ')
    .map(w => w[0].toUpperCase() + w.substr(1).toLowerCase())
    .join(' ');

    book.title = title;

    const author = book.author.split(' ')
    .map(w => w[0].toUpperCase() + w.substr(1).toLowerCase())
    .join(' ');

    book.author = author;
}


// calculate the average of the reviews, and add it as an object variable

for(book of finalList) {

    let total = 0;
    for(rating of book.rating) {
        var parsed = parseInt(rating);
        total += parsed;        
    }

    book.average = Math.round((total / book.rating.length) * 10) / 10;
}

// remove all but 10 books in the trending list

finalList.length = 10;

const sectionCentre = document.querySelector(".section-center");
const container = document.querySelector(".btn-container");

window.addEventListener("DOMContentLoaded", () => {
    displayBooks(finalList);
});

const displayBooks = (list) => {
    const displayBooks = list.map((book, index) => {

        return `
        <article class="menu-item">
        <img src="${book.image}" alt="${book.title}" 
        class="photo" />
        <div class="item-info">
        <header>
            <h4>${book.title}</h4>
            <h5 class="author">
            <i>${book.author}</i>
            <h4 class="rating">${book.average}</h4>
            </h5>
        </header>
        <p class="item-text text-trending">
        '${book.title}', by ${book.author} is currently the 'Number ${index + 1}' 
        trending book in school with ${book.reviews} reviews over the last
        month. '${book.title}' has an average rating of ${book.average} out of 10.
        </p>
        <footer>
            <h6 class="reviewer reviewer-link">
            <a href="searchedBooks.php?search=${book.title}">Click here for all Reviews</a>
            </h6>
        </footer>
        </div>
    </article>
        `
    }).join('');
    sectionCentre.innerHTML = displayBooks;
}

</script>
</body>
</html>

    

