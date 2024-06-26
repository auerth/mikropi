<?php

/**
 * The PageBuilder Class - Get head, footer and script tags for every page
 * @author     Thorben Auer
 * @link       https://softwelop.com
 */
class PageBuilder
{
    const version = "0.0.1";

    /**
     * Default Head Tag.
     *
     * Returns the Default HMLT Head Tag for every Webpage.
     *
     * @param string  $title
     * @param string  $description
     * @param array  $stylesheets DEFAULT empty
     * @return string 
     */
    public function  getHead($title, $description, $stylesheets = array(), $author = "Thorben Auer")
    {
        $headStart = "<head>";

        $head = '
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="' . $description . '">
        <meta name="author" content="' . $author . '">    
        <meta name="version" content="' . self::version . '">    
        <title>' . $title . '</title>
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="libs/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="libs/fontawesome/css/all.css">
        <link rel="stylesheet" href="../css/default.css">
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
        <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    ';
        foreach ($stylesheets as $link) {
            $head = $head . '<link rel="stylesheet" href="' . $link . '">';
        }

        $headEnd = "</head>";
        return $headStart . $head . $headEnd;
    }



    /**
     * Default Footer Tag.
     *
     * Returns the Default HMLT Footer Tag for every Webpage.
     * @return string 
     */
    public function  getFooter()
    {
        return "";
        $footerStart = "<footer>";

        $footer = '
        <div class="row">
        <div class="footer-left">
        <div class="row">
				<img src="../images/logo_white.png" 
					alt="Logo">
				<p> 
					| <a href="impressum.php">Impressum</a> | <a href="nutzerbedingung.php">Datenschutz / Nutzungsbedingungen</a> |
                </p> 
                </div>
			</div>
				<div class="footer_right">
			<a href="https://www.uniklinik-freiburg.de/de.html">© Copyright 2019 Universitätsklinikum Freiburg - All
                Rights Reserved</a>
        	</div>
		</div>
	';

        $footerEnd = "</footer>";
        return $footerStart . $footer . $footerEnd;
    }


    /**
     * Default JS-Tags.
     *
     * Returns the Default HMLT JS Tags for every Webpage.
     * 
     * @param array  $jsFiles DEFAULT empty.
     * @return string 
     */
    public function  getJsTags($jsFiles = array())
    {

        $tags = '
        <script src="libs/jquery/jquery.min.js"></script>
        <script src="libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>

       ';

        foreach ($jsFiles as &$link) {
            $tags = $tags . '<script src="' . $link . '"></script>';
        }
        return $tags;
    }



    /**
     * Returns HTML for Navbar.
     *
     * Returns Navbar depending on logged in and isAdmin.
     * 
     * @param bool  loggedIn
     * @param bool  isAdmin
     * @return string 
     */
    public function  getNavBar($loggedIn, $isAdmin)
    {

        $fileName =  basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);

        $nav = ' <nav class="navbar navbar-expand-lg navbar-dark navbar-background static-top"><a class="navbar-brand" href="index.php"><img src="../images/logo_white.png" height="40" width="120" class="logo" alt="Logo"></a><button class="navbar-toggler" type="button" tabIndex="-1" data-toggle="collapse" data-target=".navbar-collapse" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button><div class="collapse navbar-collapse" id="navbarResponsive"><ul class="navbar-nav ml-auto" style="width: 100% !important;">';

        if ($loggedIn) {
            $activSchnitt = "";
            $activeModuls = "";
            if ($fileName == "index.php") {
                $activSchnitt = "active";
            }
            if ($fileName == "cut.php") {
                $activSchnitt = "active";
            }
            if ($fileName == "moduls.php") {
                $activeModuls = "active";
            }
            $nav = $nav . ('
					           
					            <li class="nav-item ' . $activSchnitt . '">
								    <a class="nav-link" href="index.php">Schnitte</a>
							    </li>
                                <li class="nav-item ' . $activeModuls . '">
                                    <a class="nav-link" href="moduls.php">Module</a>
							    </li>');
        }
        $nav = $nav . '</ul>
                             </div>
            <div class="collapse navbar-collapse right">
                <ul class="navbar-nav ml-auto">';
        if ($loggedIn) {


            $activeSettings = "";
            $activeAdmin = "";
            $activeTutorials = "";
            $activeReport = "";
            if ($fileName == "settings.php") {
                $activeSettings = "active";
            } else if ($fileName == "admin.php") {
                $activeAdmin = "active";
            } else if ($fileName == "tutorial.php") {
                $activeTutorials = "active";
            } else if ($fileName == "report.php") {
                $activeReport = "active";
            }
            $accountItems = '<a class="dropdown-item ' . $activeSettings . '" href="settings.php"><i class="fas fa-cog"></i> Einstellungen</a>';
            if ($isAdmin) {
                $accountItems = $accountItems . '<a class="dropdown-item ' . $activeAdmin . '" href="admin.php"><i class="fas fa-unlock-alt"></i> Admin</a><a class="dropdown-item ' . $activeTutorials . '" href="tutorial.php"><i class="fas fa-book"></i> Tutorials</a>';
            }

            $nav = $nav . (' <li class="nav-item ' . $activeReport . $activeSettings . $activeAdmin . $activeTutorials . ' dropdown" >
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
									<i class="fas fa-user-alt"></i> Account
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
								  ' . $accountItems . '
                                </div>
                                
      </li>');
        }
        $nav = $nav . '
                    <li class="nav-item">';
        if ($loggedIn) {
            $nav = $nav . ('<a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>');
        }
        $nav = $nav . '
                    </li>
                </ul>
        </div>
    </nav>';
        return $nav;
    }
}
