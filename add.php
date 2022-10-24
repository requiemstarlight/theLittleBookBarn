<?php

require_once 'includes/startSession.php';

if(!isset($_SESSION['id'])) {
    header("location: index.php");
    }

  // connect to the database, 'review'

include('includes/db_connect.php');

// check if the user is a pupil and if so how many pending reviews do they have
// pupils can have a maximum of 2 reviews pending

if($_SESSION['status'] === 'pupil') {
    require_once 'includes/db_fetch_books.php';

    $unapproved_count = 0;
    
    foreach ($books as $book) {
        if ($book['reviewer'] === $_SESSION['username'] && $book['approved'] == 0) {
            $unapproved_count++;

            if ($unapproved_count >= 2) {
                header('location: twoAlreadyPending.php');
                exit();
            }
        }
    }
}


$errors = array('title' => '', 
                'author'=> '',
                'desc'=> '',
                'reviewer'=> '',
                'image' => '');

// initialise values for each of the form fields

$title = $author = $rating = $desc = $reviewer = $image = '';

$genre = "Chose a genre";

if(isset($_POST['submit'])) {

    $title = $_POST['title'];
    $rating = $_POST['rating'];
    $desc = $_POST['desc'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $rating = $_POST['rating'];
    $genre = $_POST['genre'];
    $reviewer = $_POST['reviewer'];

    $schoolClass = $_SESSION['schoolClass'];
    $userID = $_SESSION['userID'];
    $approved = 0;

    if($_SESSION['status'] === 'teacher' || $_SESSION['status'] === 'admin') {
        $approved = 1;
    }

    if($_POST['genre'] === "Choose a genre") {
        $errors['genre'] = "You must select a genre <br />";
    }

    // check author
    if(!preg_match('/^[a-zA-Z\s]+$/', $author)) {
        $errors['author'] = "Author must be letters and spaces only <br />";
    }

    // check image 

    // read and store the file, which is an associative array
    $file = $_FILES['file'];

    // separate the array into variables
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    // Split the file name on the period
    $fileExt = explode('.', $fileName);

    // store the actual extension in lowercase
    $fileActualExt = strtolower(end($fileExt));

    // create an array of permitted extemsions
    $allowed = ['jpg', 'jpeg', 'png'];

    // check if the added file has the extension and is a suitable file

    if(in_array($fileActualExt, $allowed)) {
        if($fileError === 0) {
            if($fileSize < 100000) {
                // as all checks have passed, create a unique ID name for the file
                $fileNameNew = uniqid('', true).'.'.$fileActualExt;
                
                // create the destination in the images folder for the file
                $fileDestination = 'images/'.$fileNameNew;

                // now invoke the function to store the file
                move_uploaded_file($fileTmpName, $fileDestination);

                // save image location for uploading to database
                $image = $fileDestination;

            } else {
                $errors['image'] = "The file must be a maximum of 100KB <br />";
            }
        } else {
            $errors['image'] = "There was an error uploading this file <br />";
        }
    } else {
        $errors['image'] = "You cannot upload a file of this type <br />";
    }

    // check if there are no errors on form - then re-direct to another page
    if(!array_filter($errors)) {

        $status = $_SESSION['status'];

        $yearGroup = $_SESSION['yearGroup'];
        
        // create sql to insert the date into the users table

        $sql = "INSERT INTO review(user_id, title, genre, author, rating,
        book_description, reviewer, schoolClass, yearGroup, image_file, approved, status) 
        VALUES ('$userID', ?, '$genre', ?, '$rating', ?, '$reviewer', '$schoolClass', '$yearGroup', '$image', '$approved', '$status')";

        // create a prepared statement for the ? placeholder above

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: add.php");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "sss", $title, $author, $desc);
            mysqli_stmt_execute($stmt);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        if($_SESSION['status'] === "pupil") {
            header("location: pupilCreatedSuccess.php");
        } else if ($_SESSION['status'] === "teacher" || $_SESSION['status'] === "admin") {
            header("location: teacherCreatedSuccess.php");
        }
    }
} // end of POST check

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="This website allows school children to share their book reviews.
        It is a reading resource for primary/ elementary schools.">
    <!-- auto logout after 10 minutes of inactivity --> 
    <meta http-equiv="refresh" content="600;url=includes/logout-inc.php" />
    <title>The Little Book Barn</title>
    <link rel="shortcut icon" type="image/png" href="templates/bookicon.png">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="stylesheets/styles.css" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
