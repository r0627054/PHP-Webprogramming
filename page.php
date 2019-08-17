<?php
/**
 * Created by PhpStorm.
 * User: Dries
 * Date: 22/10/2017
 * Time: 16:09
 */

/**
 * Class Page
 */
class Page
{
    protected $currentPage;
    protected $pageTitle;
    protected $pageCss;


    /**
     * Page constructor.
     * @param $currentPage the name of the page.
     * @param $pageTitle the title of the page.
     * @param $pageCss the page css file name.
     */
    public function __construct($currentPage, $pageTitle, $pageCss)
    {
        $this->currentPage = $currentPage;
        $this->pageTitle = $pageTitle;
        $this->pageCss =  $pageCss;
    }

    /**
     * Displays the head, with the correct information.
     */
    public function displayHead(){
        $head = "<head>
                    <title>" . $this->pageTitle . "</title>
                    <meta charset=\"UTF-8\">
                    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                    <meta name=\"description\" content=\"This a dynamic website made by Dries Janse for the module Web Programming 2.\">
                    <meta name=\"keywords\" content=\"Student,Dries Janse ,Webdesign\">
                    <meta name=\"author\" content=\"Dries Janse\">
                    <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\">
                    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/reset.css\">
                    <link rel=\"stylesheet\" type=\"text/css\" href=\"" . $this->pageCss . "\">
                    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css\">
                    <link href=\"https://fonts.googleapis.com/css?family=Lobster\" rel=\"stylesheet\">
                    <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js\"></script>
                    <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\"></script>";
                    if(($this->currentPage ==  "userOverview") || $this->currentPage == "addUser"){
                        $head .= "<script type=\"text/javascript\" src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js\"></script>";
                        $head .= "<script type=\"text/javascript\" src=\"https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/additional-methods.js\"></script>";
                    }
                    if($this->currentPage ==  "userOverview"){
                        $head .= "<script type=\"text/javascript\" src=\"js/detailDeleteUpdate.js\"></script>";
                        $head .= "<script type=\"text/javascript\" src=\"js/passwordReset.js\"></script>";
                        $head .= "<script type=\"text/javascript\" src=\"https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js\"></script>";
                        $head .= "<script type=\"text/javascript\" src=\"https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js\"></script>";
                    }
                    if($this->currentPage == "addUser" ){
                        $head .= "<script type=\"text/javascript\" src=\"js/addUserValidate.js\"></script>";
                    }
                    if($this->currentPage == "index"){
                        $head .= "<script src='https://www.google.com/recaptcha/api.js'></script>";
                    }
                    if($this->currentPage == "userProfilePage"){
                        $head .= "<script type=\"text/javascript\" src=\"js/updateUserHimself.js\"></script>";
                    }



        $head .= "</head>";
        echo $head;
    }

    /**
     * Displays the welcome animation, with the correct information.
     */
    public function displayWelcomeAnimation(){
        echo "<div class=\"jumbotron\" id=\"welcomeDries\">
                <div class=\"container text-center\">
                    <h1 >Welcome, " . $_SESSION["username"] . "!</h1>
                    <p>This is the user application of Dries Janse!</p>
                </div>
             </div>";
    }

    /**
     * Displays the footer, with the correct information.
     */
    public function displayFooter(){
        echo "<footer class=\"container-fluid text-center\" id=\"driesfooter\">
                    <p>This website was created by <a href=\"mailto:dries.janse@student.ucll.be?Subject=Beautiful%20webpage\" target=\"_top\">Dries Janse</a>. </p>
              </footer>";
    }
}

/**
 * Class UserPage
 */
class UserPage extends Page {

    /**
     * Displays the navigation for normal user profiles.
     */
    public function displayUserNavigation()
    {
        echo "<nav class=\"navbar navbar-inverse\">
                                <div class=\"container-fluid\">
                                    <div class=\"navbar-header\">
                                        <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\"#driesNavbar\">
                                            <span class=\"icon-bar\"></span>
                                            <span class=\"icon-bar\"></span>
                                            <span class=\"icon-bar\"></span>
                                        </button>
                                        <a class=\"navbar-brand\" href=\"userOverview.php\">Dries Webapp</a>
                                    </div>
                                    <div class=\"collapse navbar-collapse\" id=\"driesNavbar\">
                                        <ul class=\"nav navbar-nav navbar-right\">
                                            <li><a href=\"logout.php\"><span class=\"glyphicon glyphicon-log-out\"></span> Logout</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </nav>";
    }


}

/**
 * Class AdminPage
 */
class AdminPage extends Page {

    /**
     * Displays the navigation for a user with the administrator role.
     */
    public function displayAdminNavigation()
    {
        $adminNavigation = "<nav class=\"navbar navbar-inverse\">
                                <div class=\"container-fluid\">
                                    <div class=\"navbar-header\">
                                        <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\"#driesNavbar\">
                                            <span class=\"icon-bar\"></span>
                                            <span class=\"icon-bar\"></span>
                                            <span class=\"icon-bar\"></span>
                                        </button>
                                        <a class=\"navbar-brand\" href=\"userOverview.php\">Dries Webapp</a>
                                    </div>
                                    <div class=\"collapse navbar-collapse\" id=\"driesNavbar\">
                                        <ul class=\"nav navbar-nav\"><li "; if ($this->currentPage == "userOverview") {$adminNavigation .= " class=\"active\"";}
        $adminNavigation .= "><a href=\"userOverview.php\">Show users</a></li>
                                            <li ";if ($this->currentPage == "addUser") { $adminNavigation .= "class=\"active\""; }
        $adminNavigation .= "><a href=\"addUser.php\">Add user</a></li>
                                        </ul>
                                        <ul class=\"nav navbar-nav navbar-right\">
                                            <li><a href=\"logout.php\"><span class=\"glyphicon glyphicon-log-out\"></span> Logout</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </nav>";
        echo $adminNavigation;
    }

}