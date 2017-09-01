<?php

include_once 'includes/header.php';
include_once 'includes/navbar.php';
echo "<p>\nThe first script your should run when you install Jama is the TestMatrix.php script.\n</p>\n<p>\nThis will run the unit tests for methods in the <code>Matrix.php</code> class.  Because\nthe Matrix.php class can be used to invoke all the decomposition methods the <code>TestMatrix.php</code> \nscript is a test suite for the whole Jama package.\n</p>\n<p>\nThe original <code>TestMatrix.java</code> code uses try/catch error handling.  We will \neventually create a build of JAMA that will take advantage of PHP5's new try/catch error \nhandling capabilities.  This will improve our ability to replicate all the unit tests that \nappeared in the original (except for some print methods that may not be worth porting).\n</p>\n<p>\nYou can <a href='../test/TestMatrix.php'>run the TestMatrix.php script</a> to see what \nunit tests are currently implemented.  The source of the <code>TestMatrix.php</code> script \nis provided below.  It is worth studying carefully for an example of how to do matrix algebra\nprogramming with Jama.\n</p>\n";
highlight_file('../test/TestMatrix.php');
include_once 'includes/footer.php';

?>
