<?php

// ============== Includes ==============

require "header.php";

include('search_fns.php');
include('indic-wp-master/word_processor.php');
include('indic-wp-master/telugu_parser.php');

// ============== Variables ==============

// DB config.
DEFINE('DATABASE_HOST', 'localhost');
DEFINE('DATABASE_DATABASE', 'crawler');
DEFINE('DATABASE_USER', 'root');
DEFINE('DATABASE_PASSWORD', '');

// Column headers.
$col1 = "ID";
$col2 = "Word";
$col3 = "Length";
$col4 = "Strength";
$col5 = "Weight";

// Search Options
$searchDropdown = isset($_POST['searchOptions']);
$searchRadio =  isset($_POST['langRadio']);
$user_search_string = ""; //@#$ Use as default if you don't want the search to run automatically.
$message = "No Result Found";
$word = "";
$language = "telugu";
$option = "contains";
$contain_value = 1;
$prefix = "prefix";
$suffix = "suffix";
$length_equals = 3;
$length_between_from = 2;
$length_between_to = 10;
$result_limit = isset($_POST['limit']);
$relimit = 100;
$class = new wordProcessor($word, $language);

// FP6
$contain_at_index = 1; // [E4]

?>

<?php
if (isset($_POST['search'])) {

    if (isset($_POST['language'])) {
        $language = $_POST['language'];
    }

    if (isset($_POST['option'])) {
        $option = $_POST['option'];
    }

    // Check the appropriate search string length.
    if ((isset($_POST['search_string_length']))) {
        switch ($_POST['search_string_length']) {
            case "exactly":
                if (isset($_POST['exact_length'])) {
                    $length_equals = $_POST['exact_length'];
                }
                break;
            case "between":
                if (isset($_POST['between_length_a'])) {
                    $length_between_from = $_POST['between_length_a'];
                }
                if (isset($_POST['between_length_b'])) {
                    $length_between_to = $_POST['between_length_b'];
                }
                break;
        }
    }

    // E4
    if (isset($_POST['contain_at_index'])) {
        $contain_at_index = $_POST['contain_at_index'];
    }

    // IMPORTANT: User search string
    $user_search_string = stripslashes($_POST['search-bar']);
    $user_search_string = preg_replace('/^[\s]+$/', '', $user_search_string);
    $user_search_string = explode(" ", $user_search_string);
    $user_search_string = implode($user_search_string);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Styles -->
    <link href="css/search_style.css" rel="stylesheet" type="text/css" />

    <!-- Bootstrap -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" id="searchForm" autocomplete="off">
                <!-- CRITERIA -->
                <div class="col-lg-2 col-sm-3 col-xs-2 filter" style="margin-top:-8px; margin-left:-5px; padding-top:10px">
                    <!-- Header 1 -->
                    <p class="searchHeader">Constraints</p>

                    <?php

                    // ============== Variables ==============
                    $checked = "checked='checked'";
                    $checkA1 = null;
                    $checkA2 = null;

                    $checkB1 = null;
                    $checkB2 = null;
                    $checkB3 = null;

                    $checkC = null;
                    $checkC1 = null;
                    $checkC2 = null;
                    $checkC3 = null;
                    $checkC4 = null;
                    $checkC5 = null;
                    $checkC6 = null;

                    $checkD = null;
                    $checkD1 = null;
                    $checkD2 = null;
                    $checkD3 = null;
                    $checkD4 = null;
                    $checkD5 = null;
                    $checkD6 = null;

                    $checkE = null;
                    $checkE1 = null;
                    $checkE2 = null;
                    $checkE3 = null;
                    $checkE4 = null;
                    $checkE5 = null;
                    $checkE6 = null;

                    $checkF = null;
                    $checkF1 = null;
                    $checkF2 = null;
                    $checkF3 = null;
                    $checkF4 = null;

                    // Check the appropriate language.
                    if ((isset($_POST['language']))) {
                        switch ($_POST['language']) {
                            case "telugu":
                                $checkA1 = $checked;
                                break;
                            case "english":
                                $checkA2 = $checked;
                                break;
                        }
                    }

                    // Check the appropriate search string length.
                    if ((isset($_POST['search_string_length']))) {
                        switch ($_POST['search_string_length']) {
                            case "any":
                                $checkB1 = $checked;
                                break;
                            case "exactly":
                                $checkB2 = $checked;
                                break;
                            case "between":
                                $checkB3 = $checked;
                                break;
                        }
                    }

                    // Check the option.
                    if ((isset($_POST['option']))) {
                        switch ($_POST['option']) {
                            case "C1":
                                $checkC1 = $checked;
                                break;
                            case "C2":
                                $checkC2 = $checked;
                                break;
                            case "C3":
                                $checkC3 = $checked;
                                break;
                            case "C4":
                                $checkC4 = $checked;
                                break;
                            case "C5":
                                $checkC5 = $checked;
                                break;
                            case "C6":
                                $checkC6 = $checked;
                                break;
                            case "D1":
                                $checkD1 = $checked;
                                break;
                            case "D2":
                                $checkD2 = $checked;
                                break;
                            case "D3":
                                $checkD3 = $checked;
                                break;
                            case "D4":
                                $checkD4 = $checked;
                                break;
                            case "D5":
                                $checkD5 = $checked;
                                break;
                            case "D6":
                                $checkD6 = $checked;
                                break;
                            case "E1":
                                $checkE1 = $checked;
                                break;
                            case "E2":
                                $checkE2 = $checked;
                                break;
                            case "E3":
                                $checkE3 = $checked;
                                break;
                            case "E4":
                                $checkE4 = $checked;
                                break;
                            case "E5":
                                $checkE5 = $checked;
                                break;
                            case "E6":
                                $checkE6 = $checked;
                                break;
                            case "F1":
                                $checkF1 = $checked;
                                break;
                            case "F2":
                                $checkF2 = $checked;
                                break;
                            case "F3":
                                $checkF3 = $checked;
                                break;
                            case "F4":
                                $checkF4 = $checked;
                                break;
                        }
                    }

                    // Check the appropriate category
                    if ((isset($_POST['category']))) {
                        switch ($_POST['category']) {
                            case "C":
                                $checkC = $checked;
                                break;
                            case "D":
                                $checkD = $checked;
                                break;
                            case "E":
                                $checkE = $checked;
                                break;
                            case "F":
                                $checkF = $checked;
                                break;
                        }
                    }

                    ?>

                    <!-- [A] -->
                    <label>[A] Language</label><br>
                    <input type="radio" style="margin-left:25px;" name="language" value="telugu" id="rad-1" checked <?php echo $checkA1; ?>> 1. Telugu<br>
                    <input type="radio" style="margin-left:25px;" name="language" value="english" id="rad-2" <?php echo $checkA2 ?>> 2. English<br><br>

                    <!-- [B] -->
                    <label>[B] Length</label><br>
                    <input type="radio" style="margin-left:25px;" name="search_string_length" value="any" id="rad-1" checked <?php echo $checkB1; ?>> 1. Any<br>
                    <input type="radio" style="margin-left:25px;" name="search_string_length" value="exactly" id="rad-2" <?php echo $checkB2; ?>> 2. Exactly [<input type="number" name="exact_length" value=<?php echo $length_equals ?> class="numericInput">] characters<br>
                    <input type="radio" style="margin-left:25px;" name="search_string_length" value="between" id="rad-3" <?php echo $checkB3; ?>> 3. Between [<input type="number" name="between_length_a" value=<?php echo $length_between_from ?> class="numericInput">] and [<input type="number" name="between_length_b" value=<?php echo $length_between_to ?> class="numericInput">] characters<br>

                    <!-- Header 2 -->
                    <p class="searchHeader" style="padding-top:10px;">Options</p>

                    <!-- [C] -->
                    <label>[C] Search by Fuzzy String</label><br>
                    <input type="radio" style="margin-left:25px;" name="option" value="C1" id="rad-1" checked <?php echo $checkC1 ?>> 1. Contains substring<br />
                    <input type="radio" style="margin-left:25px;" name="option" value="C2" id="rad-2" <?php echo $checkC2 ?>> 2. Contains substrings (any order)<br />
                    <input type="radio" style="margin-left:25px;" name="option" value="C3" id="rad-3" <?php echo $checkC3 ?>> 3. Contains substrings (given order)<br />
                    <input type="radio" style="margin-left:25px;" name="option" value="C4" id="rad-4" <?php echo $checkC4 ?>> 4. Full Word<br />
                    <input type="radio" style="margin-left:25px;" name="option" value="C5" id="rad-5" <?php echo $checkC5 ?>> 5. Prefix<br />
                    <input type="radio" style="margin-left:25px;" name="option" value="C6" id="rad-6" <?php echo $checkC6 ?>> 6. Suffix<br /><br>

                    <!-- [D] -->
                    <label>[D] Search by Exact String</label><br>                    
                    <input type="radio" style="margin-left:25px;" name="option" value="D1" id="rad-7" <?php echo $checkD1 ?>> 1. Contains substring<br />
                    <input type="radio" style="margin-left:25px;" name="option" value="D2" id="rad-8" <?php echo $checkD2 ?>> 2. Contains substrings (any order)<br />
                    <input type="radio" style="margin-left:25px;" name="option" value="D3" id="rad-9" <?php echo $checkD3 ?>> 3. Contains substrings (given order)<br />
                    <input type="radio" style="margin-left:25px;" name="option" value="D4" id="rad-10" <?php echo $checkD4 ?>> 4. Full Word<br />
                    <input type="radio" style="margin-left:25px;" name="option" value="D5" id="rad-11" <?php echo $checkD5 ?>> 5. Prefix<br />
                    <input type="radio" style="margin-left:25px;" name="option" value="D6" id="rad-12" <?php echo $checkD6 ?>> 6. Suffix<br /><br>

                    <!-- [E] -->
                    <label>[E] Search by Logical Character</label><br>                             
                    <input type="radio" style="margin-left:25px;" name="option" value="E1" id="rad-13" <?php echo $checkE1 ?>> 1. Contains character<br />
                    <input type="radio" style="margin-left:25px;" name="option" value="E2" id="rad-14" <?php echo $checkE2 ?>> 2. Contains characters (any order)<br />
                    <input type="radio" style="margin-left:25px;" name="option" value="E3" id="rad-15" <?php echo $checkE3 ?>> 3. Contains characters (given order)<br />
                    <input type="radio" style="margin-left:25px;" name="option" value="E4" id="rad-16" <?php echo $checkE4 ?>> 4. Character at position [<input type="number" name="contain_at_index" value=<?php echo $contain_at_index; ?> class="numericInput">]<br />
                    <input type="radio" style="margin-left:25px;" name="option" value="E5" id="rad-17" <?php echo $checkE5 ?>> 5. Prefix<br />
                    <input type="radio" style="margin-left:25px;" name="option" value="E6" id="rad-18" <?php echo $checkE6 ?>> 6. Suffix<br /><br>

                    <!-- [F] -->
                    <label>[F] Special Searches</label><br>                             
                    <input type="radio" style="margin-left:25px;" name="option" value="F1" id="rad-19" <?php echo $checkF1 ?>> 1. Consonants (given order)<br>
                    <input type="radio" style="margin-left:25px;" name="option" value="F2" id="rad-20" <?php echo $checkF2 ?>> 2. Consonants in (any order)<br>
                    <input type="radio" style="margin-left:25px;" name="option" value="F3" id="rad-21" <?php echo $checkF3 ?>> 3. Vowels (given order)<br>
                    <input type="radio" style="margin-left:25px;" name="option" value="F4" id="rad-22" <?php echo $checkF4 ?>> 4. Vowels (any order)<br><br>

                </div>

                <!-- NOTE: search bar and submit button are inside the RESULTS div -->
            </form>

            <!-- RESULTS -->
            <div class="col-lg-10 col-sm-9 col-xs-10" style="margin-top: 30px">

                <!-- Search bar -->
                <div class="container-fluid">
                    <div class="col-xs-3"></div>
                    <div class="row col-xs-6" style="align-content:center; margin: 0 auto;">
                        <!-- SEARCH Bar -->
                        <div class="col-sm-10 col-md-11" style="padding-right: 0px;">
                            <input type="text" form="searchForm" id="search-bar" name="search-bar" placeholder="Enter Search Criteria" <?php if (isset($_POST['search-bar'])) {
                                                                                                                                            echo "value='" . $_POST['search-bar'] . "'";
                                                                                                                                        } ?>>
                        </div>
                        <!-- SUBMIT Button -->
                        <div class="col-sm-2 col-md-1" style="padding-left:2px; max-width:70px">
                            <button type="submit" form="searchForm" id="search-button" value="search" name="search">
                                <img src="images/search.png" alt="Search Icon" height="42" width="42">
                            </button>
                        </div>
                    </div>

                    <!-- Spacer -->
                    <div style="height:75px"></div>

                    <!-- Bring in jquery table support -->
                    <?php require "data_table_support.php"; ?>

                    <!-- Results Table -->
                    <div id="wrap">
                        <div class="container-fluid">

                            <table id="myDataTable" cellpadding="0" cellspacing="0" class="display table table-striped table-bordered" width="100%">
                                <thead>
                                    <tr>
                                        <!-- Setting up columns -->
                                        <?php
                                        if (isset($col1)) {
                                            echo "<th>$col1</th>";
                                        }
                                        if (isset($col2)) {
                                            echo "<th>$col2</th>";
                                        }
                                        if (isset($col3)) {
                                            echo "<th>$col3</th>";
                                        }
                                        if (isset($col4)) {
                                            echo "<th>$col4</th>";
                                        }
                                        if (isset($col5)) {
                                            echo "<th>$col5</th>";
                                        }
                                        ?>

                                    </tr>

                                </thead>
                                <tbody>

                                    <!-- Processing User Request -->
                                    <?php

                                    // Connect to DB
                                    $dbcn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
                                    $dbcn->set_charset("utf8");
                                    if (mysqli_connect_errno()) {
                                        echo "<p>Error creating database connection.</p>";
                                        exit;
                                    }

                                    // Default min and max to 'any'.
                                    $min_letter = 0;
                                    $max_letter = 1000;

                                    // Change min and max if the user has specified a length (grouping B)
                                    if ((isset($_POST['search_string_length']))) {
                                        switch ($_POST['search_string_length']) {
                                                // [B2]
                                            case "exactly":
                                                if (isset($_POST['exact_length'])) {
                                                    $min_letter = $length_equals;
                                                    $max_letter = $length_equals;
                                                }
                                                break;
                                                // [B3]
                                            case "between":
                                                if (isset($_POST['between_length_a'])) {
                                                    $min_letter = $length_between_from;
                                                }
                                                if (isset($_POST['between_length_b'])) {
                                                    $max_letter = $length_between_to;
                                                }
                                                break;
                                        }
                                    }

                                    // Generate SQL
                                    $sql = "";
                                    if ($language == "english") {
                                        $sql = "SELECT * FROM english AS E WHERE E.char_len >= $min_letter AND E.char_len <= $max_letter";
                                    } else {
                                        $sql = "SELECT * FROM telugu AS T WHERE T.char_len >= $min_letter AND T.char_len <= $max_letter";
                                    }

                                    // Run query, retrieving unfiltered set of words.
                                    $result = $dbcn->query($sql);
                                    if (!$result) {
                                        echo ("<p>Unable to query database at this time.</p>");
                                        exit;
                                    }

                                    // How many rows were retrieved?
                                    $numRows = $result->num_rows;

                                    // If any were found...
                                    if ($user_search_string != "" && $numRows > 0) {
                                        // Loop over each row.
                                        for ($i = 0; $i < $numRows; $i++) {
                                            $row = $result->fetch_array();

                                            // Determine what category of search is being requested. 
                                            // Possible results -> "C", "D", "E", or "F"
                                            $category = substr($_POST['option'], 0, 1);

                                            // For some categories, the two languages can use the same
                                            // filter functions. For others, they must be different.
                                            switch ($category) {
                                                case "C":
                                                    // Same
                                                    break;
                                                case "D":
                                                    // Different
                                                    break;
                                                    // Search by Logical Character
                                                case "E":
                                                    switch ($language) {
                                                        case "telugu":
                                                            teluguLogicalCharacterSearch($row, $user_search_string, $_POST['option']);
                                                            break;
                                                        case "english":
                                                            englishSearch($row, $user_search_string, $_POST['option']);
                                                            break;
                                                        default:
                                                            echo "Invalid language :{";
                                                            break;
                                                    }
                                                    break;
                                                case "F":
                                                    // Different
                                                    break;
                                            }
                                        }
                                    }

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<!-- PHP functions -->
<?php

// Prints a row in the results table.
function printRow($row)
{
    $id = $row[0];
    $word = $row['word'];
    $length = $row['char_len'];
    $strength = $row['strength'];
    $weight = $row['weight'];

    print "
        <tr>
            <td >$id</td>
            <td >$word</td>
            <td >$length</td>
            <td >$strength</td>
            <td >$weight</td>
        </tr>";
}

// Handles Logical Characters Search [E] for Telugu language.
function teluguLogicalCharacterSearch($row, $user_search_string, $option)
{
    // Telugu words must be parsed into logical characters
    // before this search can be run.
    $word = parseToLogicalCharacters($row['word']);
    $user_search_string = parseToLogicalCharacters($user_search_string);

    switch ($option) {
            // Contains character
        case "E1":
            for ($i = 0; $i < count($word); $i++) {
                // If any character in the $word matches $user_search_string[0]...
                if ($word[$i] == $user_search_string[0]) {
                    //... the word is a match.
                    printRow($row);
                    return;
                }
            }
            break;
            // Contains characters in any order
        case "E2":
            $matchFound = true;
            // For each character in the $user_search_string...
            for ($i = 0; $i < count($user_search_string); $i++) {

                // ... check every character in the $word, searching for matches
                for ($j = 0; $j < count($word); $j++) {
                    if ($user_search_string[$i] == $word[$j]) {
                        // Match found. Continue...
                        $matchFound = true;
                        break;
                    }
                    $matchFound = false;
                }

                // If the character in $user_search_string at index $i was not found in the $word,
                // the criteria has not been met. Bail!
                if ($matchFound == false) {
                    return;
                }
            }
            // If we make it this far, then it is a match.
            printRow($row);
            break;
            // Contains characters in the given order
        case "E3":
            $matchFound = true;
            $previousIndex = 0;
            // For each character in the $user_search_string...
            for ($i = 0; $i < count($user_search_string); $i++) {

                // ... check every character in the $word, searching for matches
                for ($j = $previousIndex; $j < count($word); $j++) {
                    if ($user_search_string[$i] == $word[$j]) {
                        // Match found. Continue...   
                        $previousIndex = $j;
                        $matchFound = true;
                        break;
                    }
                    $matchFound = false;
                }

                // If the character in $user_search_string at index $i was not found in the $word,
                // the criteria has not been met. Bail!
                if ($matchFound == false) {
                    return;
                }
            }
            // If we make it this far, then it is a match.
            printRow($row);
            break;
            // Contains character at given index (uses $contain_at_index)
        case "E4":
            // Get global variable $contain_at_index (direct from user input).
            global $contain_at_index;

            // Invalid input -> No results
            if ($contain_at_index < 1) return;

            // If the current word does not have a value for the given index, bail.
            if (count($word) <= $contain_at_index) return;

            // If the criteria is met...
            if ($word[$contain_at_index - 1] == $user_search_string[0]) {
                //... print.
                printRow($row);
            }
            break;
            // Prefix
        case "E5":
            // For each prefix character...
            for ($i = 0; $i < count($user_search_string); $i++) {
                //... compare against characters in the word from the DB.
                if ($word[$i] != $user_search_string[$i]) {
                    // No match. break.
                    return;
                }
            }
            // If we make it this far, then it is a match.
            printRow($row);
            break;
            // Suffix
        case "E6":
            for ($i = 1; $i <= count($user_search_string); $i++) {
                //... compare against characters in the word from the DB.
                if ($word[count($word) - $i] != $user_search_string[count($user_search_string) - $i]) {
                    // No match. break.
                    return;
                }
            }
            // If we make it this far, then it is a match.
            printRow($row);
            break;
    };
}

// Handles searches in English
function englishSearch($row, $user_search_string, $option)
{
    // Get the word.
    $word = $row['word'];

    // Different cases.
    switch ($option) {
            // Contains character
        case "E1":
            for ($i = 0; $i < strlen($word); $i++) {
                // If any character in the $word matches $user_search_string[0]...
                if ($word[$i] == $user_search_string[0]) {
                    //... the word is a match.
                    printRow($row);
                    return;
                }
            }
            break;
            // Contains characters (any order)
        case "E2":
            $matchFound = true;
            // For each character in the $user_search_string...
            for ($i = 0; $i < strlen($user_search_string); $i++) {

                // ... check every character in the $word, searching for matches
                for ($j = 0; $j < strlen($word); $j++) {
                    if ($user_search_string[$i] == $word[$j]) {
                        // Match found. Continue...
                        $matchFound = true;
                        break;
                    }
                    $matchFound = false;
                }

                // If the character in $user_search_string at index $i was not found in the $word,
                // the criteria has not been met. Bail!
                if ($matchFound == false) {
                    return;
                }
            }
            // If we make it this far, then it is a match.
            printRow($row);
            break;
            // Contains characters in the given order
        case "E3":
            $matchFound = true;
            $previousIndex = 0;
            // For each character in the $user_search_string...
            for ($i = 0; $i < strlen($user_search_string); $i++) {

                // ... check every character in the $word, searching for matches
                for ($j = $previousIndex; $j < strlen($word); $j++) {
                    if ($user_search_string[$i] == $word[$j]) {
                        // Match found. Continue...   
                        $previousIndex = $j;
                        $matchFound = true;
                        break;
                    }
                    $matchFound = false;
                }

                // If the character in $user_search_string at index $i was not found in the $word,
                // the criteria has not been met. Bail!
                if ($matchFound == false) {
                    return;
                }
            }
            // If we make it this far, then it is a match.
            printRow($row);
            break;
            // Contains character at given index (uses $contain_at_index)
        case "E4":
            // Get global variable $contain_at_index (direct from user input).
            global $contain_at_index;

            // Invalid input -> No results
            if ($contain_at_index < 1) return;

            // If the current word does not have a value for the given index, bail.
            if (strlen($word) <= $contain_at_index) return;

            // If the criteria is met...
            if ($word[$contain_at_index - 1] == $user_search_string[0]) {
                //... print.
                printRow($row);
            }
            break;
            // Prefix
        case "E5":
            // If the search string is longer than the word, it can't be a match.
            if (strlen($word) < strlen($user_search_string)) return;

            // For each prefix character...
            for ($i = 0; $i < strlen($user_search_string); $i++) {
                //... compare against characters in the word from the DB.
                if ($word[$i] != $user_search_string[$i]) {
                    // No match. break.
                    return;
                }
            }
            // If we make it this far, then it is a match.
            printRow($row);
            break;
            // Suffix
        case "E6":
            // If the search string is longer than the word, it can't be a match.
            if (strlen($word) < strlen($user_search_string)) return;

            // For each suffix character...
            for ($i = 1; $i <= strlen($user_search_string); $i++) {
                //... compare against characters in the word from the DB.
                if ($word[strlen($word) - $i] != $user_search_string[strlen($user_search_string) - $i]) {
                    // No match. break.
                    return;
                }
            }
            // If we make it this far, then it is a match.
            printRow($row);
            break;
    }
}

?>