</head>
<body>
<!-- create a container for the input form -->
<div class="container-create">
    <form class="form" id="review-form" action="add.php" method="POST"
    enctype="multipart/form-data">
        <!-- title -->
        <h1 class="title">Create Book Review</h1>

        <!-- input for Book title-->
        <div class="form__input-group">
            <input type="text" name="title" class="form__input" id="title-field"
            value="<?php echo htmlspecialchars($title); ?>" 
            minlength="2" maxlength="45" autofocus placeholder="Book Title" required>
            <div class="error-text"><?php echo $errors['title']; ?></div>
        </div>
            <!-- input for drop down and error message-->
            <div class="select-genre">
            <select class="genre" type="text" name="genre">
                <option value="<?php echo htmlspecialchars($genre); ?>" 
                selected hidden>
                    <?php echo htmlspecialchars($genre); ?>
                </option>
                <option value="Storybook">Storybook</option>
                <option value="Fiction">Fiction</option>
                <option value="Historical-fiction">Historical-Fiction</option>
                <option value="Poetry">Poetry</option>
                <option value="Fantasy">Fantasy</option>
                <option class="dropdown" value="non-fiction">Non-Fiction</option>
            </select>
        </div>
        <!-- input for author -->
        <div class="form__input-group">
            <input type="text" name="author" class="form__input" id="author-field"
            value="<?php echo htmlspecialchars($author); ?>"
            minlength="5" maxlength="35" placeholder="Author" required>
            <div class="error-text"><?php echo $errors['author']; ?></div>
        </div>
        <!-- input for rating and error message-->
        <div class="form__input-group">
            <input type="number" name="rating" class="form__input" id="rating-field"
            value="<?php echo htmlspecialchars($rating); ?>"
            placeholder="Rating (between 1 and 10)" min="1" max="10" required>               
        </div>
        <!-- input for book description -->
        <div class="form__input-group">
            <textarea rows="8" columns="6" type="text" name="desc"
            class="form__input-description" id="description-field"
            placeholder="Describe the book (150-350 characters)" 
            minlength="150" maxlength="350" spellcheck="true" required><?php echo htmlspecialchars($desc); ?></textarea>
            <div class="error-text"><?php echo $errors['desc']; ?></div>
        </div>
        <!-- input for reviewer -->
        <div class="form__input-group">
            <input type="text" name="reviewer" 
            class="form__input" id="reviewer-field"
            value="<?php echo $_SESSION['username'];?>" readonly>
            <div class="error-text"><?php echo $errors['reviewer']; ?></div>
        </div>
        <!-- input for image -->
        <div class="form-image">
            <label class="form-label" for="file">
                Add Book Cover Image
            </label><br>
                <input class="form-file" type="file" name="file" required>
                <div class="error-text"><?php echo $errors['image']; ?></div>
        </div>
        <!-- add the submit and reset buttons -->
        <div>
            <button type="submit" name="submit" value="submit"
            class="form__btn" id="submit-btn">Create review</button>
            <button type="reset" name="reset" class="form__btn" id="reset-btn">
                Reset form
            </button>
        </div>
        <div>
            <p class="form__text">
                <a class="form__link" href="books.php" id="linkReviews">
                Return to Reviews
                </a>
            </p>
            <!-- create a link for teachers and a different one for pupils -->
            <?php 
                if($_SESSION['status'] === 'teacher') {
                    echo "<p class='form__text'>
                            <a class='form__link' href='approve.php' id='linkReviews'>
                            Approve Pending Reviews
                            </a>
                            </p>";
                    echo "<p class='form__text'>
                            <a class='form__link' href='editDelete.php' id='linkReviews'>
                            Edit or Delete Reviews
                            </a>
                            </p>";
                } else if ($_SESSION['status'] === 'pupil') {
                        echo "<p class='form__text'>
                    <a class='form__link' href='editDeletePupil.php' id='linkReviews'>
                    Edit or Delete Pending Review
                    </a>
                </p>";
                }
                ?>

            <p class="form__text">
                <a class="form__link" href="includes/logout-inc.php" id="linkLogout">
                Logout
                </a>
            </p>
        </div>
    </form>
</div>
</body>
</html>