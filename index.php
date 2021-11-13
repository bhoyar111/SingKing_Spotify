<?php
    session_start();
    error_reporting(0);
    require_once 'config.php';
    require_once 'code.php';

    $tableData  = [];
    $totalPages = 0;

    $vsong      = 'Song';
    $vartist    = 'Artist Name';
    $vimage     = 'assets/img/img_singking.jpg';

    if( empty($_SESSION['user']) ) {
        getAccessAndRefreshToken($credentials);
    }

    if( !empty($_GET) && (!empty($_GET['title']) || !empty($_GET['artist'])) ){
        $tableData = searchUserQuery();
    }
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <script src="js/moment-with-locales.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#000000">
    <meta name="msapplication-navbutton-color" content="#000000">
    <meta name="apple-mobile-web-app-status-bar-style" content="#000000">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/icon.css">
    <link rel="stylesheet" href="assets/fonts/fontstylesheet.css">
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon"> 
    <title>Sing King</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg fixed-top navbar-light bg-white" aria-label="Main navigation">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="assets/img/logo.png" class="img-fluid" alt="">
                </a>
                <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>

                <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item me-0 me-sm-0 me-md-3">
                            <a class="nav-link active btn btn-dark rounded-pill" aria-current="page" href="#requestTrackId">request your track</a>
                        </li>                        
                        <li class="nav-item">
                            <a class="nav-link" href="#"><span class="icon-instagram"></span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><span class="icon-facebook"></span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><span class="icon-youtube"></span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- for search filter section -->
    <section class="hero-setion" id="requestTrackId">
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="assets/img/Banner.png" class="d-block w-100" alt="...">
                    <div class="carousel-caption pt-0 pb-4 pb-sm-4 pb-md-5">
                        <div class="container">
                            <form method="GET" autocomplete="off" id="searchFormId">
                                <input type="hidden" name="offset" value="0" id="offsetInputId">                                             
                                <input type="hidden" name="activepage" value="<?php echo isset($_GET['activepage']) ? $_GET['activepage'] : '1'; ?>" id="activePageInputId"> 
                                <div class="row d-flex justify-content-center">
                                    <div class="col-12 col-sm-12 col-md-10">
                                        <h2 class="mb-1 mb-sm-2 mb-md-4 mb-lg-4">request your track</h2>
                                        <div class="input-group request-fominput">
                                            <input type="text" onkeyup="resetPageNo();" name="artist" value="<?php echo isset($_GET['artist']) ? $_GET['artist'] : ''; ?>" class="form-control border-0" placeholder="Artist">
                                            <span>|</span>
                                            <input type="text" onkeyup="resetPageNo();" name="title" value="<?php echo isset($_GET['title']) ? $_GET['title'] : ''; ?>" class="form-control border-0 ms-2" placeholder="Title">
                                            <button class="btn btn-dark rounded-pill px-2 px-sm-2 px-md-4 ms-1"
                                                type="submit" onclick='showListing()' value="search"><span class="icon-search_icn"></span> Search </input>
                                        </div>
                                    </div> 
                                </div>
                                <?php 
                                    if (($_GET['title'])) {
                                       echo $tableData['tracks']['href'];
                                    } else {
                                       echo $tableData['artists']['href'];
                                    } 
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- For listing show as per search filter -->
    <section id="albumListSectionId" class="alubum-list pt-4 pt-sm-4 pt-md-0">
        <div class="container">
            <form method="POST" autocomplete="off">
                <div class="row d-flex justify-content-center">
                    <div class="col-12">
                        <div class="artist-tags row d-flex justify-content-center">
                            <div class="col-6 col-sm-6 col-md-3 pe-1 pe-sm-2 pe-md-3">
                                <p class="mb-3 mb-sm-3 mb-md-5"><span>Artist : </span>
                                    <?php echo isset($_GET['artist']) ? $_GET['artist'] : ''; ?>
                                </p>
                            </div>
                            <div class="col-6 col-sm-6 col-md-3 ps-1 ps-sm-2 ps-md-3">
                                <p class="mb-3 mb-sm-3 mb-md-5"><span>Title : </span>
                                    <?php echo isset($_GET['title']) ? $_GET['title'] : ''; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-10">
                        <div class="table-responsive">
                            <form method="POST" autocomplete="off">
                                <table class="table table-hover table-borderless align-middle">
                                    <thead class="border-bottom">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Album Name</th>
                                            <th scope="col">Vote</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if( !empty($tableData['tracks']) ) {
                                            $tTotal = $tableData['tracks']['total'];
                                            $currentDisplayLimit = 10;                                           
                                            $totalPages = ceil($tTotal/$currentDisplayLimit);                                            
                                            if( $totalPages > 100 ) {
                                                $totalPages = 100;
                                            }
                                            $pageNo = isset($_GET['activepage']) ? $_GET['activepage'] : 1; 
                                            foreach ($tableData['tracks']['items'] as $tkey => $titem) { ?>                                       
                                        <tr>
                                            <th scope="row">
                                                <span class="row-count">
                                                    <?php
                                                        $newSrNo = (($pageNo - 1) * $currentDisplayLimit) + ($tkey + 1);
                                                        echo $newSrNo; 
                                                    ?>
                                                </span>
                                                <button type="button" class="btn btn-link text-decoration-none play-btn p-0">
                                                    <i class="fa fa-play" aria-hidden="true"></i>
                                                </button>    
                                            </th>
                                            <td>
                                                <?php 
                                                    $song     = str_replace('\'', '',$titem['name']);
                                                    $spotifyId = $titem['id'];
                                                    // $itemName = ($titem["artists"][0]['name']);
                                                    $itemName = "";
                                                    if( !empty($titem["artists"]) ) {
                                                        foreach ($titem["artists"] as $arkey => $artistValue) {
                                                            $itemName .= $artistValue['name'] . ', ';
                                                        }
                                                        $itemName = rtrim($itemName, ', ');
                                                    }
                                                    
                                                    $imageObj = end($titem["album"]['images']);  
                                                    $trackName = str_replace('\'', '',$titem["album"]["name"]);
                                                    $itemArray = [
                                                        "song" => $song,
                                                        "id" => $spotifyId, 
                                                        "name" => $itemName,
                                                        "image" => $imageObj,
                                                        "trackName" => $trackName,
                                                    ];                               
                                                ?>
                                                <div class="d-flex song-info">
                                                    <div class="flex-shrink-0">
                                                        <?php
                                                        if(!empty($imageObj['url'])){
                                                        ?>
                                                        <img class="artist-avtar" src="<?php echo $imageObj['url']; ?>" alt="<?php echo $song; ?>">
                                                        <?php
                                                        }else{
                                                        ?>
                                                        <img class="artist-avtar" src="assets/img/img_singking.jpg" alt="<?php echo $song; ?>">
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h3 class="song-name"><?php echo $song; ?></h3>
                                                        <p class="artist-name mb-0"><?php echo $itemName; ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo $titem["album"]["name"]; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-dark rounded-pill" type="button" onclick='fillRequestForm(<?php echo json_encode($itemArray); ?>);'>Vote</button>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                        <div>
                            <input type="hidden" id="totalPageCount" value="<?php echo $totalPages; ?>">
                            <nav aria-label="Page navigation" class="text-center">
                                <ul class="pagination list-inline d-inline-block">
                                    <li class="page-item list-inline-item">
                                        <a class="page-link" onclick="navSearchPagination('minus')" href="javascript:void(0);" aria-label="Previous">                                       
                                            <span class="icon-left-arrow"></span>
                                        </a>
                                    </li>                                    
                                    <?php 
                                        for ($i=0; $i < $totalPages; $i++) {                                             
                                            $activeClass = '';
                                            if($i == 0 && !isset($_GET['activepage'])) {
                                                $activeClass = 'active';
                                            }
                                            if( isset($_GET['activepage']) && $_GET['activepage'] == ($i + 1) ){
                                                $activeClass = 'active';
                                            }
                                    ?>
                                        <li class="page-item list-inline-item <?php echo $activeClass; ?>">                                    
                                            <a class="page-link" href="javascript:void(0);" onclick="searchPagination(<?php echo  $i; ?>);"><?php echo $i + 1; ?></a>
                                        </li> 
                                    <?php } ?>
                                    <li class="page-item list-inline-item">
                                        <a class="page-link" onclick="navSearchPagination('plus')" href="javascript:void(0);" aria-label="Next">                                       
                                            <span class="icon-right-arrow-angle"></span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- For Request form as per search filter -->
    <section class="request-form" id="divSubmitRequestId">
        <div class="container">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST" autocomplete="off" name="google-sheet">
                <div class="row pb-3 pb-sm-3 pb-md-5">
                    <div class="col-12 px-2 px-sm-3 px-md-5">
                        <div class="shadow pb-4 pb-sm-4 pb-md-5 mx-0 mx-sm-0 mx-md-5 mt-0 mt-sm-3 mt-md-4 rounded">
                            <div class="card signrcard mb-0 mb-sm-3 mb-md-5 p-2 p-sm-3 p-ms-3 border-0">
                                <div class="d-flex justify-content-start">
                                        <img id="vimage" src="assets/img/img_singking.jpg" class="img-fluid singer-avtar" alt="...">
                                        <div class="card-body py-0 py-sm-0 py-md-3 ">
                                            <h5 class="card-title" id="vsong"><?php echo $vsong; ?></h5>
                                            <p class="card-text" id="vartist">
                                            <?php echo $vartist; ?>
                                            </p>
                                        </div>
                                </div>
                            </div>
                            <div class="re-form-contain row m-0 d-flex justify-content-center">
                                <div class="col-12 col-sm-12 col-md-8">
                                    <h2 class="text-center my-3 my-sm-3 my-md-4">Submit your Request</h2>
                                </div>
                                <div class="col-12 col-sm-12 col-md-8">
                                    <label class="form-label">spotify ID</label>
                                    <input type="text" id="spotifyId" name="spotify_id" class="form-control mb-2 mb-sm-3 mb-md-3">
                                </div>
                                <div class="col-12 col-sm-12 col-md-8">
                                    <label class="form-label">album</label>
                                    <input type="text" id="albumNameId" name="album" class="form-control mb-2 mb-sm-3 mb-md-3">
                                </div>
                                <div class="col-12 col-sm-12 col-md-8">
                                    <label class="form-label">artist</label>
                                    <input type="text" id="artistNameId" name="artist" class="form-control mb-2 mb-sm-3 mb-md-3">
                                </div>
                                <div class="col-12 col-sm-12 col-md-8">
                                    <label class="form-label">title</label>
                                    <input type="text" id="arTitleId" name="title" class="form-control mb-2 mb-sm-3 mb-md-3">
                                </div>
                                <div class="col-12 col-sm-12 col-md-8">
                                    <label class="form-label">name</label>
                                    <input type="text" name="name" class="form-control mb-2 mb-sm-3 mb-md-3" required>
                                </div>
                                <div class="col-12 col-sm-12 col-md-8">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email_id" class="form-control mb-2 mb-sm-3 mb-md-3" required>
                                    <input type="hidden" id="dateId" name="date" value="<?php echo Date('d-m-y h:m:s'); ?>">
                                    <input type="hidden" id="refrenceId" name="reference_id" value="">
                                </div>
                                <div class="col-12 col-sm-12 col-md-8">
                                    <div class="form-check">
                                        <input class="form-check-input" name="agree" type="checkbox" value="yes" id="agree" required>
                                        <label class="form-check-label" for="agree">
                                            I accept all term and conditions
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-8 text-center">
                                    <button type="submit" class="mainbtn btn w-50 w-sm-50 w-md-25 rounded-pill px-3 mt-4">Submit</button>
                                    <button type="button" class="d-none" data-bs-toggle="modal" data-bs-target="#staticBackdrop" id="openModelBtnId">ShowModal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- For footer section -->
    <footer class="py-2 py-sm-3 py-md-4 mb-2">
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-12 col-sm-12 col-md-5">
                    <p class="mt-3 d-none d-sm-none d-md-block">Sing King 2021 | All Rights Reserved</p>
                </div>
                <div class="col-12 col-sm-12 col-md-2 text-center py-5 py-sm-5 py-md-0">
                    <button onclick="topFunction()" id="GoToTopBtn"><i class="fa fa-arrow-up"
                            aria-hidden="true"></i></button>
                </div>
                <div class="col-12 col-sm-12 col-md-5 footlinks text-center text-sm-center text-md-end">
                    <ul class="nav justify-content-center justify-content-sm-center justify-content-md-end">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Privacy Policy</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Terms</a>
                        </li>
                    </ul>
                    <p class="mt-3 d-block d-sm-block d-md-none">Sing King 2021 | All Rights Reserved</p>
                </div>
            </div>
        </div>
    </footer>
    <!-- Modal -->
    <div class="modal subscribemodal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img src="assets/img/rightpop.png" class="img-fluid w-50 mb-3" alt="">
                    <h2>Thank for submitting </h2>
                    <p>We got your request for the track-<span id="pSong"><?php echo $pSong;?></span>-We will make its track and get back to you.</p>
                    <div>
                        <p class="sociap m-0">Boost your Request/Vote on Twitter/ Facebook</p>
                    </div>
                </div>
                <div class="modal-footer border-0 d-flex justify-content-center">
                    <button type="button" class="mainbtn btn w-50 w-sm-50 w-md-25 rounded-pill px-3" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>        
        const scriptURL = `https://script.google.com/macros/s/AKfycbztM2x35ybydtdEvDuxH6EhN3V3cP7sDQNpDXlytJEiHWbyt7q2/exec`
        const form = document.forms['google-sheet']
        form.addEventListener('submit', e => {
            e.preventDefault()
            fetch(scriptURL, { method: 'POST', body: new FormData(form)})
            
            .then(response => {
                form.reset();
                document.getElementById("openModelBtnId").click();
            })
            .catch(error => console.error('Error!', error.message))
        });

        var dispLimit = 10;

        //Get the button
        // var mybutton = document.getElementById("myBtn");

        // When the user scrolls down 20px from the top of the document, show the button
        // window.onscroll = function () { scrollFunction() };

        // function scrollFunction() {
        //     if (document.body.scrollTop > 1 || document.documentElement.scrollTop > 20) {
        //         mybutton.fadeIn().removeClass('d-none');
        //     } else {
        //         mybutton.fadeOut().addClass('d-none');
        //     }
        // }

        $( document ).ready(function() {
            var active = $('ul.pagination').find('li.active');
            if(active.length){
                var left = active.position().left;
                var currScroll= $("ul.pagination").scrollLeft(); 
                var contWidth = $('ul.pagination').width()/2; 
                var activeOuterWidth = active.outerWidth()/2; 
                left= left + currScroll - contWidth + activeOuterWidth;
                $('.pagination').animate( { 
                    scrollLeft: left
                },'slow');
            }
        });

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }

        function fillRequestForm(itemObj){
            console.log(fillRequestForm);
            document.getElementById("dateId").value;
            document.getElementById("spotifyId").value = itemObj.id;
            document.getElementById("albumNameId").value = itemObj.trackName; 
            document.getElementById("artistNameId").value = itemObj.name; 
            document.getElementById("arTitleId").value = itemObj.song; 
            document.getElementById("refrenceId").value = Date.now(); 
            $vsong = itemObj.song;
            $pSong = itemObj.song;
            $vartist = itemObj.name;
            if(itemObj.image == false){
                $vimage = 'assets/img/img_singking.jpg';
            } else {
                $vimage = itemObj.image.url;
            }
            $("#vsong").text($vsong);
            $("#pSong").text($pSong);
            $("#vartist").text($vartist);
            $("#vimage").attr("src", $vimage);
            document.getElementById("divSubmitRequestId").scrollIntoView();
        }

        // function onSubmitformPopUp(){
        //     document.getElementById("songId").value = itemObj.song; 
        // }

        function showListing(){
            document.getElementById("albumListSectionId").scrollIntoView();
        }

        function searchPagination(page){           
            $("#activePageInputId").val(page + 1);
            $("#offsetInputId").val((page) * dispLimit);
            $("#searchFormId").submit();
        }

        function navSearchPagination(cond){
            var currentPage = $("#activePageInputId").val();
            var newPage = currentPage;
            var totalPageCount = $("#totalPageCount").val();
            switch (cond) {
                case 'minus':
                    if(newPage == '1') return;
                    newPage = parseInt(newPage) - 1;
                    break;

                case 'plus':                    
                    if(currentPage >= parseInt(totalPageCount - 1)) return;
                    newPage = parseInt(newPage) + 1;
                    break;
            
                default:
                    break;
            }

            $("#activePageInputId").val(newPage);
            $("#offsetInputId").val((newPage) * dispLimit);
            $("#searchFormId").submit();
        }

        function resetPageNo() {
            $("#activePageInputId").val('1');
        }
    </script>
</body>
</html>