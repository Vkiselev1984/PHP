<!DOCTYPE html>
<html>

<head>
    <title>Мое резюме</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php include 'job_data.php'; ?>
    <style>
        html,
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Jost', sans-serif;
        }
    </style>
</head>

<body class="w3-light-grey">
    <!-- Page Container -->
    <div class="w3-content w3-margin-top" style="max-width:1400px;">

        <!-- The Grid -->
        <div class="w3-row-padding">

            <!-- Left Column -->
            <div class="w3-third">

                <div class="w3-white w3-text-grey w3-card-4">
                    <div class="w3-display-container">
                        <img src="VladimirKiselev.jpg" style="width:100%" alt="Avatar">
                        <div class="w3-display-bottomleft w3-container w3-text-white">
                            <h2>Vladimir Kiselev</h2>
                        </div>
                    </div>
                    <div class="w3-container">
                        <p><i class="fa fa-briefcase fa-fw w3-margin-right w3-large w3-text-teal"></i>Front-end</p>
                        <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-teal"></i>Russia, Moscow</p>
                        <p><i class="fa fa-envelope fa-fw w3-margin-right w3-large w3-text-teal"></i>kiselev@gnivc.ru</a>
                        <p><i class="fa fa-envelope fa-fw w3-margin-right w3-large w3-text-teal"></i>kiselevwebspace@gmail.com</p>
                        <p><i class="fa fa-phone fa-fw w3-margin-right w3-large w3-text-teal"></i>+79854772319</p>
                        <hr>

                        <p class="w3-large"><b><i class="fa fa-asterisk fa-fw w3-margin-right w3-text-teal"></i>Skills</b></p>
                        <p>Html/CSS</p>
                        <div class="w3-light-grey w3-round-xlarge w3-small">
                            <div class="w3-container w3-center w3-round-xlarge w3-teal" style="width:80%">80%</div>
                        </div>
                        <p>JavaScript</p>
                        <div class="w3-light-grey w3-round-xlarge w3-small">
                            <div class="w3-container w3-center w3-round-xlarge w3-teal" style="width:40%">40%</div>
                        </div>
                        <p>Java</p>
                        <div class="w3-light-grey w3-round-xlarge w3-small">
                            <div class="w3-container w3-center w3-round-xlarge w3-teal" style="width:30%">30%</div>
                        </div>
                        <p>C#</p>
                        <div class="w3-light-grey w3-round-xlarge w3-small">
                            <div class="w3-container w3-center w3-round-xlarge w3-teal" style="width:30%">30%</div>
                        </div>
                        <p>Python</p>
                        <div class="w3-light-grey w3-round-xlarge w3-small">
                            <div class="w3-container w3-center w3-round-xlarge w3-teal" style="width:30%">30%</div>
                        </div>
                        <p>Adobe Photoshop</p>
                        <div class="w3-light-grey w3-round-xlarge w3-small">
                            <div class="w3-container w3-center w3-round-xlarge w3-teal" style="width:70%">70%</div>
                        </div>
                        <p>Photography</p>
                        <div class="w3-light-grey w3-round-xlarge w3-small">
                            <div class="w3-container w3-center w3-round-xlarge w3-teal" style="width:80%">
                                <div class="w3-center w3-text-white">80%</div>
                            </div>
                        </div>
                        <p>Illustrator</p>
                        <div class="w3-light-grey w3-round-xlarge w3-small">
                            <div class="w3-container w3-center w3-round-xlarge w3-teal" style="width:55%">55%</div>
                        </div>
                        <p>Text editor</p>
                        <div class="w3-light-grey w3-round-xlarge w3-small">
                            <div class="w3-container w3-center w3-round-xlarge w3-teal" style="width:90%">90%</div>
                        </div>
                        <br>

                        <p class="w3-large w3-text-theme"><b><i class="fa fa-globe fa-fw w3-margin-right w3-text-teal"></i>Languages</b></p>
                        <p>English</p>
                        <div class="w3-light-grey w3-round-xlarge">
                            <div class="w3-round-xlarge w3-teal" style="height:24px;width:100%"></div>
                        </div>
                        <br>
                    </div>
                </div><br>

                <!-- End Left Column -->
            </div>

            <!-- Right Column -->

            <div class="w3-twothird">

                <div class="w3-container w3-card w3-white w3-margin-bottom">
                    <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-suitcase fa-fw w3-margin-right w3-xxlarge w3-text-teal"></i>Work experience</h2>
                    <div class="w3-container">
                        <h5 class="w3-opacity"><b>Front-end developer, web-developer </b></h5>
                        <h6 class="w3-text-teal"><i class="fa fa-calendar fa-fw w3-margin-right"></i><?php echo $job['job_data'][0]; ?> - <span class="w3-tag w3-teal w3-round">Current</span></h6>
                        <p>Chief Specialist of the Interactive Services Software Design and Development Department</p>

                        <p><?php echo $job['job_name'][0]; ?></p>
                        <p><?php echo $job['job_desc'][0]; ?></p>
                        <hr>
                    </div>
                    <div class="w3-container">
                        <h5 class="w3-opacity"><b>Press Secretary</b></h5>
                        <h6 class="w3-text-teal"><i class="fa fa-calendar fa-fw w3-margin-right"></i><?php echo $job['job_data'][1]; ?></h6>
                        <p>Press Secretary of the Federal Tax Service of Russia for the Moscow region</p>
                        <p><?php echo $job['job_name'][1]; ?></p>
                        <p><?php echo $job['job_desc'][1]; ?></p>
                        <hr>
                    </div>
                    <div class="w3-container">
                        <h5 class="w3-opacity"><b>Economist/Reporter</b></h5>
                        <h6 class="w3-text-teal"><i class="fa fa-calendar fa-fw w3-margin-right"></i><?php echo $job['job_data'][2]; ?></h6>
                        <p>Open Economy Center for Economic Research and Dissemination of Economic Information Foundation
                        </p>
                        <p><?php echo $job['job_name'][2]; ?></p>
                        <p><?php echo $job['job_desc'][2]; ?></p>
                    </div>
                </div>

                <div class="w3-container w3-card w3-white w3-margin-bottom">
                    <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-certificate fa-fw w3-margin-right w3-xxlarge w3-text-teal"></i>Education</h2>
                    <div class="w3-container">
                        <h5 class="w3-opacity"><b>GeekBrains</b></h5>
                        <h6 class="w3-text-teal"><i class="fa fa-calendar fa-fw w3-margin-right"></i>2024- <span class="w3-tag w3-teal w3-round">Current</span></h6>
                        <p>Developer</p>
                        <hr>
                    </div>
                    <div class="w3-container">
                        <h5 class="w3-opacity"><b>GeekBrains</b></h5>
                        <h6 class="w3-text-teal"><i class="fa fa-calendar fa-fw w3-margin-right"></i>2019- 2023</h6>
                        <p>Faculty of Web Development</p>
                        <hr>
                    </div>

                    <div class="w3-container">
                        <h5 class="w3-opacity"><b>International Independent Ecological and Political Science University</b></h5>
                        <h6 class="w3-text-teal"><i class="fa fa-calendar fa-fw w3-margin-right"></i>2001-2006</h6>
                        <p>Faculty of Finance and Credit</p>
                        <hr>
                    </div>



                </div>

                <div class="w3-container w3-card w3-white">
                    <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-certificate fa-fw w3-margin-right w3-xxlarge w3-text-teal"></i>Courses</h2>

                    <div class="w3-container">
                        <h6 class="w3-text-teal"><i class="fa fa-calendar fa-fw w3-margin-right"></i>2013</h6>
                        <p>Organization of work of tax authorities with mass media</p>
                        <hr>
                    </div>

                    <div class="w3-container">
                        <h6 class="w3-text-teal"><i class="fa fa-calendar fa-fw w3-margin-right"></i>2006, 2007, 2010</h6>
                        <p>Information services to taxpayers, the use of Internet technologies in the work of tax authorities</p>
                        <hr>
                    </div>

                    <div class="w3-container">
                        <h6 class="w3-text-teal"><i class="fa fa-calendar fa-fw w3-margin-right"></i>2009</h6>
                        <p>Administration and information content of the website system of the Federal Tax Service of Russia for the
                            subjects of the Russian Federation</p>
                        <hr>
                    </div>

                    <div class="w3-container">
                        <h6 class="w3-text-teal"><i class="fa fa-calendar fa-fw w3-margin-right"></i>2008</h6>
                        <p>Information services for taxpayers</p>
                        <hr>
                    </div>



                </div>


                <!-- End Right Column -->
            </div>

            <!-- End Grid -->
        </div>

        <!-- End Page Container -->
    </div>

    <!-- Footer -->
    <footer class="w3-container w3-teal w3-center w3-margin-top">
        <?php include 'connectdb.php'; ?>
        <p>Find me on social media.</p>
        <a href="https://github.com/Vkiselev1984"> <i class="fa fa-github w3-hover-opacity"></i></a>
        <a href="https://vk.com/volodya_kiselev"><i class="fa fa-vk w3-hover-opacity"></i></a>

        <!-- End footer -->
    </footer>

</body>

</html>