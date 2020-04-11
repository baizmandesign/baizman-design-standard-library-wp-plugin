<?php
/**
 * WordPress login screen styles.
 */

// Send header content-type declaration.
header ( 'Content-Type: text/css' ) ;

$primary_color = '#00205B' ;

?>
body {
    font-family: CentSchbookBTWGL4WXX-Roman, Georgia, serif ;
    font-style: normal ;
    font-weight: normal ;
    background-color: #fff ;
}

.login form {
    box-shadow: 0 2px 5px rgba(0,0,0,.23) ;
}

#login h1, .login h1 {
    display: none;
}

#login h2, .login h2 {
    font-family: CentSchbookBTWGL4WXX-Italic ;
    font-style: normal ;
    font-weight: normal ;
    font-size: 2em ;
    line-height: 1.3em;
}

#login h2 a, .login h2 a {
    color: <?php echo $primary_color ; ?> ;
    text-decoration: none ;
}

#login h2 a:hover, .login h2 a:hover {
    color: <?php echo $primary_color ; ?> ;
    border-bottom: 2px solid <?php echo $primary_color ; ?> ;
}
#login h3, .login h3 {
    margin-top: 10px ;
    font-weight: normal ;
}

h3, label, p {
    color: #393939 ;
}

/* Style login button. */
#wp-submit, #wp-submit:focus {
    background-color: <?php echo $primary_color ; ?> ;
    border-color: <?php echo $primary_color ; ?> ;
    /*text-shadow: 0 -1px 1px #006799, 1px 0 1px #006799, 0 1px 1px #006799, -1px 0 1px #006799 ;*/
    text-shadow: none ;
    box-shadow: 0 1px 0 <?php echo $primary_color ; ?> ;
}

.login #backtoblog a, .login #nav a {
    color: <?php echo $primary_color ; ?> ;
    padding-bottom: 1px ;
}

.login #backtoblog a:hover, .login #nav a:hover,
.login #backtoblog a:focus, .login #nav a:focus {
    color: <?php echo $primary_color ; ?> ;
    border-bottom: 1px solid <?php echo $primary_color ; ?> ;
}

/* Add space above "You are now logged out." message. */
.login #login_error, .login .message, .login .success {
    margin-top: 1em ;
}

#login .message {
    border-left: 4px solid <?php echo $primary_color ; ?> ;
    margin: 16px 0 ;
}