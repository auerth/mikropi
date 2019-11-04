<?php

class PageBuilder
{

    /**
     * Default Head Tag.
     *
     * Returns the Default HMLT Head Tag for every Webpage.
     *
     * @param type String title.
     * @param type String description
     * @param type ArrayOfStrings stylesheets DEFAULT empty.
     * @return type String author DEFAULT Thorben Auer.
     */
    public function  getHead($title, $description, $stylesheets = array(), $author = "Thorben Auer")
    {
        $headStart = "<head>";
        $google = "<!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src='https://www.googletagmanager.com/gtag/js?id=UA-150508341-1'></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'UA-150508341-1');
        </script>";
        $head = '
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="' . $description . '">
        <meta name="author" content="$author">
        ' . $google . '
        
        <title>' . $title . '</title>
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap/fontawesome/all.css">
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
     */
    public function  getFooter()
    {
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
     * @param type ArrayOfStrings jsFiles DEFAULT empty.
     */
    public function  getJsTags($jsFiles = array())
    {
        $tags = '
        <script src="js/bootstrap/jquery.js"></script>
        <script src="js/bootstrap/propper.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>';
        foreach ($jsFiles as &$link) {
            $tags = $tags . '<script src="' . $link . '"></script>';
        }
        return $tags;
    }



    /**
     * Default JS-Tags.
     *
     * Returns the Default HMLT JS Tags for every Webpage.
     * 
     * 
     */
    public function  getNavBar($loggedIn, $isAdmin)
    {
        $fileName =  basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
        $nav = ' <nav class="navbar navbar-expand-lg navbar-dark navbar-background static-top"><div class="container"><a class="navbar-brand" href="index.php?dash"><img src="../images/logo_white.png" height="40" width="120" alt="Logo"></a><button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button><div class="collapse navbar-collapse" id="navbarResponsive"><ul class="navbar-nav ml-auto">';

        if (!$loggedIn) {
            $activeHome = "";
            if ($fileName == "index.php") {
                $activeHome = "active";
            }
            $nav = $nav . ('<li class="nav-item ' . $activeHome . '">
                                        <a class="nav-link" href="index.php">Home</a>
                                        </li>');
        } else {
            $activSchnitt = "";
            $activeDashboard = "";
            $activeModuls = "";
            $activeDashboard = "";
            if ($fileName == "index.php") {
                if (isset($_GET["cuts"])) {
                    $activSchnitt = "active";
                }
                if (isset($_GET["dash"])) {
                    $activeDashboard = "active";
                }
            }
            if ($fileName == "moduls.php") {
                $activeModuls = "active";
            }
            $nav = $nav . ('
					            <li class="nav-item ' . $activeDashboard . '">
								    <a class="nav-link" href="index.php?dash">Dashboard</a>
							    </li>
					            <li class="nav-item ' . $activSchnitt . '">
								    <a class="nav-link" href="index.php?cuts">Schnitte</a>
							    </li>
                                <li class="nav-item ' . $activeModuls . '">
                                    <a class="nav-link" href="moduls.php">Module</a>
							    </li>');
        }
        $nav = $nav . '</ul>
                             </div>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">';
        if (!$loggedIn) {
            $activeLogin = "";
            if ($fileName == "login.php") {
                $activeLogin = "active";
            }
            $nav = $nav . ('<li class="nav-item ' . $activeLogin . '">
                                        <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                                        </li>');
        } else {

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
            $accountItems .= '<a class="dropdown-item ' . $activeReport . '" href="report.php"><i class="fas fa-bug"></i> Bug Report</a>';
            $accountItems .= '<a class="dropdown-item" href="https://www.surveymonkey.de/r/R8L8LDL" target="_blank"><i class="fas fa-comments"></i> Feedback</a>';

            $nav = $nav . (' <li class="nav-item ' . $activeReport . $activeSettings . $activeAdmin . $activeTutorials .' dropdown" >
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
									<i class="fas fa-user-alt"></i> Account
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
								  ' . $accountItems . '
                                </div>
                                
      </li>');
        }
        $nav = $nav . '
                    <li class="nav-item">';
        if (!$loggedIn) {
            $activeRegister = "";
            if ($fileName == "register.php") {
                $activeRegister = "active";
            }
            $nav = $nav . ('<a class="nav-link ' . $activeRegister . '" href="register.php"><i class="far fa-address-card"></i> Registrieren</a>');
        } else {
            $nav = $nav . ('<a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>');
        }
        $nav = $nav . '
                    </li>
                </ul>
            </div>
        </div>
    </nav>';
        return $nav;
    }
}
