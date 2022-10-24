<?php

require_once 'includes/startSession.php';

if(!isset($_SESSION['id'])) {
  header("location: index.php");
}

// connect to database -> '$conn'

include('includes/db_connect.php');
include('includes/db_fetch_books.php');

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
            <i>Prep School Must Reads</i>
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
                              <a class='form-link' href='reviewOfTheMonth.php' id='linkReviews'>
                              Choose Review of the Month
                              </a>
                              </p>";
                        echo "<p class='form-text'>
                              <a class='form-link' href='approve.php' id='linkReviews'>
                              Approve Pending Reviews
                              </a>
                              </p>";
                        echo "<p class='form-text'>
                              <a class='form-link' href='editDelete.php' id='linkReviews'>
                              Edit or Delete Reviews
                              </a>
                              </p>";
                        echo "<p class='form-text'>
                              <a class='form-link' href='myPupils.php'>
                              Manage Pupils
                              </a>
                              </p>";
                    } else if ($_SESSION['status'] === 'pupil') {
                        echo "<p class='form-text'>
                              <a class='form-link' href='editDeletePupil.php' id='linkReviews'>
                              Edit or Delete Pending Review
                              </a>
                              </p>";
                    } else if ($_SESSION['status'] === 'admin') {
                        echo "<p class='form-text'>
                              <a class='form-link' href='myTeachers.php'>
                              Manage Teachers
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

var booksObj = <?php echo json_encode($books); ?>;


const newBookReviews = [];
const emptyBooks = [];

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
    'yearGroup': book.yearGroup,
    'img': book.image_file,
    'reviewOfMonth': book.review_of_month,
    'approved': book.approved,
    'status': book.status,
    'dateCreated': book.date_created
  };

  newBookReviews.push(newBook);
});

const sectionCentre = document.querySelector(".section-center");
const container = document.querySelector(".btn-container");

