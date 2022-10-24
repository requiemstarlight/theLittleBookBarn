<?php

require_once 'includes/startSession.php';
require_once 'includes/db_connect.php';

$errors = array('title' => '', 
                'author'=> '',
                'desc'=> '',
                'reviewer'=> '',
                'image' => '');

$id = '';

if(!$_SESSION['status']) {
  header("location: index.php");
}

if((!$_SESSION['status'] === 'pupil') || (!$_SESSION['status'] === 'teacher')) {
  header("location: index.php");
}

if(!isset($_GET['bookId']) && !isset($_POST['submit'])) {
    header("location: books.php");
    }

// check the $_GET value and set the fields with the existing review

if(isset($_GET['bookId'])) {
      // assign the variable id, in order to locate the book in the DB
      $id = $_GET['bookId'];

      // connect to the database
      require_once 'includes/db_connect.php';
      require_once 'includes/db_fetch_one.php';

      // print_r($books);

      $title = $books[0]['title'];
      $genre = $books[0]['genre'];
      $author = $books[0]['author'];
      $rating = $books[0]['rating'];
      $desc = $books[0]['book_description'];
      $review = $books[0]['reviewer'];
      $schoolClass = $books[0]['schoolClass'];
  }

// check the $_POST method and carry out checks on field entries for suitability

if(isset($_POST['submit'])) {

    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $author = $_POST['author'];
    $rating = $_POST['rating'];
    $desc = $_POST['desc'];


    // check author
    if($_POST['author']) {
            if(!preg_match('/^[0-9A-Za-z\s\-]+$/', $author)) {
                $errors['author'] = "Author must be letters, hyphens and spaces only";
            }
    }

    // check image 

    if($_FILES['file']['name']) {

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
                    $errors['image'] = "The file must be a maximum of 100KB";
                }
            } else {
                $errors['image'] = "There was an error uploading this file";
            }
        } else {
            $errors['image'] = "You cannot upload a file of this type";
        }
    } 

    // check if there are no errors on form - then re-direct to another page once the database has been updated
    if(!array_filter($errors)) {
        
        $title = $_POST['title'];
        $genre = $_POST['genre'];
        $author = $_POST['author'];
        $rating = $_POST['rating'];
        $desc = $_POST['desc'];
        $reviewer = $_POST['reviewer'];
        $schoolClass = $_SESSION['schoolClass'];
        $userID = $_SESSION['userID'];
        $approved = 0;
        $id = $_POST['bookID'];

        if($_SESSION['status'] === 'teacher') {
            $approved = 1;
        }


        // create sql to insert the date into the users table

        $sql = "UPDATE review SET title = ?, genre = '".$genre."',
                author = ?, rating = '".$rating."', book_description = ?,
                approved = '".$approved."'
                WHERE id = '".$id."'";

        // create a prepared statement for the ? placeholder above

        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: edit.php");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "sss", $title, $author, $desc);
            mysqli_stmt_execute($stmt);
        }

        mysqli_stmt_close($stmt);


        // now check if the user uploaded an image
        if(!$image) {
        header("location: editedSuccess.php");
        } else {
        // write a SQL entry to update the image
        $newSql = "UPDATE review SET image_file = '".$image."'
        WHERE id = '".$id."'";

        $newResult = mysqli_query($conn, $newSql);

        if($newResult) {
            // success
            header("location: editedSuccess.php");
        } else {
            // error
            echo 'Query error: ' . mysqli_error($conn);
            header("location: books.php");
                }
            }
         }
    }
// end of POST check
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
        <form class="form" id="review-form" action="edit.php" method="POST"
        enctype="multipart/form-data">
            <!-- title -->
            <h1 class="title">Edit Book Review</h1>

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
                    <option>
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
                placeholder="Describe the book (100-250 characters)" 
                minlength="100" maxlength="350" spellcheck="true" required><?php echo htmlspecialchars($desc); ?></textarea>
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
                </label>
                 <input class="form-file" type="file" name="file">
                 <div class="error-text"><?php echo $errors['image']; ?></div>
            </div>
            <!-- add a hidden input to POST the bookId -->
            <input type="hidden" name="bookID" 
            value="<?php echo htmlspecialchars($id); ?>">

           <!-- add the submit button -->
            <div>
                <button type="submit" name="submit" value="submit"
                class="form__btn" id="submit-btn">Edit Review</button>
            </div>
            <div>
                <button type="button" name="cance" value="cancel"
                class="form__btn">
                    <a href="books.php">Cancel</a>
                </button>
            </div>
            <br /> 
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
                        <a class='form__link' href='edit.php' id='linkReviews'>
                        Edit Pending Review
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




    