window.addEventListener("DOMContentLoaded", () => {
    classChoiceBtns();
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
      </div>
    </article>
    `
  }).join('');
  sectionCentre.innerHTML = displayBooks;
}

const classChoiceBtns = () => {

  // create an object containing the classes

  const classes = {
    'prep-1': ['ocean', 'rainforest', 'savannah', 'woodland'],
    'prep-2': ['cajueiro', 'coqueiro', 'ipe', 'manaca'],
    'prep-3': ['frida', 'niemayer', 'ohtake', 'tarsila'],
    'prep-4': ['curie', 'darwin', 'franklin', 'turing'],
    'prep-5': ['gandhi', 'malala', 'mandela', 'tiradentes']
    };

    // create yearGroup buttons

    let yearGroups = Object.keys(classes);

    yearGroups.push('teachers', 'trending', 'top reviews');

    const yearGroupBtns = yearGroups.map((yearGroup) => {
      return `
      <button class="filter-btn-blue" type="button" 
      data-id=${yearGroup}>${yearGroup}</button>
      `
    }).join('');
    container.innerHTML = yearGroupBtns;

    const buttons = document.querySelectorAll('.filter-btn-blue');

    buttons.forEach((btn) => {
      btn.addEventListener('click', (e) => {
        
      const selectedYearGroup = e.currentTarget.dataset.id;

      // check if the user chooses the 'trending' option

      if(selectedYearGroup === 'trending') {
        window.document.location = "trending.php";
      } else if(selectedYearGroup === 'top') {
        window.document.location = "topReviews.php";
      } else if(selectedYearGroup === 'teachers') {
        const teacherBooks = newBookReviews.filter((book) => book.status === 'teacher' || book.status === 'admin');
        displayBooks(teacherBooks);
        displayTeacherReviewerBtns(teacherBooks);
      } else {

        // user has now selected the year group - display the classes buttons

        const chosenClasses = classes[selectedYearGroup];

        chosenClasses.unshift('back', 'all');

        const classBtns = chosenClasses.map((cls) => {
          return `
          <button class="filter-btn-purple" type="button" 
          data-id=${cls}>${cls}</button>
          `
          }).join('');
          container.innerHTML = classBtns;

          const clsButtons = document.querySelectorAll('.filter-btn-purple');

          // class buttons

          clsButtons.forEach((btn) => {
            btn.addEventListener('click', (e) => {
          
          const selectedClass = e.currentTarget.dataset.id;

          if(selectedClass === 'back') {
            classChoiceBtns();
          } else if (selectedClass === 'all') {
              const yearReviews = newBookReviews.filter((b) => (b.yearGroup === selectedYearGroup));
              displayBooks(yearReviews);
          } else {
              const bookList = newBookReviews.filter((book) => (book.schoolClass === selectedClass) && (book.approved == true));
              displayBooks(bookList);
              displayChoiceBtns(selectedClass);
            };
          });
        });
      }

  });
});
};

const displayChoiceBtns = (classChoice) => {
  const choices = ['back', 'all', 'genre', 'reviewer'];

  const choiceBtns = choices.map((choice) => {
    return `
    <button class="filter-btn-red" type="button" 
    data-id=${choice}>${choice}</button>
    `
  }).join('');
  container.innerHTML = choiceBtns;

  const buttons = document.querySelectorAll('.filter-btn-red');

  // filter the overall booklist just for the current class and for approved reviews
  const classBookList = newBookReviews.filter((book) => book.schoolClass === classChoice && book.approved == true);

  buttons.forEach((btn) => {
    btn.addEventListener('click', (e) => {
      const selectedChoice = e.currentTarget.dataset.id;

      if (selectedChoice === 'all') {
        displayBooks(classBookList);
      } else if (selectedChoice === 'genre') {
        displayGenreBtns(classBookList, classChoice);
      } else if (selectedChoice === 'reviewer') {
        displayReviewerBtns(classBookList, classChoice);
      } else if (selectedChoice == 'back') {
        classChoiceBtns();
        displayBooks(emptyBooks);
      }
    });
  });
};

const displayGenreBtns = (list, classChosen) => {
  const genres = list.reduce((values, current) => {
    if (!values.includes(current.genre)) {
      values.push(current.genre);
    };
    return values;
  }, ['back', 'all']);


  const genreBtns = genres.map((genre) => {
    return `
    <button class="filter-btn-gold" type="button" 
    data-id=${genre}>${genre}</button>
    `
  }).join('');
  container.innerHTML = genreBtns;

  const genreButtons = document.querySelectorAll('.filter-btn-gold');

  genreButtons.forEach((btn) => {
    btn.addEventListener('click', (e) => {
      const category = e.currentTarget.dataset.id;
      console.log(category);

      if (category === 'all') {
        displayBooks(list);
      } else if (category === 'back') {
        displayChoiceBtns(classChosen)
        displayBooks(list);
      } else {
        const selectedReading = list.filter((book) => category === book.genre && book.approved == true);
        displayBooks(selectedReading);
      }
    });
  });
};

const displayReviewerBtns = (list, classChosen) => {
  const reviewers = list.reduce((values, current) => {
    if (!values.includes(current.reviewer)) {
      values.push(current.reviewer);
    };
    return values;
  }, ['back', 'all']);


  const filterBtns = reviewers.map((reviewer) => {
    return `
    <button class="filter-btn-green" type="button" 
    data-id=${reviewer}>${reviewer}</button>
    `
  }).join('');
  container.innerHTML = filterBtns;

  const buttons = document.querySelectorAll('.filter-btn-green');

  buttons.forEach((btn) => {
    btn.addEventListener('click', (e) => {
      const reviewer = e.currentTarget.dataset.id;
      // console.log(reviewer);

      if (reviewer === 'all') {
        displayBooks(list);
      } else if (reviewer === 'back') {
        displayChoiceBtns(classChosen);
        displayBooks(list);
      } else {
        const selectedReading = list.filter((item) => {
        if (reviewer === item.reviewer)
          return item;
      });
        displayBooks(selectedReading);
      }
    });
});
};

const displayTeacherReviewerBtns = (list) => {
  const reviewers = list.reduce((values, current) => {
    if (!values.includes(current.reviewer)) {
      values.push(current.reviewer);
    };
    return values;
  }, ['back', 'all']);


  const filterBtns = reviewers.map((reviewer) => {
    return `
    <button class="filter-btn-green" type="button" 
    data-id=${reviewer}>${reviewer}</button>
    `
  }).join('');
  container.innerHTML = filterBtns;

  const buttons = document.querySelectorAll('.filter-btn-green');

  buttons.forEach((btn) => {
    btn.addEventListener('click', (e) => {
      const reviewer = e.currentTarget.dataset.id;

      if (reviewer === 'all') {
        displayBooks(list);
      } else if (reviewer === 'back') {
        window.document.location = "books.php";
      } else {
        const selectedReading = list.filter((item) => {
        if (reviewer === item.reviewer)
          return item;
      });
        displayBooks(selectedReading);
      }
    });
});
};

</script>
</body>
</html>

    

